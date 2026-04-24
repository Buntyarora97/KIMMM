<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

if (isset($_GET['pincode'])) {
    $pincode = $_GET['pincode'];
    
    if (strlen($pincode) !== 6 || !is_numeric($pincode)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid pincode format']);
        exit;
    }

    // Use a public PostOffice API for real India pincode lookup
    $url = "https://api.postalpincode.in/pincode/$pincode";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $data = json_decode($response, true);
        if (isset($data[0]['Status']) && $data[0]['Status'] === 'Success') {
            $postOffice = $data[0]['PostOffice'][0];
            $city = $postOffice['District'];
            $state = $postOffice['State'];
            
            // Generate a random but realistic delivery estimate
            // In a real app, this would come from Shiprocket/Delhivery API
            $days_list = ['2-3 days', '3-4 days', '4-5 days', '5-7 days'];
            $days = $days_list[array_rand($days_list)];
            
            echo json_encode([
                'status' => 'success',
                'data' => [
                    'city' => $city,
                    'state' => $state,
                    'days' => $days
                ]
            ]);
            exit;
        }
    }

    // Fallback for offline/error or if API fails
    $mock_data = [
        '141120' => ['city' => 'Ludhiana', 'state' => 'Punjab', 'days' => '2-3 days'],
        '125111' => ['city' => 'Fatehabad', 'state' => 'Haryana', 'days' => '3-4 days'],
        '110001' => ['city' => 'Delhi', 'state' => 'Delhi', 'days' => '1-2 days'],
    ];

    if (isset($mock_data[$pincode])) {
        echo json_encode(['status' => 'success', 'data' => $mock_data[$pincode]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Delivery not available or invalid pincode']);
    }
}
