<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteAnalyticsReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Action:deleteAnalyticsReports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Analytics Reports';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->deleteAnalyticsReport("toptools", "7");
        $this->deleteAnalyticsReport("activitylogs", "60");
        $this->deleteAnalyticsReport("ebt_analytics", "60");
        $this->deleteAnalyticsReport("admin_user_analytics", "60");

        return Command::SUCCESS;
    }


    /**
     * Delete previous analytic report
     * @param $table
     * @param $days
     * @param $column
     * @return void
     */
    public function deleteAnalyticsReport($table, $days, $column = "created_at")
    {
        try {
            DB::table($table)->where($column, '<=', Carbon::today()->subDays($days))->delete();
        } catch (\Exception $e) {
            Log::error("Failed to delete from {$table}: " . $e->getMessage());
        }
    }


}
