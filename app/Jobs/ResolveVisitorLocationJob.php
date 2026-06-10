<?php

namespace App\Jobs;

use App\Models\AdminVisitor;
use App\Services\Visitors\VisitorLocationResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stevebauman\Location\Facades\Location;

class ResolveVisitorLocationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 3;

    public function __construct(
        public string $ip,
        public ?int $adminVisitorId = null
    ) {
    }

    public function handle(VisitorLocationResolver $resolver): void
    {
        $payload = $resolver->payloadFromPosition(Location::get($this->ip));

        if (! $payload) {
            return;
        }

        $resolver->cachePayload($this->ip, $payload);

        if ($this->adminVisitorId) {
            AdminVisitor::where('id', $this->adminVisitorId)
                ->update($resolver->adminVisitorPayload($payload));
        }
    }
}
