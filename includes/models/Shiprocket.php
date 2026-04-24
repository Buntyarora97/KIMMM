<?php

class Shiprocket {

    private static $token = null;

    /* ======================
       DEBUG LOGGER
    ====================== */
    private static function log($data) {
        file_put_contents(
            __DIR__ . '/shiprocket_debug.log',
            date('Y-m-d H:i:s') . "\n" . print_r($data, true) . "\n\n",
            FILE_APPEND
        );
    }

    /* ======================
       LOGIN & TOKEN
    ====================== */
    private static function getToken() {

        if (self::$token) {
            return self::$token;
        }

        $payload = [
            "email"    => SHIPROCKET_API_EMAIL,
            "password" => SHIPROCKET_API_PASSWORD
        ];

        $ch = curl_init(SHIPROCKET_BASE_URL . '/auth/login');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        self::log(["LOGIN_RESPONSE" => $response, "HTTP" => $httpCode]);

        $data = json_decode($response, true);

        if (!isset($data['token'])) {
            throw new Exception('Shiprocket login failed');
        }

        self::$token = $data['token'];
        return self::$token;
    }

    /* ======================
       CREATE ORDER
    ====================== */
    public static function createOrder($orderId) {

        $token = self::getToken();

        $order = Order::getById($orderId);
        $items = Order::getItems($orderId);

        if (!$order) {
            throw new Exception("Order not found");
        }

        $orderItems = [];
        foreach ($items as $item) {
            $orderItems[] = [
                "name" => $item['product_name'],
                "sku" => "SKU-" . ($item['product_id'] ?? rand(100,999)),
                "units" => (int)$item['quantity'],
                "selling_price" => (float)$item['price']
            ];
        }

        $payload = [

            "order_id" => $order['order_number'],
            "order_date" => date('Y-m-d H:i'),

            "pickup_location" => SHIPROCKET_PICKUP_LOCATION,

            /* ======================
               BILLING DETAILS
            ====================== */
            "billing_customer_name" => $order['customer_name'],
            "billing_last_name" => ".",
            "billing_address" => $order['shipping_address'],
            "billing_city" => $order['city'],
            "billing_state" => $order['state'],
            "billing_pincode" => (string)$order['pincode'],
            "billing_country" => "India",
            "billing_phone" => $order['customer_phone'],
            "billing_email" => $order['customer_email'] ?: "no-reply@livvra.in",

            /* ======================
               SHIPPING DETAILS (FIXED)
            ====================== */
            "shipping_is_billing" => false,

            "shipping_customer_name" => $order['customer_name'],
            "shipping_last_name" => ".",
            "shipping_address" => $order['shipping_address'],
            "shipping_city" => $order['city'],
            "shipping_state" => $order['state'],
            "shipping_pincode" => (string)$order['pincode'],
            "shipping_country" => "India",
            "shipping_phone" => $order['customer_phone'],

            /* ======================
               ORDER ITEMS
            ====================== */
            "order_items" => $orderItems,

            /* ======================
               PAYMENT
            ====================== */
            "payment_method" =>
                ($order['payment_method'] === 'cod') ? 'COD' : 'Prepaid',

            "sub_total" => (float)$order['subtotal'],

            /* ======================
               PACKAGE DETAILS
            ====================== */
            "length" => 10,
            "breadth" => 10,
            "height" => 10,
            "weight" => 0.5
        ];

        self::log(["ORDER_PAYLOAD" => $payload]);

        $ch = curl_init(SHIPROCKET_BASE_URL . '/orders/create/adhoc');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ]
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        self::log(["ORDER_RESPONSE" => $response, "HTTP" => $httpCode]);

        if ($httpCode !== 200) {
            throw new Exception('Shiprocket order failed: ' . $response);
        }

        return json_decode($response, true);
    }
}