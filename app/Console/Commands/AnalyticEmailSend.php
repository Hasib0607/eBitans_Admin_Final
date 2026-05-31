<?php

namespace App\Console\Commands;

use App\Jobs\SendStoreAnalyticsEmailJob;
use App\Models\Store;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AnalyticEmailSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:analyticEmailSend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Store Analytic Email';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Analytic email next plan if have next plan
        if (env('APP_ENV') == "production") {
            $this->analyticEmailSend();
        }

        return Command::SUCCESS;
    }


    public function analyticEmailSend()
    {
        $oneDay = Carbon::now()->subDays(1);
        $fifteenDays = Carbon::now()->subDays(15);

        $storeQuery = Store::select('id')
            ->whereNotNull('plan_id')
            ->whereNotIn('plan_id', [6, 9]);

        $expiredStores = collect();
        $nonExpiredStores = collect();

        // Expired
        (clone $storeQuery)
            ->where(function ($query) use ($oneDay) {
                $query->where('analytic_email', '<=', $oneDay)
                    ->orWhereNull('analytic_email');
            })
            ->where("expiry_date", "<=", Carbon::now())
            ->chunk(100, function ($stores) use (&$expiredStores) {
                foreach ($stores as $store) {
                    $store->notification_type = 'expired';
                    $expiredStores->push($store);
                }
            });

        // Non-expired
        (clone $storeQuery)
            ->where(function ($query) use ($fifteenDays) {
                $query->where('analytic_email', '<=', $fifteenDays)
                    ->orWhereNull('analytic_email');
            })
            ->where("expiry_date", ">", Carbon::now())
            ->chunk(100, function ($stores) use (&$nonExpiredStores) {
                foreach ($stores as $store) {
                    $store->notification_type = 'non_expired';
                    $nonExpiredStores->push($store);
                }
            });

        // Merge the stores
        $mergedStores = $expiredStores->merge($nonExpiredStores)->unique('id');

        foreach ($mergedStores as $store) {
            SendStoreAnalyticsEmailJob::dispatch($store->id, $store->notification_type);
        }

    }


}
