<?php

namespace App\Jobs;

use App\Mail\StoreAnalyticMail;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendStoreAnalyticsEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;
    protected $storeId;
    protected $notificationType;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $storeId, string $notificationType)
    {
        $this->storeId = $storeId;
        $this->notificationType = $notificationType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $store = Store::with(['user', 'headerSetting'])->find($this->storeId);
        if (!$store) return;

        $storeEmail = $store->user->email ?? null;
        $headerEmail = $store->headerSetting->email ?? null;
        $email = $storeEmail ?: $headerEmail;

        if (!is_null($email)) {
            $days = $store->notification_type === 'expired' ? 1 : 15;
            $result = getAnalyticData($store->id, $days);

            $totalVisitor = $result['totalVisitor'] ?? 0;
            $visitorChange = $result['visitorChange'] ?? NULL;

            if ($totalVisitor > 0) {
                $data = [
                    'storeName' => $store->name ?? 'Unknown Store',
                    'days' => $days,
                    'visitors' => $totalVisitor,
                    'increase' => $visitorChange,
                ];

                try {
                    Mail::to($email)->send(new StoreAnalyticMail($data));
                    $store->analytic_email = Carbon::now();
                    $store->update();

                    // Update analytic_email timestamp only if the email was sent successfully
                    //Log::info("Sent analytic email to {$email} ({$store->notification_type}) for store ID {$store->id}");
                } catch (\Exception $e) {
                    //Log::error("Failed to send email to {$email} for store ID {$store->id}: " . $e->getMessage());
                }

                // Log which store received which type of email
                //Log::info("Sent analytic email to {$email} ({$store->notification_type}) for store: {$store->name}");
            } else {
//                Log::info("No visitors for store ID {$store->id}, skipping email.");
            }

            unset($store->notification_type);

        }
    }
}
