<?php

namespace App\Console\Commands;

use App\Services\BackupManager;
use Illuminate\Console\Command;

class RunNightlyLocalBackup extends Command
{
    protected $signature = 'backup:nightly-local';
    protected $description = 'Take nightly local backups and prepare them for Google Drive upload';

    public function handle(BackupManager $manager): int
    {
        $types = ['database', 'code', 'public'];
        $currentStage = 'backup';

        try {
            $manager->setStatus('nightly-drive-backup', 'running', 5, 'Nightly local backup started...');
            $manager->setNightlyStatus('running', 5, 'Nightly local backup started...');
            $manager->setNightlyStageStatus('backup', 'running', 'Nightly local backup started...');
            $manager->setNightlyStageStatus('upload', 'pending', 'Google Drive upload will start 5 minutes after backup completion.');

            foreach ($types as $index => $type) {
                $progress = 10 + (int) (($index / max(count($types), 1)) * 70);
                $manager->setStatus('nightly-drive-backup', 'running', $progress, 'Creating ' . ucfirst($type) . ' backup...');
                $manager->setNightlyStatus('running', $progress, 'Creating ' . ucfirst($type) . ' backup...');

                if ($type === 'database') {
                    $manager->backupDatabase();
                } elseif ($type === 'code') {
                    $manager->backupCode();
                } else {
                    $manager->backupPublic();
                }
            }

            $manager->setNightlyStageStatus('backup', 'completed', 'Nightly local backup completed successfully.');
            $manager->setStatus('nightly-drive-backup', 'running', 75, 'Nightly local backup completed. Waiting 5 minutes before Google Drive upload...');
            $manager->setNightlyStatus('running', 75, 'Nightly local backup completed. Waiting 5 minutes before Google Drive upload...');

            sleep(300);

            $currentStage = 'upload';
            $manager->setStatus('nightly-drive-backup', 'running', 78, 'Google Drive upload started for tonight\'s backups...');
            $manager->setNightlyStatus('running', 78, 'Google Drive upload started for tonight\'s backups...');
            $manager->setNightlyStageStatus('upload', 'running', 'Google Drive upload started for tonight\'s backups...');

            $manager->uploadLatestSelectedToDrive($types, function (int $progress, string $message) use ($manager) {
                $adjustedProgress = min(95, 78 + (int) round($progress * 0.17));
                $manager->setStatus('nightly-drive-backup', 'running', $adjustedProgress, $message);
                $manager->setNightlyStatus('running', $adjustedProgress, $message);
            });

            $manager->setStatus('nightly-drive-backup', 'running', 96, 'Cleaning backups older than 3 days...');
            $manager->setNightlyStatus('running', 96, 'Cleaning backups older than 3 days...');
            $manager->cleanupSelected($types, 3);

            $manager->setNightlyStageStatus('upload', 'completed', 'Google Drive upload completed successfully.');
            $message = 'Nightly backup and Google Drive upload completed successfully.';
            $manager->setStatus('nightly-drive-backup', 'completed', 100, $message);
            $manager->setNightlyStatus('completed', 100, $message);

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $manager->setNightlyStageStatus($currentStage, 'failed', $e->getMessage());
            $manager->setStatus('nightly-drive-backup', 'failed', 100, $e->getMessage());
            $manager->setNightlyStatus('failed', 100, $e->getMessage());

            return self::FAILURE;
        }
    }
}
