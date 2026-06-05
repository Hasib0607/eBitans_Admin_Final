<?php

namespace App\Services;

use Google\Service\Drive\DriveFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Symfony\Component\Process\Process;

class BackupManager
{
    public function latestFileFromFolder(string $folder, array $extensions = ['zip', 'sql']): ?string
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

    public function ensureCleanDirectory(string $path): void
    {
        if (file_exists($path)) {
            File::deleteDirectory($path);
        }

        mkdir($path, 0775, true);
    }

    public function statusFile(): string
    {
        return storage_path('app/backup-status.json');
    }

    public function nightlyStatusFile(): string
    {
        return storage_path('app/nightly-backup-status.json');
    }

    public function getNightlyStatus(): array
    {
        $default = [
            'type' => 'nightly-drive-backup',
            'status' => 'idle',
            'progress' => 0,
            'message' => 'Nightly auto backup has not run yet.',
            'time' => null,
            'backup_status' => 'idle',
            'backup_message' => 'Nightly local backup has not run yet.',
            'backup_time' => null,
            'upload_status' => 'idle',
            'upload_message' => 'Nightly Drive upload has not run yet.',
            'upload_time' => null,
        ];

        if (!file_exists($this->nightlyStatusFile())) {
            return $default;
        }

        $decoded = json_decode((string) file_get_contents($this->nightlyStatusFile()), true);

        return is_array($decoded) ? array_merge($default, $decoded) : $default;
    }

    public function setStatus(string $type, string $status, int $progress, string $message): void
    {
        file_put_contents($this->statusFile(), json_encode([
            'type' => $type,
            'status' => $status,
            'progress' => $progress,
            'message' => $message,
            'time' => now()->toDateTimeString(),
        ]));
    }

    public function setNightlyStatus(string $status, int $progress, string $message): void
    {
        $payload = array_merge($this->getNightlyStatus(), [
            'type' => 'nightly-drive-backup',
            'status' => $status,
            'progress' => $progress,
            'message' => $message,
            'time' => now()->toDateTimeString(),
        ]);

        file_put_contents($this->nightlyStatusFile(), json_encode($payload));
    }

    public function setNightlyStageStatus(string $stage, string $status, string $message): void
    {
        if (!in_array($stage, ['backup', 'upload'], true)) {
            return;
        }

        $payload = $this->getNightlyStatus();
        $payload[$stage . '_status'] = $status;
        $payload[$stage . '_message'] = $message;
        $payload[$stage . '_time'] = now()->toDateTimeString();

        file_put_contents($this->nightlyStatusFile(), json_encode($payload));
    }

    public function ensureDirectory(string $path): void
    {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
    }

    public function backupDatabase(): void
    {
        $backupDir = storage_path('app/backups/database');
        $this->ensureDirectory($backupDir);

        $fileName = 'database-backup-' . date('Y-m-d-H-i-s') . '.sql';
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $fileName;

        $command = sprintf(
            'mysqldump --host=%s --port=%s --protocol=TCP --user=%s --password=%s --single-transaction --quick --routines --triggers --events --default-character-set=utf8mb4 %s > %s 2>&1',
            escapeshellarg(env('DB_HOST', '127.0.0.1')),
            escapeshellarg(env('DB_PORT', '3306')),
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_DATABASE')),
            escapeshellarg($filePath)
        );

        exec($command, $output, $resultCode);

        if ($resultCode !== 0 || !file_exists($filePath) || filesize($filePath) <= 0) {
            throw new \Exception('Database backup failed: ' . $this->commandOutputMessage($output));
        }
    }

    public function backupCode(): void
    {
        $backupDir = storage_path('app/backups/code');
        $this->ensureDirectory($backupDir);

        $fileName = 'code-backup-' . date('Y-m-d-H-i-s') . '.zip';
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $fileName;

        $command = sprintf(
            "cd %s && zip -rq %s . "
            . "-x 'public/*' "
            . "-x 'node_modules/*' "
            . "-x 'storage/app/backups/*' "
            . "-x 'storage/app/backup-tmp/*' "
            . "-x 'storage/app/public/*' "
            . "-x '.env' "
            . "2>&1",
            escapeshellarg(base_path()),
            escapeshellarg($filePath)
        );

        exec($command, $output, $resultCode);

        if ($resultCode !== 0 || !file_exists($filePath) || filesize($filePath) <= 0) {
            throw new \Exception('Code backup failed.');
        }
    }

    public function backupPublic(): void
    {
        $backupDir = storage_path('app/backups/public');
        $this->ensureDirectory($backupDir);

        $fileName = 'public-backup-' . date('Y-m-d-H-i-s') . '.zip';
        $filePath = $backupDir . DIRECTORY_SEPARATOR . $fileName;

        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $command = sprintf(
            "cd %s && zip -rq %s public "
            . "-x 'storage/app/backups/*' "
            . "-x 'storage/app/backup-tmp/*' "
            . "2>&1",
            escapeshellarg(base_path()),
            escapeshellarg($filePath)
        );

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            throw new \Exception('Public backup failed: ' . implode("\n", $output));
        }

        clearstatcache();

        if (!file_exists($filePath)) {
            throw new \Exception('Public backup file was not created.');
        }

        if (filesize($filePath) <= 0) {
            @unlink($filePath);
            throw new \Exception('Public backup file is empty.');
        }
    }

    public function googleClient(): GoogleClient
    {
        $client = new GoogleClient();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->addScope(GoogleDrive::DRIVE);
        $client->fetchAccessTokenWithRefreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));

        return $client;
    }

    public function getOrCreateDriveDateFolder(GoogleDrive $drive): string
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

    public function deleteOldDriveDateFolders(GoogleDrive $drive, int $days = 3): void
    {
        $rootFolderId = env('GOOGLE_DRIVE_FOLDER');
        $cutoffDate = now()->subDays($days)->format('Y-m-d');

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

    public function availableDriveRestoreDates(): array
    {
        $prefixMap = [
            'database' => 'database-backup-',
            'code' => 'code-backup-',
            'public' => 'public-backup-',
        ];

        $client = $this->googleClient();
        $drive = new GoogleDrive($client);
        $rootFolderId = env('GOOGLE_DRIVE_FOLDER');

        $folders = collect($drive->files->listFiles([
            'q' => sprintf(
                "'%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
                $rootFolderId
            ),
            'fields' => 'files(id,name)',
            'pageSize' => 200,
        ])->files)->sortByDesc('name');

        $results = [];

        foreach ($folders as $folder) {
            $folderName = $folder->name ?? '';

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $folderName)) {
                continue;
            }

            $files = $drive->files->listFiles([
                'q' => sprintf("'%s' in parents and trashed = false", $folder->id),
                'fields' => 'files(name)',
                'pageSize' => 200,
            ]);

            $availableTypes = [];

            foreach ($prefixMap as $type => $prefix) {
                $exists = collect($files->files)->contains(function ($file) use ($prefix) {
                    return str_starts_with($file->name ?? '', $prefix);
                });

                if ($exists) {
                    $availableTypes[] = $type;
                }
            }

            if (!empty($availableTypes)) {
                $results[] = [
                    'date' => $folderName,
                    'types' => $availableTypes,
                ];
            }
        }

        return $results;
    }

    public function latestDriveBackupFileMeta(string $type, ?string $preferredDate = null): array
    {
        $prefixMap = [
            'database' => 'database-backup-',
            'code' => 'code-backup-',
            'public' => 'public-backup-',
        ];

        if (!isset($prefixMap[$type])) {
            throw new \Exception('Invalid restore type selected.');
        }

        $client = $this->googleClient();
        $drive = new GoogleDrive($client);
        $rootFolderId = env('GOOGLE_DRIVE_FOLDER');

        $folders = collect($drive->files->listFiles([
            'q' => sprintf(
                "'%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
                $rootFolderId
            ),
            'fields' => 'files(id,name)',
            'pageSize' => 200,
        ])->files)->sortByDesc('name');

        if ($preferredDate !== null) {
            $folders = $folders->filter(function ($folder) use ($preferredDate) {
                return ($folder->name ?? '') === $preferredDate;
            });
        }

        foreach ($folders as $folder) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $folder->name ?? '')) {
                continue;
            }

            $files = $drive->files->listFiles([
                'q' => sprintf("'%s' in parents and trashed = false", $folder->id),
                'fields' => 'files(id,name,mimeType)',
                'pageSize' => 200,
            ]);

            $matched = collect($files->files)
                ->filter(function ($file) use ($prefixMap, $type) {
                    return str_starts_with($file->name ?? '', $prefixMap[$type]);
                })
                ->sortByDesc('name')
                ->first();

            if ($matched) {
                return [
                    'folder_id' => $folder->id,
                    'folder_name' => $folder->name,
                    'file_id' => $matched->id,
                    'file_name' => $matched->name,
                    'mime_type' => $matched->mimeType ?? null,
                ];
            }
        }

        if ($preferredDate !== null) {
            throw new \Exception('No Google Drive backup found for ' . $type . ' on ' . $preferredDate . '.');
        }

        throw new \Exception('No Google Drive backup found for ' . $type . '.');
    }

    public function driveFileSize(string $fileId): int
    {
        $client = $this->googleClient();
        $drive = new GoogleDrive($client);
        $meta = $drive->files->get($fileId, ['fields' => 'size']);

        return (int) ($meta->size ?? 0);
    }

    public function downloadDriveFile(string $fileId, string $destinationPath, ?callable $progressCallback = null): void
    {
        $client = $this->googleClient();
        $httpClient = $client->authorize();
        $token = $client->getAccessToken();
        $accessToken = is_array($token) ? ($token['access_token'] ?? '') : '';

        if ($accessToken === '') {
            throw new \Exception('Could not authorize Google Drive download.');
        }

        $size = $this->driveFileSize($fileId);
        $handle = fopen($destinationPath, 'wb');

        if ($handle === false) {
            throw new \Exception('Unable to create restore file.');
        }

        try {
            $response = $httpClient->request('GET', 'https://www.googleapis.com/drive/v3/files/' . $fileId . '?alt=media', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                'stream' => true,
            ]);

            $body = $response->getBody();
            $downloadedBytes = 0;

            while (!$body->eof()) {
                $chunk = $body->read(1024 * 1024);

                if ($chunk === '') {
                    continue;
                }

                fwrite($handle, $chunk);
                $downloadedBytes += strlen($chunk);

                if ($progressCallback && $size > 0) {
                    $percent = (int) floor(($downloadedBytes / max($size, 1)) * 100);
                    $progressCallback(max(0, min(100, $percent)));
                }
            }
        } catch (\Throwable $e) {
            fclose($handle);
            @unlink($destinationPath);
            throw $e;
        }

        fclose($handle);

        if (!file_exists($destinationPath) || filesize($destinationPath) <= 0) {
            @unlink($destinationPath);
            throw new \Exception('Downloaded restore file is empty.');
        }
    }

    public function restoreDatabaseFromFile(string $filePath, ?callable $progressCallback = null): ?string
    {
        if (!file_exists($filePath) || filesize($filePath) <= 0) {
            throw new \Exception('Database restore failed: SQL backup file is missing or empty.');
        }

        $backupContainsUsers = $this->sqlBackupContainsUsersTable($filePath);

        $command = sprintf(
            '(printf %%s\n "SET NAMES utf8mb4;" "SET FOREIGN_KEY_CHECKS=0;" "SET UNIQUE_CHECKS=0;" "SET SQL_MODE=NO_AUTO_VALUE_ON_ZERO;"; cat %s; printf %%s\n "SET FOREIGN_KEY_CHECKS=1;" "SET UNIQUE_CHECKS=1;") | mysql --host=%s --port=%s --protocol=TCP --user=%s --password=%s --default-character-set=utf8mb4 --binary-mode=1 %s 2>&1',
            escapeshellarg($filePath),
            escapeshellarg(env('DB_HOST', '127.0.0.1')),
            escapeshellarg(env('DB_PORT', '3306')),
            escapeshellarg(env('DB_USERNAME')),
            escapeshellarg(env('DB_PASSWORD')),
            escapeshellarg(env('DB_DATABASE'))
        );

        [$exitCode, $output] = $this->runShellCommandWithHeartbeat($command, $progressCallback);

        if ($exitCode !== 0) {
            throw new \Exception('Database restore failed: ' . $this->commandOutputMessage([$output]));
        }

        $this->verifyUsersTableRestored($backupContainsUsers);

        $message = trim($output);

        return $message === '' ? null : 'Database restored with warnings: ' . mb_substr($message, 0, 600);
    }

    protected function sqlBackupContainsUsersTable(string $filePath): bool
    {
        $handle = fopen($filePath, 'rb');

        if ($handle === false) {
            return false;
        }

        while (!feof($handle)) {
            $chunk = fread($handle, 1024 * 1024);

            if ($chunk === false || $chunk === '') {
                break;
            }

            if (preg_match('/(INSERT INTO|CREATE TABLE)\s+`?users`?\b/i', $chunk)) {
                fclose($handle);

                return true;
            }
        }

        fclose($handle);

        return false;
    }

    protected function verifyUsersTableRestored(bool $backupContainsUsers): void
    {
        if (!$backupContainsUsers) {
            return;
        }

        try {
            $userCount = (int) DB::table('users')->count();
        } catch (\Throwable $e) {
            throw new \Exception('Database restore finished but users table could not be verified: ' . $e->getMessage());
        }

        if ($userCount === 0) {
            throw new \Exception(
                'Database restore finished but users table is empty. '
                . 'This usually means foreign key constraints blocked table recreation during import.'
            );
        }
    }

    protected function runShellCommandWithHeartbeat(string $command, ?callable $progressCallback = null): array
    {
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);
        $process->setIdleTimeout(null);
        $process->start();

        $output = '';
        $startedAt = time();
        $lastProgressAt = 0;

        while ($process->isRunning()) {
            $output = $this->appendCommandOutput($output, $process->getIncrementalOutput());
            $output = $this->appendCommandOutput($output, $process->getIncrementalErrorOutput());

            $elapsedSeconds = time() - $startedAt;

            if ($progressCallback && ($elapsedSeconds - $lastProgressAt) >= 5) {
                $lastProgressAt = $elapsedSeconds;
                $progressCallback($elapsedSeconds);
            }

            usleep(500000);
        }

        $process->wait();
        $output = $this->appendCommandOutput($output, $process->getIncrementalOutput());
        $output = $this->appendCommandOutput($output, $process->getIncrementalErrorOutput());

        return [$process->getExitCode() ?? 1, $output];
    }

    protected function appendCommandOutput(string $current, string $chunk): string
    {
        if ($chunk === '') {
            return $current;
        }

        return mb_substr($current . $chunk, -4000);
    }

    protected function commandOutputMessage(array $output): string
    {
        $message = trim(implode("\n", array_filter($output)));

        if ($message === '') {
            return 'No command output returned. Please verify MySQL client, DB credentials, and database permissions.';
        }

        return mb_substr($message, 0, 1000);
    }

    public function restoreZipToBasePath(string $filePath, ?callable $progressCallback = null): ?string
    {
        if (!file_exists($filePath) || filesize($filePath) <= 0) {
            throw new \Exception('Zip restore failed: backup file is missing or empty.');
        }

        $command = sprintf(
            'unzip -oq %s -d %s -x %s',
            escapeshellarg($filePath),
            escapeshellarg(base_path()),
            escapeshellarg('.env')
        );

        [$exitCode, $output] = $this->runShellCommandWithHeartbeat($command, $progressCallback);

        if ($exitCode > 1) {
            throw new \Exception('Zip restore failed: ' . $this->commandOutputMessage([$output]));
        }

        $message = trim($output);

        return $exitCode === 1 || $message !== '' ? 'Zip restored with warnings: ' . mb_substr($message ?: 'unzip finished with warnings.', 0, 600) : null;
    }

    public function uploadLatestSelectedToDrive(array $types, ?callable $progressCallback = null): array
    {
        $selected = array_values(array_intersect(['database', 'code', 'public'], $types));

        if (empty($selected)) {
            throw new \Exception('Please select at least one backup type.');
        }

        $client = $this->googleClient();
        $drive = new GoogleDrive($client);
        $dateFolderId = $this->getOrCreateDriveDateFolder($drive);

        $folders = [
            'database' => storage_path('app/backups/database'),
            'code' => storage_path('app/backups/code'),
            'public' => storage_path('app/backups/public'),
        ];

        $total = count($selected);
        $done = 0;
        $uploadedAny = false;
        $uploadedFiles = [];

        foreach ($selected as $type) {
            $folder = $folders[$type] ?? null;

            if (!$folder) {
                continue;
            }

            $latestFile = $this->latestFileFromFolder($folder, ['zip', 'sql']);

            if (!$latestFile || !file_exists($latestFile)) {
                continue;
            }

            $uploadedAny = true;

            $fileName = basename($latestFile);
            $mimeType = str_ends_with(strtolower($fileName), '.sql')
                ? 'application/sql'
                : 'application/zip';

            $baseProgress = 10 + (int) (($done / max($total, 1)) * 70);

            if ($progressCallback) {
                $progressCallback(
                    $baseProgress,
                    'Uploading ' . ucfirst($type) . ' backup to Google Drive...'
                );
            }

            $driveFile = new DriveFile([
                'name' => $fileName,
                'parents' => [$dateFolderId],
            ]);

            $fileSize = filesize($latestFile);

            $client->setDefer(true);

            $requestUpload = $drive->files->create($driveFile, [
                'fields' => 'id,name',
                'uploadType' => 'resumable',
            ]);

            $chunkSizeBytes = 10 * 1024 * 1024;

            $media = new \Google\Http\MediaFileUpload(
                $client,
                $requestUpload,
                $mimeType,
                null,
                true,
                $chunkSizeBytes
            );

            $media->setFileSize($fileSize);

            $handle = fopen($latestFile, 'rb');

            if ($handle === false) {
                throw new \Exception('Unable to open file: ' . $fileName);
            }

            $status = false;
            $uploadedBytes = 0;

            while (!$status && !feof($handle)) {
                $chunk = fread($handle, $chunkSizeBytes);
                $uploadedBytes += strlen($chunk);

                $status = $media->nextChunk($chunk);

                $filePercent = (int) (($uploadedBytes / max($fileSize, 1)) * 100);
                $globalProgress = $baseProgress + (int) ($filePercent * (70 / max($total, 1)) / 100);

                if ($globalProgress > 95) {
                    $globalProgress = 95;
                }

                if ($progressCallback) {
                    $progressCallback(
                        $globalProgress,
                        'Uploading ' . ucfirst($type) . '... ' . $filePercent . '%'
                    );
                }
            }

            fclose($handle);
            $client->setDefer(false);

            if (!$status) {
                throw new \Exception('Upload failed for: ' . $fileName);
            }

            $uploadedFiles[] = $fileName;
            $done++;
        }

        if (!$uploadedAny) {
            throw new \Exception('No backup files found for the selected option(s).');
        }

        if ($progressCallback) {
            $progressCallback(96, 'Cleaning old Google Drive folders...');
        }

        $this->deleteOldDriveDateFolders($drive);

        return $uploadedFiles;
    }

    public function cleanupSelected(array $types, int $days): array
    {
        $types = array_values(array_intersect(['database', 'code', 'public'], $types));

        if (empty($types)) {
            throw new \Exception('Please select at least one backup type.');
        }

        $folderMap = [
            'database' => storage_path('app/backups/database'),
            'code' => storage_path('app/backups/code'),
            'public' => storage_path('app/backups/public'),
        ];

        $prefixMap = [
            'database' => 'database-backup-',
            'code' => 'code-backup-',
            'public' => 'public-backup-',
        ];

        $localDeleted = 0;
        $cutoffLocal = strtotime("-{$days} days");

        foreach ($types as $type) {
            $folder = $folderMap[$type] ?? null;

            if (!$folder || !is_dir($folder)) {
                continue;
            }

            foreach (File::allFiles($folder) as $file) {
                if ($file->getMTime() < $cutoffLocal) {
                    @unlink($file->getPathname());
                    $localDeleted++;
                }
            }
        }

        $client = $this->googleClient();
        $drive = new GoogleDrive($client);

        $rootFolderId = env('GOOGLE_DRIVE_FOLDER');
        $cutoffDate = now()->subDays($days)->format('Y-m-d');
        $driveDeleted = 0;

        $folders = $drive->files->listFiles([
            'q' => sprintf(
                "'%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
                $rootFolderId
            ),
            'fields' => 'files(id,name)',
            'pageSize' => 200,
        ]);

        foreach ($folders->files as $folder) {
            $folderName = $folder->name;

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $folderName)) {
                continue;
            }

            if ($folderName >= $cutoffDate) {
                continue;
            }

            $files = $drive->files->listFiles([
                'q' => sprintf("'%s' in parents and trashed = false", $folder->id),
                'fields' => 'files(id,name)',
                'pageSize' => 200,
            ]);

            foreach ($files->files as $file) {
                foreach ($types as $type) {
                    $prefix = $prefixMap[$type];

                    if (str_starts_with($file->name, $prefix)) {
                        $drive->files->delete($file->id);
                        $driveDeleted++;
                        break;
                    }
                }
            }

            $remainingFiles = $drive->files->listFiles([
                'q' => sprintf("'%s' in parents and trashed = false", $folder->id),
                'fields' => 'files(id)',
                'pageSize' => 5,
            ]);

            if (empty($remainingFiles->files)) {
                $drive->files->delete($folder->id);
            }
        }

        return [
            'local' => $localDeleted,
            'drive' => $driveDeleted,
        ];
    }
}
