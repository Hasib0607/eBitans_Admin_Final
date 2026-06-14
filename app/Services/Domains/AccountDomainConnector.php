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
        $token = $this->configuredToken('token');

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
            $hostingResponse = $this->client($token)
                ->post($this->endpoint('/api/v1/account/domains'), $payload);
        } catch (Throwable $exception) {
            $message = 'Hosting domain API request failed.';
            $this->markFailed($domain, $message);

            return [
                'status' => false,
                'code' => 500,
                'message' => $message,
                'response' => null,
            ];
        }

        if (!$this->isSuccessfulOrExisting($hostingResponse->status())) {
            $message = $this->failureMessage($hostingResponse->status(), $hostingResponse->json(), 'Hosting domain');
            $this->markFailed($domain, $message);

            return [
                'status' => false,
                'code' => $hostingResponse->status(),
                'message' => $message,
                'response' => [
                    'hosting' => $hostingResponse->json(),
                    'project_domain' => null,
                ],
            ];
        }

        $domain->connect_status = 3;
        $domain->remark = $hostingResponse->status() === 409
            ? 'Domain already exists on hosting server; assigning frontend project'
            : 'Successfully add domain on hosting server; assigning frontend project';
        $domain->save();

        $projectToken = $this->configuredToken('project_token');
        if ($projectToken === '') {
            $message = 'Frontend project domain API token is not configured.';
            $this->markFailed($domain, $message);

            return [
                'status' => false,
                'code' => 500,
                'message' => $message,
                'response' => [
                    'hosting' => $hostingResponse->json(),
                    'project_domain' => null,
                ],
            ];
        }

        $projectPayload = [
            'name' => cleanDomain($domain->name),
        ];

        try {
            $projectResponse = $this->client($projectToken)
                ->post($this->endpoint('/api/v1/account/project-domain/domains'), $projectPayload);
        } catch (Throwable $exception) {
            $message = 'Frontend project domain API request failed.';
            $this->markFailed($domain, $message);

            return [
                'status' => false,
                'code' => 500,
                'message' => $message,
                'response' => [
                    'hosting' => $hostingResponse->json(),
                    'project_domain' => null,
                ],
            ];
        }

        if (!$this->isSuccessfulOrExisting($projectResponse->status())) {
            $message = $this->failureMessage($projectResponse->status(), $projectResponse->json(), 'Frontend project domain');
            $this->markFailed($domain, 'Frontend project assignment failed: ' . $message);

            return [
                'status' => false,
                'code' => $projectResponse->status(),
                'message' => $message,
                'response' => [
                    'hosting' => $hostingResponse->json(),
                    'project_domain' => $projectResponse->json(),
                ],
            ];
        }

        $domain->status = 'Active';
        $domain->connect_status = 4;
        $domain->remark = $projectResponse->status() === 409
            ? 'Domain already assigned to frontend project'
            : 'Successfully add domain on hosting server and assign frontend project';
        $domain->save();

        $this->activateStoreDomain($domain);

        return [
            'status' => true,
            'code' => $projectResponse->status(),
            'message' => 'Domain connect successfully',
            'response' => [
                'hosting' => $hostingResponse->json(),
                'project_domain' => $projectResponse->json(),
            ],
        ];
    }

    public function connectBulk(array $domains, array $options = []): array
    {
        $token = $this->configuredToken('token');

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
            'message' => $this->failureMessage($response->status(), $response->json(), 'Hosting domain'),
            'response' => $response->json(),
        ];
    }

    private function endpoint(string $path): string
    {
        return rtrim(trim((string) config('services.account_domain.base_url')), '/') . $path;
    }

    private function isSuccessfulOrExisting(int $status): bool
    {
        return ($status >= 200 && $status < 300) || $status === 409;
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

        $csrfToken = trim((string) config('services.account_domain.csrf_token', ''));
        if ($csrfToken !== '') {
            $client = $client->withHeaders([
                'X-CSRF-TOKEN' => $csrfToken,
                'X-XSRF-TOKEN' => $csrfToken,
            ]);
        }

        $csrfCookie = trim((string) config('services.account_domain.csrf_cookie', ''));
        if ($csrfCookie !== '') {
            $client = $client->withHeaders([
                'Cookie' => $csrfCookie,
            ]);
        }

        return $client;
    }

    private function configuredToken(string $key): string
    {
        $token = trim((string) config("services.account_domain.{$key}", ''));

        if (stripos($token, 'Bearer ') === 0) {
            return trim(substr($token, 7));
        }

        return $token;
    }

    private function failureMessage(int $status, $body, string $phase = 'Domain server'): string
    {
        $message = is_array($body) ? ($body['message'] ?? $body['error'] ?? null) : null;
        $tokenName = stripos($phase, 'frontend') !== false
            ? 'frontend project domain API token'
            : 'account domain API token';

        if ($status === 401) {
            return "{$phase} API token is invalid or expired. Please update the {$tokenName}.";
        }

        if ($status === 419) {
            return "{$phase} API CSRF/auth setup is not configured correctly.";
        }

        if (!empty($message)) {
            if (stripos((string) $message, 'csrf') !== false) {
                return "{$phase} API CSRF/auth setup is not configured correctly.";
            }

            if (stripos((string) $message, 'unauthorized') !== false) {
                return "{$phase} API token is invalid or expired. Please update the {$tokenName}.";
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
