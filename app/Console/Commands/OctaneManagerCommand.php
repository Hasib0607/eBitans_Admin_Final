<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class OctaneManagerCommand extends Command
{
    protected $signature = 'octane:manage {action : start|stop}';
    protected $description = 'Start or stop Laravel Octane using Swoole';

    public function handle()
    {
        $action = $this->argument('action');

        if (!in_array($action, ['start', 'stop'])) {
            $this->error("Invalid action. Use: start or stop.");
            return Command::FAILURE;
        }

        if ($action === 'start') {
            $this->info("Starting Laravel Octane server using Swoole...");

            $process = new Process(['php', 'artisan', 'octane:start', '--server=swoole', '--host=127.0.0.1', '--port=8000']);
            $process->setTty(Process::isTtySupported());
            $process->run();
        }

        if ($action === 'stop') {
            $this->info("Stopping Laravel Octane server...");
            $process = new Process(['pkill', '-f', 'octane']);
            $process->run();

            if ($process->isSuccessful()) {
                $this->info("Octane server stopped successfully.");
            } else {
                $this->error("Failed to stop Octane server or it may not be running.");
            }
        }

        return Command::SUCCESS;
    }
}
