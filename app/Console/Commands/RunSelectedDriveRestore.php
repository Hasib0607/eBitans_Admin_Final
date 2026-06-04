<?php

namespace App\Console\Commands;

use App\Services\BackupManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RunSelectedDriveRestore extends Command
{
    protected $signature = 'backup:restore-selected-drive {types*} {--date=}';
    protected $description = 'Restore selected backups from Google Drive';

    public function handle(BackupManager $manager): int
    {
        $tmpDir = storage_path('app/backup-restore-tmp');

        try {
            $backupDate = (string) $this->option('date');
            $types = array_values(array_intersect(
                ['database', 'code', 'public'],
                $this->argument('types')
            ));

            if (empty($types)) {
                $manager->setStatus('restore-drive', 'failed', 100, 'No valid restore type selected.');
                return self::FAILURE;
            }

            if ($backupDate === '') {
                $manager->setStatus('restore-drive', 'failed', 100, 'No backup date selected.');
                return self::FAILURE;
            }

            $manager->ensureCleanDirectory($tmpDir);

            $totalPhases = max(count($types) * 2, 1);
            $completedPhases = 0;

            $manager->setStatus('restore-drive', 'running', 10, 'Preparing Google Drive restore...');

            foreach ($types as $type) {
                $downloadPhaseStart = 10 + (int) floor(($completedPhases / $totalPhases) * 80);
                $downloadPhaseEnd = 10 + (int) floor((($completedPhases + 1) / $totalPhases) * 80);

                $manager->setStatus(
                    'restore-drive',
                    'running',
                    $downloadPhaseStart,
                    'Finding ' . ucfirst($type) . ' backup from ' . $backupDate . ' on Google Drive...'
                );

                $meta = $manager->latestDriveBackupFileMeta($type, $backupDate);
                $targetPath = $tmpDir . DIRECTORY_SEPARATOR . $meta['file_name'];

                $manager->setStatus(
                    'restore-drive',
                    'running',
                    $downloadPhaseStart,
                    'Downloading ' . $meta['file_name'] . '...'
                );
                $manager->downloadDriveFile($meta['file_id'], $targetPath, function (int $percent) use (
                    $manager,
                    $meta,
                    $downloadPhaseStart,
                    $downloadPhaseEnd
                ) {
                    $progressRange = max($downloadPhaseEnd - $downloadPhaseStart, 1);
                    $mappedProgress = $downloadPhaseStart + (int) floor(($percent / 100) * $progressRange);

                    $manager->setStatus(
                        'restore-drive',
                        'running',
                        min($downloadPhaseEnd, max($downloadPhaseStart, $mappedProgress)),
                        'Downloading ' . $meta['file_name'] . '... ' . $percent . '%'
                    );
                });
                $completedPhases++;

                $restorePhaseStart = 10 + (int) floor(($completedPhases / $totalPhases) * 80);
                $restorePhaseEnd = 10 + (int) floor((($completedPhases + 1) / $totalPhases) * 80);
                $manager->setStatus(
                    'restore-drive',
                    'running',
                    $restorePhaseStart,
                    'Restoring ' . ucfirst($type) . ' backup...'
                );

                $restoreMessage = null;

                if ($type === 'database') {
                    $restoreMessage = $manager->restoreDatabaseFromFile(
                        $targetPath,
                        function (int $elapsedSeconds) use ($manager, $restorePhaseStart, $restorePhaseEnd) {
                            $progressRange = max($restorePhaseEnd - $restorePhaseStart, 1);
                            $elapsedProgress = min($progressRange - 1, (int) floor($elapsedSeconds / 30));
                            $elapsedText = gmdate($elapsedSeconds >= 3600 ? 'H\h i\m s\s' : 'i\m s\s', $elapsedSeconds);

                            $manager->setStatus(
                                'restore-drive',
                                'running',
                                $restorePhaseStart + max(0, $elapsedProgress),
                                'Restoring Database backup... ' . $elapsedText . ' elapsed'
                            );
                        }
                    );
                } else {
                    $manager->restoreZipToBasePath($targetPath);
                }

                $manager->setStatus(
                    'restore-drive',
                    'running',
                    $restorePhaseEnd,
                    $restoreMessage ?: ucfirst($type) . ' backup restored successfully.'
                );
                $completedPhases++;
            }

            File::deleteDirectory($tmpDir);
            $manager->setStatus('restore-drive', 'completed', 100, 'Selected Google Drive backup(s) restored successfully.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            File::deleteDirectory($tmpDir);
            $manager->setStatus('restore-drive', 'failed', 100, $e->getMessage());
            return self::FAILURE;
        }
    }
}
