<?php

namespace App\Console\Commands;

use App\Services\BackupManager;
use Illuminate\Console\Command;

class RunNightlyDriveBackup extends Command
{
    protected $signature = 'backup:nightly-drive';
    protected $description = 'Upload the current day nightly backups to Google Drive and keep only the last 3 days';

    public function handle(BackupManager $manager): int
    {
        $types = ['database', 'code', 'public'];

        try {
            $manager->setStatus('nightly-drive-backup', 'running', 5, 'Google Drive upload started for tonight\'s backups...');
            $manager->setNightlyStatus('running', 5, 'Google Drive upload started for tonight\'s backups...');

            $manager->uploadLatestSelectedToDrive($types, function (int $progress, string $message) use ($manager) {
                $adjustedProgress = min(95, 10 + (int) round($progress * 0.85));
                $manager->setStatus('nightly-drive-backup', 'running', $adjustedProgress, $message);
                $manager->setNightlyStatus('running', $adjustedProgress, $message);
            });

            $manager->setStatus('nightly-drive-backup', 'running', 96, 'Cleaning backups older than 3 days...');
            $manager->setNightlyStatus('running', 96, 'Cleaning backups older than 3 days...');
            $manager->cleanupSelected($types, 3);

            $manager->setStatus('nightly-drive-backup', 'completed', 100, 'Nightly backup completed successfully.');
            $manager->setNightlyStatus('completed', 100, 'Nightly backup completed successfully.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $manager->setStatus('nightly-drive-backup', 'failed', 100, $e->getMessage());
            $manager->setNightlyStatus('failed', 100, $e->getMessage());

            return self::FAILURE;
        }
    }
}
