<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BackupManager;

class RunSelectedBackup extends Command
{
    protected $signature = 'backup:selected {types*}';
    protected $description = 'Run selected backups in background';

    public function handle(BackupManager $manager): int
    {
        try {
            $types = array_values(array_intersect(
                ['database', 'code', 'public'],
                $this->argument('types')
            ));

            if (empty($types)) {
                $manager->setStatus('backup-batch', 'failed', 100, 'No valid backup type selected.');
                return self::FAILURE;
            }

            $total = count($types);
            $done = 0;

            $manager->setStatus('backup-batch', 'running', 10, 'Starting selected backup...');

            foreach ($types as $type) {
                $progress = 10 + (int)(($done / max($total, 1)) * 80);
                $manager->setStatus('backup-batch', 'running', $progress, 'Creating ' . ucfirst($type) . ' backup...');

                if ($type === 'database') {
                    $manager->backupDatabase();
                } elseif ($type === 'code') {
                    $manager->backupCode();
                } elseif ($type === 'public') {
                    $manager->backupPublic();
                }

                $done++;
            }

            $manager->setStatus('backup-batch', 'completed', 100, 'Selected backup(s) created successfully.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $manager->setStatus('backup-batch', 'failed', 100, $e->getMessage());
            return self::FAILURE;
        }
    }
}