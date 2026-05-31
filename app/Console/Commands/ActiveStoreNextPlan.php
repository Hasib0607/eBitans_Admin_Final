<?php

namespace App\Console\Commands;

use App\Models\Store;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ActiveStoreNextPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'action:activeStoreNextPlan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Store Next Plan And Expired Date';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Active next plan if have next plan
        $this->activeStoreNextPlan();

        return Command::SUCCESS;
    }

    public function activeStoreNextPlan()
    {
        Store::whereNotNull('plan_id')->whereNotNull('upcoming_plan_id')->where("expiry_date", "<=", Carbon::now())->chunk(100, function ($stores) {
            foreach ($stores as $store) {
                $store->renew_date = $store->upcoming_plan_purchase_date;
                $store->expiry_date = $store->upcoming_plan_expiry_date;
                $store->month = $store->upcoming_plan_month;
                $store->upcoming_plan_id = NULL;
                $store->upcoming_plan_purchase_date = NULL;
                $store->upcoming_plan_expiry_date = NULL;
                $store->upcoming_plan_month = NULL;
                $store->save();
            }
        });

    }


}
