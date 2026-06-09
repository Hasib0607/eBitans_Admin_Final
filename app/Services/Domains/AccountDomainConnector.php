<?php

namespace App\Services\Domains;

use App\Models\Domain;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Throwable;

class AccountDomainConnector
{
    public function connect(Domain $domain, array $options = []): array
    {
        $token = (string) config('services.account_domain.token');

        if ($token === '') {
            $this->markFailed($domain, 'Account domain API token is not configured.');

            return [
                'status' => false,
                'code' => 500,
                'message' => 'Account domain API token is not configured.',
                'response' => null,
            ];
        }

        $payload = [
            'name' => cleanDomain($domain->name),
            'forceSsl' => (bool) ($options['forceSsl'] ?? config('services.account_domain.force_ssl', true)),
            'hostingMode' => $options['hostingMode'] ?? config('services.account_domain.hosting_mode', 'PUBLIC_HTML'),
            'documentRoot' => $options['documentRoot'] ?? config('services.account_domain.document_root', 'public_html'),
        ];

        try {
            $response = $this->client($token)
                ->post($this->endpoint('/api/v1/account/domains'), $payload);
        } catch (Throwable $exception) {
            $message = 'Account domain API request failed.';
            $this->markFailed($domain, $message);

            return [
                'status' => false,
                'code' => 500,
                'message' => $message,
                'response' => null,
            ];
        }

        if ($response->successful()) {
            $domain->status = 'Active';
            $domain->connect_status = 4;
            $domain->remark = 'Successfully add domain using account domain API';
            $domain->save();

            $this->activateStoreDomain($domain);

            return [
                'status' => true,
                'code' => $response->status(),
                'message' => 'Domain connect successfully',
                'response' => $response->json(),
            ];
        }

        $message = $this->failureMessage($response->status(), $response->json());
        $this->markFailed($domain, $message);

        return [
            'status' => false,
            'code' => $response->status(),
            'message' => $message,
            'response' => $response->json(),
        ];
    }

    public function connectBulk(array $domains, array $options = []): array
    {
        $token = (string) config('services.account_domain.token');

        if ($token === '') {
            return [
                'status' => false,
                'code' => 500,
                'message' => 'Account domain API token is not configured.',
                'response' => null,
            ];
        }

        $payload = [
            'domains' => array_values(array_map('cleanDomain', $domains)),
            'forceSsl' => (bool) ($options['forceSsl'] ?? config('services.account_domain.force_ssl', true)),
            'skipExisting' => (bool) ($options['skipExisting'] ?? true),
            'publish' => (bool) ($options['publish'] ?? true),
            'issueSsl' => (bool) ($options['issueSsl'] ?? false),
        ];

        try {
            $response = $this->client($token)
                ->post($this->endpoint('/api/v1/account/domains/bulk'), $payload);
        } catch (Throwable $exception) {
            return [
                'status' => false,
                'code' => 500,
                'message' => 'Account domain API request failed.',
                'response' => null,
            ];
        }

        return [
            'status' => $response->successful(),
            'code' => $response->status(),
            'message' => $this->failureMessage($response->status(), $response->json()),
            'response' => $response->json(),
        ];
    }

    private function endpoint(string $path): string
    {
        return rtrim((string) config('services.account_domain.base_url'), '/') . $path;
    }

    private function client(string $token)
    {
        $client = Http::acceptJson()
            ->asJson()
            ->withToken($token)
            ->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->timeout((int) config('services.account_domain.timeout', 30));

        $csrfToken = (string) config('services.account_domain.csrf_token', '');
        if ($csrfToken !== '') {
            $client = $client->withHeaders([
                'X-CSRF-TOKEN' => $csrfToken,
                'X-XSRF-TOKEN' => $csrfToken,
            ]);
        }

        $csrfCookie = (string) config('services.account_domain.csrf_cookie', '');
        if ($csrfCookie !== '') {
            $client = $client->withHeaders([
                'Cookie' => $csrfCookie,
            ]);
        }

        return $client;
    }

    private function failureMessage(int $status, $body): string
    {
        $message = is_array($body) ? ($body['message'] ?? $body['error'] ?? null) : null;

        if (!empty($message)) {
            if (stripos((string) $message, 'csrf') !== false) {
                return 'Domain server API CSRF/auth setup is not configured correctly.';
            }

            return (string) $message;
        }

        if ($status === 409) {
            return 'Domain already exists.';
        }

        if ($status === 403) {
            return 'Domain package limit reached.';
        }

        return 'Domain connect failed. Please try again.';
    }

    private function markFailed(Domain $domain, string $message): void
    {
        $domain->remark = $message;
        $domain->save();
    }

    private function activateStoreDomain(Domain $domain): void
    {
        $store = Store::find($domain->store_id);

        if ($store && (int) $store->plan_id !== 6 && (int) $store->plan_id !== 9) {
            $store->url = $domain->name;
            $store->save();
        }

        $user = User::find($domain->uid);
        if ($user) {
            $user->domain = $domain->name;
            $user->save();
        }

        $domainParts = explode('.', $domain->name);
        if (count($domainParts) === 2) {
            if ($user) {
                $user->active_cpanel = 'active';
                $user->save();
            }

            if ($store) {
                $store->webmail_status = 'active';
                $store->save();
            }
        }
    }
}
