<?php

namespace App\Console\Commands;

use App\Models\Domain;
use App\Models\Store;
use Illuminate\Console\Command;
use App\Logic\Providers\cPanelApi;
use Carbon\Carbon;

class DeleteStoreDomainAndFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'action:deleteStoreDomainAndFile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Store Domain and File';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Delete store domain and file
        $this->deleteStoreDomainAndFile();

        return Command::SUCCESS;
    }


    public function deleteStoreDomainAndFile()
    {
        Store::where('expiry_date', '<=', Carbon::today()->subDays(90))->where(function ($q) {
            $q->where("isDomainDelete", 0)->orWhere("isCFileDelete", 0);
        })->chunk(100, function ($stores) {
            $api = new cPanelApi("ebitans.com", "ebitans", env("HOST_POINT"));

            foreach ($stores as $store) {
                $st = Store::where('id', $store->id)->first();
                $domain = cleanDomain($st->url);

                if (strpos($domain, "ebitans") === false) {
                    if (isset($st->isDomainDelete) && $st->isDomainDelete == 0) {
                        // Delete domain function call
                        $domainList = Domain::where('store_id', $st->id)->where("name", $domain)->first();
                        if (isset($domainList)) {
                            $domainList->deleteRequest = 1;
                            $domainList->save();
                        }
                    }

                    if (isset($st->isCFileDelete) && $st->isCFileDelete == 0) {
                        $result = $api->deleteDomainFolder($domain);
                        if ($result) {
                            $st->isCFileDelete = 1;
                            $st->save();
                        }
                    }
                }
            }
        });
    }

}
