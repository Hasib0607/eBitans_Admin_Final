<?php

namespace App\Console\Commands;

use App\Models\Store;
use Illuminate\Console\Command;
use Carbon\Carbon;

class PaymentNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:sendPaymentNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payment notification send to user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->makePaymentNotify(); // make change notify status
        return Command::SUCCESS;
    }


    public function makePaymentNotify()
    {
        // 7 Days client
        $end_date = Carbon::now()->addDays(7)->format('Y-m-d');
        Store::whereDate('expiry_date', $end_date)
            ->whereNotIn('plan_id', [6, 9])
            ->whereNotIn('pay_noti', [7])
            ->whereNull("upcoming_plan_id")
            ->chunk(100, function ($stores) {
                foreach ($stores as $item) {
                    packageExpiryNotification($item, 7);
                    $item->pay_noti = 7;
                    $item->sms_status = (int)$item->sms_status + 1;
                    $item->save();
                }
            });


        // 3 Days client
        $end_date = Carbon::now()->addDays(3)->format('Y-m-d');
        Store::whereDate('expiry_date', $end_date)
            ->whereNotIn('plan_id', [6, 9])
            ->whereNotIn('pay_noti', [3])
            ->whereNull("upcoming_plan_id")
            ->chunk(100, function ($stores) {
                foreach ($stores as $item) {
                    packageExpiryNotification($item, 3);
                    $item->pay_noti = 3;
                    $item->sms_status = (int)$item->sms_status + 1;
                    $item->save();
                }
            });


        // 1 Days client
        $end_date = Carbon::now()->addDays(1)->format('Y-m-d');
        Store::whereDate('expiry_date', $end_date)
            ->whereNotIn('plan_id', [6, 9])
            ->whereNotIn('pay_noti', [0])
            ->whereNull("upcoming_plan_id")
            ->chunk(100, function ($stores) {
                foreach ($stores as $item) {
                    packageExpiryNotification($item, 1);
                    $item->pay_noti = 0;
                    $item->sms_status = (int)$item->sms_status + 1;
                    $item->save();
                }
            });


    }


}
