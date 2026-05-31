<?php

namespace App\Console\Commands;

use App\Models\Pse\PseVisitorCounter;
use App\Mail\SendPseVisitorReport;
use Illuminate\Console\Command;
use App\Models\Store;
use Mail;

class PseVisitorReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pseVisitor:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly PSE products visitor report';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $getAllPseProductStore = Store::select('stores.id as storeId', 'stores.name', 'users.email as userEmail', 'users.name as userName')
            ->leftJoin('users', function ($join) {
                $join->on('stores.user_id', '=', 'users.id');
            })
            ->whereNotNull('users.email')
            ->where('users.email', '!=', '')
            ->get();

        foreach ($getAllPseProductStore as $store) {
            $visitors = PseVisitorCounter::select(
                'pse_visitor_counters.id',
                'pse_visitor_counters.appr_id',
                'pse_visitor_counters.product_id',
                'products.images AS productImage',
                'products.name',
                'stores.name AS store_name',
                'stores.url'
            )
                ->leftJoin('products', 'products.id', '=', 'pse_visitor_counters.product_id')
                ->leftJoin('stores', 'stores.id', '=', 'pse_visitor_counters.store_id')
                ->selectRaw('COUNT(pse_visitor_counters.product_id) AS totalVisitor')
                ->where('pse_visitor_counters.store_id', $store->storeId)
                ->whereDate('pse_visitor_counters.created_at', '>=', now()->subDays(7))
                ->groupBy('pse_visitor_counters.product_id')
                ->orderBy('totalVisitor', 'DESC')
                ->limit(10)
                ->get();
            if ($visitors->isNotEmpty()) {
                Mail::to($store->userEmail)->send(new SendPseVisitorReport($store->userName, $visitors));
            }
        }
    }
}
