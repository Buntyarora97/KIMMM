<?php
require_once 'includes/config.php';

// Log the incoming webhook for debugging
file_put_contents('webhook_debug.log', "POST Data: ".json_encode($_POST)."\n", FILE_APPEND);

$data = $_POST;

if(isset($data['status']) && $data['status'] == 'Credit'){
    // Instamojo 'purpose' was set to 'Livvra Order ' . $order['order_number']
    // We need to extract the order number
    $purpose = $data['purpose'];
    $order_number = str_replace('Livvra Order ', '', $purpose);

    db()->prepare("UPDATE orders SET payment_status='paid' WHERE order_number=?")
       ->execute([$order_number]);
    
    file_put_contents('webhook_debug.log', "Updated order: $order_number to paid\n", FILE_APPEND);
}