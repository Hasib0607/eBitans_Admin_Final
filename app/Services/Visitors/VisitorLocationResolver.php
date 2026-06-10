<?php

namespace App\Services\Visitors;

use App\Jobs\ResolveVisitorLocationJob;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Position;

class VisitorLocationResolver
{
    private const CACHE_PREFIX = 'visitor_location:';
    private const DISPATCH_PREFIX = 'visitor_location_dispatch:';
    private const CACHE_TTL_MINUTES = 43200;
    private const DISPATCH_TTL_SECONDS = 600;

    public function getCached(?string $ip): ?Position
    {
        $payload = $this->getCachedPayload($ip);

        return $payload ? $this->positionFromPayload($payload) : null;
    }

    public function getCachedPayload(?string $ip): ?array
    {
        if (! $this->isPublicIp($ip)) {
            return null;
        }

        $payload = Cache::get($this->cacheKey($ip));

        return is_array($payload) ? $payload : null;
    }

    public function warmAsync(?string $ip, ?int $adminVisitorId = null): void
    {
        if (! $this->isPublicIp($ip) || $this->getCachedPayload($ip)) {
            return;
        }

        if (config('queue.default') === 'sync') {
            return;
        }

        if (! Cache::add($this->dispatchKey($ip), true, self::DISPATCH_TTL_SECONDS)) {
            return;
        }

        try {
            $job = (new ResolveVisitorLocationJob($ip, $adminVisitorId))->onQueue(
                env('VISITOR_LOCATION_QUEUE', 'default')
            );

            app(Dispatcher::class)->dispatch($job);
        } catch (\Throwable $exception) {
            Cache::forget($this->dispatchKey($ip));

            Log::warning('Visitor location lookup queue dispatch failed', [
                'ip' => $ip,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function cachePayload(string $ip, array $payload): void
    {
        Cache::put($this->cacheKey($ip), $payload, now()->addMinutes(self::CACHE_TTL_MINUTES));
    }

    public function payloadFromPosition($position): ?array
    {
        if (! $position instanceof Position) {
            return null;
        }

        return [
            'ip' => $position->ip,
            'countryName' => $position->countryName,
            'countryCode' => $position->countryCode,
            'regionCode' => $position->regionCode,
            'regionName' => $position->regionName,
            'cityName' => $position->cityName,
            'zipCode' => $position->zipCode,
            'isoCode' => $position->isoCode,
            'postalCode' => $position->postalCode,
            'latitude' => $position->latitude,
            'longitude' => $position->longitude,
            'metroCode' => $position->metroCode,
            'areaCode' => $position->areaCode,
            'timezone' => $position->timezone,
            'driver' => $position->driver,
        ];
    }

    public function adminVisitorPayload(array $payload): array
    {
        return [
            'country_code' => $payload['countryCode'] ?? null,
            'country_name' => $payload['countryName'] ?? null,
            'state' => $payload['regionName'] ?? null,
            'city' => $payload['cityName'] ?? null,
            'zip_code' => $payload['zipCode'] ?? null,
            'latitude' => $payload['latitude'] ?? null,
            'longitude' => $payload['longitude'] ?? null,
            'time_zone' => $payload['timezone'] ?? null,
        ];
    }

    private function positionFromPayload(array $payload): Position
    {
        $position = new Position();

        foreach ($payload as $key => $value) {
            if (property_exists($position, $key)) {
                $position->{$key} = $value;
            }
        }

        return $position;
    }

    private function isPublicIp(?string $ip): bool
    {
        return is_string($ip)
            && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
    }

    private function cacheKey(string $ip): string
    {
        return self::CACHE_PREFIX . sha1($ip);
    }

    private function dispatchKey(string $ip): string
    {
        return self::DISPATCH_PREFIX . sha1($ip);
    }
}
