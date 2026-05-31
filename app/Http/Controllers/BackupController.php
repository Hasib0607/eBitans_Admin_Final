<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Services\BackupManager;

class BackupController extends Controller
{
    protected function statusFile(): string
    {
        return storage_path('app/backup-status.json');
    }

    protected function setStatus(string $type, string $status, int $progress, string $message): void
    {
        file_put_contents($this->statusFile(), json_encode([
            'type' => $type,
            'status' => $status,
            'progress' => $progress,
            'message' => $message,
            'time' => now()->toDateTimeString(),
        ]));
    }

    protected function latestFileFromFolder(string $folder, array $extensions = ['zip', 'sql']): ?string
    {
        if (!is_dir($folder)) {
            return null;
        }

        $files = collect(File::allFiles($folder))
            ->filter(function ($file) use ($extensions) {
                return in_array(strtolower($file->getExtension()), $extensions, true);
            })
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            });

        return $files->isEmpty() ? null : $files->first()->getPathname();
    }

    protected function googleClient(): GoogleClient
    {
        $client = new GoogleClient();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->addScope(GoogleDrive::DRIVE);
        $client->fetchAccessTokenWithRefreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));

        return $client;
    }

    protected function getOrCreateDriveDateFolder(GoogleDrive $drive): string
    {
        $rootFolderId = env('GOOGLE_DRIVE_FOLDER');
        $dateFolderName = now()->format('Y-m-d');

        $existingFolders = $drive->files->listFiles([
            'q' => sprintf(
                "name = '%s' and '%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
                addslashes($dateFolderName),
                $rootFolderId
            ),
            'fields' => 'files(id,name)',
            'pageSize' => 10,
        ]);

        if (!empty($existingFolders->files)) {
            return $existingFolders->files[0]->id;
        }

        $folderMetadata = new DriveFile([
            'name' => $dateFolderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$rootFolderId],
        ]);

        $folder = $drive->files->create($folderMetadata, [
            'fields' => 'id,name',
        ]);

        return $folder->id;
    }

    protected function deleteOldDriveDateFolders(GoogleDrive $drive): void
    {
        $rootFolderId = env('GOOGLE_DRIVE_FOLDER');
        $cutoffDate = now()->subDays(3)->format('Y-m-d');

        $folders = $drive->files->listFiles([
            'q' => sprintf(
                "'%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
                $rootFolderId
            ),
            'fields' => 'files(id,name,createdTime)',
            'pageSize' => 200,
        ]);

        foreach ($folders->files as $folder) {
            $folderName = $folder->name;

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $folderName) && $folderName < $cutoffDate) {
                $drive->files->delete($folder->id);
            }
        }
    }

    public function status(BackupManager $manager): JsonResponse
    {
        $file = $manager->statusFile();

        if (!file_exists($file)) {
            return response()->json([
                'type' => null,
                'status' => 'idle',
                'progress' => 0,
                'message' => 'No task running.',
            ]);
        }

        return response()->json(json_decode(file_get_contents($file), true));
    }

    public function startSelectedBackup(Request $request, BackupManager $manager)
    {
        $types = $request->input('types', []);

        if (!is_array($types) || empty($types)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one backup type.'
            ], 422);
        }

        $types = array_values(array_intersect(['database', 'code', 'public'], $types));

        if (empty($types)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid backup type selected.'
            ], 422);
        }

        $manager->setStatus('backup-batch', 'queued', 5, 'Backup queued...');

        $phpPath = trim(shell_exec('which php'));
        if (empty($phpPath)) {
            $phpPath = PHP_BINARY;
        }

        $artisanPath = base_path('artisan');
        $logPath = storage_path('logs/backup-background.log');

        $args = implode(' ', array_map('escapeshellarg', $types));

        $command = sprintf(
            'nohup %s %s backup:selected %s >> %s 2>&1 &',
            escapeshellarg($phpPath),
            escapeshellarg($artisanPath),
            $args,
            escapeshellarg($logPath)
        );

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            $manager->setStatus('backup-batch', 'failed', 100, 'Failed to start background backup.');

            return response()->json([
                'success' => false,
                'message' => 'Failed to start background backup.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Backup started in background.'
        ]);
    }

    public function uploadSelectedToDrive(Request $request)
    {
        try {
            $selected = $request->input('types', []);

            if (!is_array($selected) || empty($selected)) {
                throw new \Exception('Please select at least one backup type.');
            }

            $allowed = ['database', 'code', 'public'];
            $selected = array_values(array_intersect($allowed, $selected));

            if (empty($selected)) {
                throw new \Exception('Invalid backup type selected.');
            }

            $this->setStatus('selected-drive', 'running', 10, 'Connecting to Google Drive...');
            app(BackupManager::class)->uploadLatestSelectedToDrive($selected, function (int $progress, string $message) {
                $this->setStatus('selected-drive', 'running', $progress, $message);
            });

            $this->setStatus('selected-drive', 'completed', 100, 'Selected backup(s) uploaded successfully.');

            return response()->json([
                'success' => true,
                'message' => 'Selected backup(s) uploaded successfully.',
            ]);
        } catch (\Throwable $e) {
            $this->setStatus('selected-drive', 'failed', 100, $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function restoreSelectedFromDrive(Request $request, BackupManager $manager): JsonResponse
    {
        $types = $request->input('types', []);
        $backupDate = (string) $request->input('backup_date', '');

        if (!is_array($types) || empty($types)) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one backup type.'
            ], 422);
        }

        $types = array_values(array_intersect(['database', 'code', 'public'], $types));

        if (empty($types)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid backup type selected.'
            ], 422);
        }

        if ($backupDate === '') {
            return response()->json([
                'success' => false,
                'message' => 'Please select a backup date.'
            ], 422);
        }

        $manager->setStatus('restore-drive', 'queued', 5, 'Google Drive restore queued...');

        $phpPath = trim(shell_exec('which php'));
        if (empty($phpPath)) {
            $phpPath = PHP_BINARY;
        }

        $artisanPath = base_path('artisan');
        $logPath = storage_path('logs/backup-restore-background.log');
        $args = implode(' ', array_map('escapeshellarg', $types));

        $command = sprintf(
            'nohup %s %s backup:restore-selected-drive --date=%s %s >> %s 2>&1 &',
            escapeshellarg($phpPath),
            escapeshellarg($artisanPath),
            escapeshellarg($backupDate),
            $args,
            escapeshellarg($logPath)
        );

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            $manager->setStatus('restore-drive', 'failed', 100, 'Failed to start Google Drive restore.');

            return response()->json([
                'success' => false,
                'message' => 'Failed to start Google Drive restore.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Google Drive restore started in background.'
        ]);
    }

    public function driveRestoreOptions(BackupManager $manager): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'items' => $manager->availableDriveRestoreDates(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'items' => [],
            ], 422);
        }
    }

    public function deleteSelected(Request $request, BackupManager $manager)
    {
        try {
            $types = $request->input('types', []);
            $days = (int) $request->input('days', 7);

            if (!in_array($days, [7, 15, 30], true)) {
                throw new \Exception('Please select 7, 15, or 30 days.');
            }

            if (!is_array($types) || empty($types)) {
                throw new \Exception('Please select at least one backup type.');
            }

            $manager->setStatus('delete-batch', 'running', 10, 'Deleting selected backups...');

            $result = $manager->cleanupSelected($types, $days);

            $message = "Deleted {$result['local']} local and {$result['drive']} Drive backup(s).";
            $manager->setStatus('delete-batch', 'completed', 100, $message);

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Throwable $e) {
            $manager->setStatus('delete-batch', 'failed', 100, $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
