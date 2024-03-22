<?php

namespace App\Extensions\Gateways\RazorPay;

use App\Classes\Extensions\Gateway;
use App\Helpers\ExtensionHelper;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class RazorPay extends Gateway
{
    /**
    * Get the extension metadata
    * 
    * @return array
    */
    public function getMetadata()
    {
        return [
            'display_name' => 'RazorPay',
            'version' => '1.0.0',
            'author' => 'Sarthak',
            'website' => 'https://stellarhost.tech',
        ];
    }

    /**
     * Get all the configuration for the extension
     * 
     * @return array
     */
    public function getConfig()
    {
        return [
            [
                'name' => 'razorpay_key_id',
                'friendlyName' => 'RazorPay Key ID',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'razorpay_secret_key',
                'friendlyName' => 'RazorPay Secret Key',
                'type' => 'text',
                'required' => true,
            ],
            [
                'name' => 'test_mode',
                'friendlyName' => 'Test Mode',
                'type' => 'boolean',
                'required' => false,
            ],
            [
                'name' => 'test_key_id',
                'friendlyName' => 'Test Key ID',
                'type' => 'text',
                'required' => false,
            ],
            [
                'name' => 'test_secret_key',
                'friendlyName' => 'Test Secret Key',
                'type' => 'text',
                'required' => false,
            ],
        ];
    }
    
    /**
     * Get the URL to redirect to
     * 
     * @param int $total
     * @param array $products
     * @param int $invoiceId
     * @return string
     */
    public function pay($total, $products, $invoiceId)
    {
        $key_id = ExtensionHelper::getConfig('RazorPay', 'test_mode') ? ExtensionHelper::getConfig('RazorPay', 'test_key_id') : ExtensionHelper::getConfig('RazorPay', 'razorpay_key_id');
        $secretKey = ExtensionHelper::getConfig('RazorPay', 'test_mode') ? ExtensionHelper::getConfig('RazorPay', 'test_secret_key') : ExtensionHelper::getConfig('RazorPay', 'razorpay_secret_key');        
        $orderId = 'order_' . $invoiceId;
        $order_amount = $total * 100;

        $url = "https://api.razorpay.com/v1/orders";
        
        $data = [
            "amount" => $order_amount,
            "currency" => "INR",
            "receipt" => $orderId
        ];
        
        try {
            $response = Http::withBasicAuth($key_id, $secretKey)
                            ->withHeaders([
                                'Content-Type' => 'application/json'
                            ])
                            ->post($url, $data);

            $response_object = $response->json();

            if (isset($response_object['id'])) {
                $id = $response_object['id'];
                return route('razorpay.payment', ['id' => $id, 'order_amount' => $order_amount, 'invoiceId' => $invoiceId, 'key_id' => $key_id]);
            } else {
                return 'Unexpected response format';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Handle RazorPay webhook request.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        $webhookSecret = ExtensionHelper::getConfig('RazorPay', 'test_mode') ? ExtensionHelper::getConfig('RazorPay', 'test_secret_key') : ExtensionHelper::getConfig('RazorPay', 'razorpay_secret_key');

        $signature = $request->header('X-Razorpay-Signature');
        $webhookBody = file_get_contents('php://input');

        $expectedSignature = hash_hmac('sha256', $webhookBody, $webhookSecret);

        if ($signature !== $expectedSignature) {
            return response('Signature verification failed', 401);
        }

        $data = json_decode($webhookBody, true);
        $orderId = $data['payload']['order']['entity']['receipt'];
        $invoiceId = $this->extractInvoiceId($orderId);

        if ($data['event'] === 'order.paid') {
            if ($invoiceId) {
                ExtensionHelper::paymentDone($invoiceId);
            }
        }
        return response('Webhook received and processed successfully');
    }

    /**
     * Extract invoice ID from order ID
     *
     * @param string $orderId
     * @return int|null
     */
    private function extractInvoiceId($orderId)
    {
        $numericPart = str_replace('order_', '', $orderId);
        return (int)$numericPart;
    }
}