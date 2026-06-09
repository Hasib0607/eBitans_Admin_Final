<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SuperAdminController;
use App\Logic\Providers\cPanelApi;
use App\Models\Domain;
use App\Models\Store;
use App\Models\User;
use App\Models\ZoneRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountDomainController extends Controller
{
    public function index(): JsonResponse
    {
        $userData = getUserData();
        $storeId = $userData['store_id'] ?? null;
        $customerId = $userData['customer_id'] ?? null;

        if (empty($storeId) || empty($customerId)) {
            return sendError('Store not found for this account.', [], 404);
        }

        $domains = Domain::where('store_id', $storeId)
            ->where('customer_id', $customerId)
            ->orderBy('id', 'DESC')
            ->get([
                'id',
                'name',
                'status',
                'connect_status',
                'remark',
                'store_id',
                'customer_id',
                'created_at',
                'updated_at',
            ]);

        return sendResponse('Success', $domains);
    }

    public function store(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'domain' => ['required', 'string', 'max:255'],
        ]);

        if ($validation->fails()) {
            return sendError('Validation error', $validation->errors()->toArray(), 422);
        }

        $userData = getUserData();
        $user = Auth::user();
        $store = $userData['store'] ?? null;
        $storeId = $userData['store_id'] ?? null;
        $customerId = $userData['customer_id'] ?? null;

        if (!$user || empty($store) || empty($storeId) || empty($customerId)) {
            return sendError('Store not found for this account.', [], 404);
        }

        if ((int) ($store->plan_id ?? 0) === 6) {
            return sendError('Free plan can not add domain. Please change your plan.', [], 422);
        }

        $domainName = cleanDomain($request->domain);

        if (empty($domainName) || !filter_var($domainName, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            return sendError('Please enter a valid domain name.', [], 422);
        }

        $existingDomain = Domain::where('name', $domainName)
            ->where('status', '!=', 'Rejected')
            ->first();

        if ($existingDomain) {
            return sendError('Domain already exist. Please choose another domain.', [
                'domain_id' => $existingDomain->id,
                'store_id' => $existingDomain->store_id,
                'status' => $existingDomain->status,
            ], 409);
        }

        $domainData = new Domain();
        $domainData->name = $domainName;
        $domainData->status = 'Processing';
        $domainData->uid = $user->id;
        $domainData->store_id = $storeId;
        $domainData->customer_id = $customerId;
        $domainData->creator = $user->id;
        $domainData->editor = $user->id;
        $domainData->save();

        try {
            if (checkDomainConnectWithCpanel()) {
                $cpanelResult = $this->connectCpanel($domainData);

                if (!$cpanelResult['status']) {
                    return sendError($cpanelResult['message'], [
                        'domain' => $this->domainPayload($domainData->fresh()),
                    ], 422);
                }
            } else {
                $domainData->connect_status = 3;
                $domainData->remark = 'Cpanel domain connection disabled';
                $domainData->save();
            }

            $vercelResult = $this->connectVercel($domainData);

            if (!$vercelResult['status']) {
                return sendError($vercelResult['message'], [
                    'domain' => $this->domainPayload($domainData->fresh()),
                ], 422);
            }

            return sendResponse('Domain connect successfully', $this->domainPayload($domainData->fresh()), 201);
        } catch (\Exception $exception) {
            $domainData->remark = 'Domain connect failed';
            $domainData->save();

            return serverError();
        }
    }

    private function connectCpanel(Domain $domainData): array
    {
        $domain = $domainData->name;
        $api = new cPanelApi('ebitans.com', 'ebitans', env('HOST_POINT'));

        $domainResponse = json_decode($api->addDomain($domain));
        if ($this->hasCpanelError($domainResponse) && !$this->isCpanelAlreadyExists($domainResponse)) {
            $domainData->remark = 'Name server not updated yet';
            $domainData->save();

            return ['status' => false, 'message' => 'Your Name Server not update yet. Please update your name server.'];
        }

        $domainData->connect_status = 1;
        $domainData->remark = 'Domain add in cpanel';
        $domainData->save();

        $subdomainResponse = json_decode($api->addSubdomain($domain));
        if ($this->hasCpanelError($subdomainResponse) && !$this->isCpanelAlreadyExists($subdomainResponse)) {
            $domainData->remark = 'Failed adding sub domain in cpanel';
            $domainData->save();

            return ['status' => false, 'message' => 'Your Name Server not update yet. Please update your name server.'];
        }

        $domainData->connect_status = 2;
        $domainData->remark = 'Sub domain add in cpanel';
        $domainData->save();

        $api->updateMxToWebmail($domain);

        return $this->syncZoneRecords($api, $domainData);
    }

    private function syncZoneRecords(cPanelApi $api, Domain $domainData): array
    {
        $domain = $domainData->name;
        $aRecords = ZoneRecord::where('type', 'A')->pluck('value');
        $cnameRecords = ZoneRecord::where('type', 'CNAME')->pluck('value');

        if ($aRecords->isEmpty() || $cnameRecords->isEmpty()) {
            $domainData->remark = 'Zone type like A,CNAME Not found in the database';
            $domainData->save();

            return ['status' => false, 'message' => 'Domain connect failed. Zone records not configured.'];
        }

        foreach (['A', 'CNAME', 'AAAA'] as $type) {
            if (!$api->deleteDomainZoneEditorRecord($domain, $type)) {
                $domainData->remark = 'Domain not add in Cpanel Zone';
                $domainData->save();

                return ['status' => false, 'message' => 'Domain connect failed. Please try again.'];
            }
        }

        foreach ([
            ['record_type' => 'A', 'record_value' => $aRecords],
            ['record_type' => 'CNAME', 'record_value' => $cnameRecords],
        ] as $zone) {
            $success = false;

            foreach ($zone['record_value'] as $value) {
                $response = json_decode($api->addZoneEditor($domain, $zone['record_type'], $value));

                if (isset($response->cpanelresult->data[0]->result->status) && $response->cpanelresult->data[0]->result->status == 0) {
                    $domainData->remark = 'Domain not add in Cpanel Zone';
                    $domainData->save();

                    return ['status' => false, 'message' => 'Domain connect failed. Please try again.'];
                }

                if (!$this->hasCpanelError($response)) {
                    $success = true;
                    break;
                }
            }

            if (!$success) {
                $domainData->remark = 'Domain not add in Cpanel Zone';
                $domainData->save();

                return ['status' => false, 'message' => 'Domain connect failed. Please try again.'];
            }
        }

        $domainData->connect_status = 3;
        $domainData->remark = 'Successfully domain add in Cpanel Zone';
        $domainData->save();

        return ['status' => true, 'message' => 'Success'];
    }

    private function connectVercel(Domain $domainData): array
    {
        $superAdmin = new SuperAdminController();
        $domainName = cleanDomain($domainData->name);
        $response = json_decode($superAdmin->addDomainInVercel($domainName));
        $vercelError = null;

        if (isset($response->error)) {
            $vercelError = $response->error->message ?? 'Domain not add in Vercel';
        } else {
            $wwwResponse = json_decode($superAdmin->addDomainInVercel('www.' . $domainName));
            if (isset($wwwResponse->error)) {
                $vercelError = $wwwResponse->error->message ?? 'Domain not add in Vercel';
            }
        }

        if ($vercelError) {
            $domainData->remark = $vercelError;
            $domainData->save();

            return ['status' => false, 'message' => $vercelError];
        }

        $domainData->status = 'Active';
        $domainData->connect_status = 4;
        $domainData->remark = 'Successfully add domain in Cpanel and Vercel Both';
        $domainData->save();

        $superAdmin->activeStoreDomain($domainData);

        $domainParts = explode('.', $domainData->name);
        if (count($domainParts) === 2) {
            $user = User::find($domainData->uid);
            if ($user) {
                $user->domain = $domainData->name;
                $user->active_cpanel = 'active';
                $user->save();
            }

            $store = Store::find($domainData->store_id);
            if ($store) {
                $store->webmail_status = 'active';
                $store->save();
            }
        }

        return ['status' => true, 'message' => 'Success'];
    }

    private function hasCpanelError($response): bool
    {
        return isset($response->cpanelresult->error);
    }

    private function isCpanelAlreadyExists($response): bool
    {
        return $this->hasCpanelError($response)
            && strpos($response->cpanelresult->error, 'already exists in the userdata') !== false;
    }

    private function domainPayload(?Domain $domain): array
    {
        if (!$domain) {
            return [];
        }

        return [
            'id' => $domain->id,
            'name' => $domain->name,
            'status' => $domain->status,
            'connect_status' => $domain->connect_status,
            'remark' => $domain->remark,
            'store_id' => $domain->store_id,
            'customer_id' => $domain->customer_id,
            'created_at' => $domain->created_at,
            'updated_at' => $domain->updated_at,
        ];
    }
}
