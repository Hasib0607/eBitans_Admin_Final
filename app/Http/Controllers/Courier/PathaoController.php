<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\CourierDelivery;
use App\Models\Order;
use Codeboxr\PathaoCourier\Facade\PathaoCourier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class PathaoController extends Controller
{
    /**
     * Create a new PathaoController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['handleWebhook']);
    }

    /**
     * Get Pathao stores.
     *
     * @return array
     */
    public static function getStores()
    {
        try {
            if (env('PATHAO_SANDBOX')) {
                $data = PathaoCourier::store()->list();
                return ["status" => true, "data" => $data];
            }

            $self = new self();

            $baseUrl = "https://api-hermes.pathao.com";
            $accessToken = $self->getAccessToken($baseUrl);

            if (isset($accessToken['error'])) {
                return ["status" => false, "message" => $accessToken['message']];
            }

            $getStoreUrl = $baseUrl . "/aladdin/api/v1/stores";
            $headers = [
                'Authorization: Bearer ' . $accessToken['access_token'],
                'Content-Type: application/json',
                'Accept: application/json',
            ];

            $storeResponse = $self->sendCurlRequest($getStoreUrl, 'GET', $headers);

            if (isset($storeResponse['type']) && $storeResponse['type'] === "success" && $storeResponse['code'] == 200) {
                $data = (object)$storeResponse['data'] ?? [];
                return ["status" => true, "data" => $data];
            }

            return ["status" => false, "message" => "API Error"];
        } catch (\Exception $e) {
            return ["status" => false, "message" => "API Error"];
        }
    }

    /**
     * Get Access Token from Pathao API.
     *
     * @param string $baseUrl
     * @return array
     */
    private function getAccessToken($baseUrl)
    {
        $clientId = Config::get('pathao.client_id');
        $clientSecret = Config::get('pathao.client_secret');
        $username = Config::get('pathao.username');
        $password = Config::get('pathao.password');

        $postData = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'username' => $username,
            'password' => $password,
            'grant_type' => "password",
        ];

        $issueTokenUrl = $baseUrl . "/aladdin/api/v1/issue-token";
        return $this->sendCurlRequest($issueTokenUrl, 'POST', [
            'Accept: application/json',
            'Content-Type: application/json',
        ], $postData);
    }

    /**
     * Handle cURL requests.
     *
     * @param string $url
     * @param string $method
     * @param array|null $headers
     * @param array|null $data
     * @return array
     */
    private function sendCurlRequest($url, $method = 'GET', $headers = [], $data = null)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'error' => true,
                'message' => $error,
            ];
        }

        return json_decode($response, true);
    }

    /**
     * Create an order with Pathao.
     *
     * @param $request
     * @return array
     */
    public static function createOrder($bodyData = [])
    {
        try {
            if (env('PATHAO_SANDBOX')) {
                $orderResponse = PathaoCourier::order()->create($bodyData);
                return ["status" => true, "data" => $orderResponse];
            }

            $self = new self();

            $baseUrl = "https://api-hermes.pathao.com";
            $accessToken = $self->getAccessToken($baseUrl);

            if (isset($accessToken['error'])) {
                return ["status" => false, "message" => $accessToken['message']];
            }

            $createOrderUrl = $baseUrl . "/aladdin/api/v1/orders";
            $headers = [
                'Authorization: Bearer ' . $accessToken['access_token'],
                'Content-Type: application/json',
                'Accept: application/json',
            ];

            $orderResponse = $self->sendCurlRequest($createOrderUrl, 'POST', $headers, $bodyData);

            if (isset($orderResponse['type']) && $orderResponse['type'] === "success" && $orderResponse['code'] == 200) {
                return ["status" => true, "data" => $orderResponse];
            }

            return ["status" => false, "message" => "API Error"];
        } catch (\Exception $e) {
            return ["status" => false, "message" => "API Error"];
        }
    }


    /**
     * Handle incoming webhook from Pathao.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        try {
//            Log::info('Pathao Webhook Received:', $request->all());

            // Process the webhook event
            $eventType = $request->input('event'); // Example: 'order_status_update'
            $data = $request->all(); // The actual event data
            $integrationSecret = "f3992ecc-59da-4cbe-a049-a13da2018d51";

            // Verify the request
            if (!$this->isValidSignature($request)) {
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            // Webhook Integration Test Response
            if ($eventType == 'webhook_integration') {
                return response()->json(['message' => 'Webhook received'], 202)
                    ->header('X-Pathao-Merchant-Webhook-Integration-Secret', $integrationSecret);
            }

            switch ($eventType) {
                case 'order.created':
                    $status = "Created";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.updated':
                    $status = "Updated";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.pickup-requested':
                    $status = "Pickup Requested";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.assigned-for-pickup':
                    $status = "Assigned for Pickup";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.picked':
                    $status = "Picked";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    $this->handleOrderStatusUpdate($data, $status);
                    break;
                case 'order.pickup-failed':
                    $status = "Pickup Failed";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.pickup-cancelled':
                    $status = "Pickup Cancelled";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.at-the-sorting-hub':
                    $status = "At the Sorting HUB";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.in-transit':
                    $status = "In Transit";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    $this->handleOrderStatusUpdate($data, $status);
                    break;
                case 'order.received-at-last-mile-hub':
                    $status = "Received at Last Mile HUB";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.assigned-for-delivery':
                    $status = "Assigned for Delivery";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    $this->handleOrderStatusUpdate($data, $status);
                    break;
                case 'order.delivered':
                    $status = "Delivered";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    $this->handleOrderStatusUpdate($data, $status);
                    break;
                case 'order.partial-delivery':
                    $status = "Partial Delivery";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    $this->handleOrderStatusUpdate($data, $status);
                    break;
                case 'order.returned':
                    $status = "Return";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    $this->handleOrderStatusUpdate($data, $status);
                    break;
                case 'order.delivery-failed':
                    $status = "Delivery Failed";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.on-hold':
                    $status = "On Hold";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.paid':
                    $status = "Payment Invoice";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.paid-return':
                    $status = "Paid Return";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                case 'order.exchanged':
                    $status = "Exchanged";
                    $this->handleOrderDeliveryStatusUpdate($data, $status);
                    break;
                default:
                    Log::warning("Unhandled Pathao webhook event type: $eventType");
                    break;
            }

            // Respond to Pathao that the webhook was received successfully
            return response()->json(['message' => 'Webhook received'], 200)->header('X-Pathao-Merchant-Webhook-Integration-Secret', $integrationSecret);
        } catch (\Exception $e) {
            return serverError();
        }

    }

    /**
     * Verify the signature of the incoming request.
     *
     * @param Request $request
     * @return bool
     */
    protected function isValidSignature(Request $request)
    {
        $secretKey = env('PATHAO_WEBHOOK_SECRET');
        $dataToSign = env('PATHAO_WEBHOOK_SECRET_KEY');
        $generateSignature = hash_hmac('sha256', $dataToSign, $secretKey);
        $receivedSignature = $request->header('X-Pathao-Signature');

        if (hash_equals($generateSignature, $receivedSignature)) {
            return true;
        }

        return false; // Update this with actual verification logic
    }

    /**
     * Handle Courier Delivery status update.
     *
     * @param array $data
     * @return void
     */
    protected function handleOrderDeliveryStatusUpdate($data, $newStatus = "Unknown Status")
    {
        $consignment_id = $data['consignment_id'] ?? "";
        $status = $data['order_status'] ?? "";
        if (empty($status)) {
            $status = $newStatus;
        }

        $order = CourierDelivery::where('consignment_id', $consignment_id)->first();
        if (isset($order)) {
            $order->delivery_status = $status;
            $order->save();
        }
    }

    /**
     * Handle order status update events.
     *
     * @param array $data
     * @return void
     */
    protected function handleOrderStatusUpdate($data, $newStatus = "Unknown Status")
    {
        $orderId = $data['merchant_order_id'] ?? "";
        $status = $data['order_status'] ?? "";
        if (empty($status)) {
            $status = $newStatus;
        }

        $order = Order::where('reference_no', $orderId)->first();
        if (isset($order)) {
            $order->status = $status;
            $order->save();
        }
    }

    /**
     * Create pathao Signature
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function craeteSignature()
    {
        $secretKey = env('PATHAO_WEBHOOK_SECRET');
        $dataToSign = env('PATHAO_WEBHOOK_SECRET_KEY');
        $signature = hash_hmac('sha256', $dataToSign, $secretKey);
        return response()->json(['status' => true, "message" => "Signature generated", "signature" => $signature]);
    }


}
