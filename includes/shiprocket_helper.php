<?php
require_once __DIR__ . '/config.php';

class Shiprocket {

    private static $apiBaseUrl = SHIPROCKET_BASE_URL;

    /* ===============================
       AUTH TOKEN
    =============================== */
    private static function getAuthToken() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$apiBaseUrl . '/auth/login');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'email' => SHIPROCKET_API_EMAIL,
            'password' => SHIPROCKET_API_PASSWORD
        ]));

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            error_log("Shiprocket Auth Error: " . curl_error($ch));
            curl_close($ch);
            return null;
        }
        
        curl_close($ch);

        $result = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Shiprocket Auth JSON Error: " . $response);
            return null;
        }

        return $result['token'] ?? null;
    }

    /* ===============================
       CREATE SHIPMENT
    =============================== */
    public static function createOrder($orderData) {
        $token = self::getAuthToken();

        if (!$token) {
            return [
                'success' => false,
                'message' => 'Authentication Failed - Unable to get token'
            ];
        }

        // Clean phone number - last 10 digits only
        $phone = preg_replace('/[^0-9]/', '', $orderData['customer_phone'] ?? '');
        $phone = substr($phone, -10);
        
        if (strlen($phone) !== 10) {
            return [
                'success' => false,
                'message' => 'Invalid phone number. Must be 10 digits.'
            ];
        }

        // Safety fallbacks
        $email = !empty($orderData['customer_email']) ? $orderData['customer_email'] : 'support@livvra.in';
        $state = !empty($orderData['state']) ? $orderData['state'] : 'Punjab';
        $city = !empty($orderData['city']) ? $orderData['city'] : 'Bathinda';
        $pincode = !empty($orderData['pincode']) ? $orderData['pincode'] : '151001';
        $address = !empty($orderData['shipping_address']) ? $orderData['shipping_address'] : 'Bathinda';
        $customerName = !empty($orderData['customer_name']) ? $orderData['customer_name'] : 'Customer';

        // Build order items array
        $orderItems = [];
        if (!empty($orderData['items']) && is_array($orderData['items'])) {
            foreach ($orderData['items'] as $item) {
                $orderItems[] = [
                    "name" => $item['product_name'] ?? 'Product',
                    "sku" => $item['sku'] ?? 'SKU001',
                    "units" => (int)($item['quantity'] ?? 1),
                    "selling_price" => (float)($item['price'] ?? $orderData['total_amount']),
                    "hsn" => $item['hsn'] ?? 1234
                ];
            }
        } else {
            // Fallback single item
            $orderItems[] = [
                "name" => $orderData['product_name'] ?? 'Product',
                "sku" => $orderData['sku'] ?? 'SKU001',
                "units" => (int)($orderData['quantity'] ?? 1),
                "selling_price" => (float)$orderData['total_amount'],
                "hsn" => 1234
            ];
        }

       $payload = [
    "order_id" => $orderData['order_number'] . '-' . time(),
    "order_date" => date('Y-m-d H:i:s'),
    "pickup_location" => SHIPROCKET_PICKUP_LOCATION,

    // Billing
    "billing_customer_name" => $customerName,
    "billing_last_name" => "",
    "billing_address" => $address,
    "billing_city" => $city,
    "billing_pincode" => (string)$pincode,
    "billing_state" => $state,
    "billing_country" => "India",
    "billing_email" => $email,
    "billing_phone" => $phone,

    // Shipping (IMPORTANT)
    "shipping_is_billing" => false,

    "shipping_customer_name" => $customerName,
    "shipping_last_name" => "",
    "shipping_address" => $address,
    "shipping_city" => $city,
    "shipping_pincode" => (string)$pincode,
    "shipping_state" => $state,
    "shipping_country" => "India",
    "shipping_email" => $email,
    "shipping_phone" => $phone,

    "order_items" => $orderItems,

    "payment_method" => strtolower($orderData['payment_method'] ?? '') === 'cod' ? "COD" : "Prepaid",
    "sub_total" => (float)$orderData['total_amount'],

    "length" => (float)($orderData['length'] ?? 10),
    "breadth" => (float)($orderData['breadth'] ?? 10),
    "height" => (float)($orderData['height'] ?? 10),
    "weight" => (float)($orderData['weight'] ?? 0.5)
];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, SHIPROCKET_BASE_URL . '/orders/create/adhoc');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return [
                'success' => false,
                'message' => 'Curl Error: ' . $error
            ];
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($httpCode !== 200 && $httpCode !== 201) {
            return [
                'success' => false,
                'message' => 'API Error (' . $httpCode . '): ' . ($result['message'] ?? $response)
            ];
        }

        if (!empty($result['order_id'])) {
            return [
                'success' => true,
                'shiprocket_order_id' => $result['order_id'],
                'shipment_id' => $result['shipment_id'] ?? null,
                'awb_code' => $result['awb_code'] ?? null,
                'courier_name' => $result['courier_name'] ?? null,
                'label_url' => $result['label_url'] ?? null,
                'manifest_url' => $result['manifest_url'] ?? null,
                'response' => $result
            ];
        }

        return [
            'success' => false,
            'message' => 'Unknown error: ' . json_encode($result)
        ];
    }

  /* ===============================
   GENERATE AWB (Assign Courier)
   ✅ FIXED: Accepts shipment_id (not order_id)
=============================== */
public static function generateAWB($shipmentId, $courierId = null) {
    $token = self::getAuthToken();
    
    if (!$token) {
        return ['success' => false, 'message' => 'Auth failed'];
    }

    $payload = [
        'shipment_id' => (int)$shipmentId
    ];
    
    if ($courierId) {
        $payload['courier_id'] = (int)$courierId;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, SHIPROCKET_BASE_URL . '/courier/assign/awb');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['success' => false, 'message' => 'Curl Error: ' . $error];
    }
    
    curl_close($ch);

    $result = json_decode($response, true);

    // Check if AWB assigned successfully
    if (!empty($result['awb_assign_status']) || !empty($result['response']['data']['awb_code'])) {
        return [
            'success' => true,
            'awb_code' => $result['response']['data']['awb_code'] ?? null,
            'courier_name' => $result['response']['data']['courier_name'] ?? null,
            'label_url' => $result['response']['data']['label_url'] ?? null,
            'response' => $result
        ];
    }

    return [
        'success' => false,
        'message' => $result['message'] ?? $result['response']['data']['message'] ?? json_encode($result)
    ];
}
    /* ===============================
       TRACK SHIPMENT
    =============================== */
    public static function trackShipment($awbCode) {
        $token = self::getAuthToken();
        
        if (!$token) {
            return ['success' => false, 'message' => 'Auth failed'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, SHIPROCKET_BASE_URL . '/courier/track/awb/' . $awbCode);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (!empty($result['tracking_data'])) {
            return [
                'success' => true,
                'tracking_data' => $result['tracking_data']
            ];
        }

        return [
            'success' => false,
            'message' => $result['message'] ?? 'Tracking failed'
        ];
    }

    /* ===============================
       CANCEL ORDER
    =============================== */
    public static function cancelOrder($shiprocketOrderIds = []) {
        $token = self::getAuthToken();
        
        if (!$token) {
            return ['success' => false, 'message' => 'Auth failed'];
        }

        $ids = is_array($shiprocketOrderIds) ? $shiprocketOrderIds : [$shiprocketOrderIds];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, SHIPROCKET_BASE_URL . '/orders/cancel');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['ids' => $ids]));

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        return [
            'success' => !empty($result['status']),
            'message' => $result['message'] ?? 'Cancel processed',
            'data' => $result
        ];
    }

    /* ===============================
       DOWNLOAD LABEL
    =============================== */
    public static function downloadLabel($shipmentIds = []) {
        $token = self::getAuthToken();
        
        if (!$token) {
            return ['success' => false, 'message' => 'Auth failed'];
        }

        $ids = is_array($shipmentIds) ? $shipmentIds : [$shipmentIds];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, SHIPROCKET_BASE_URL . '/courier/generate/label');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['shipment_id' => $ids]));

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (!empty($result['label_url'])) {
            return [
                'success' => true,
                'label_url' => $result['label_url']
            ];
        }

        return [
            'success' => false,
            'message' => $result['message'] ?? 'Label generation failed'
        ];
    }

    /* ===============================
       GET ALL COURIERS
    =============================== */
    public static function getCouriers() {
        $token = self::getAuthToken();
        
        if (!$token) {
            return ['success' => false, 'message' => 'Auth failed'];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, SHIPROCKET_BASE_URL . '/courier/list');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (!empty($result['data'])) {
            return [
                'success' => true,
                'couriers' => $result['data']
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to fetch couriers'
        ];
    }
}
?>