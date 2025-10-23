<?php

session_start();
header('Content-Type: application/json'); 

$response = [];

if (!isset($_SESSION['username'])) {
    
    $response = ['error' => 'User not logged in.'];
    echo json_encode($response);
    exit();
}

$username = $_SESSION['username'];
$xmlFile = 'addresses.xml';

if (!file_exists($xmlFile)) {
   
    $xml = new SimpleXMLElement('<addresses></addresses>');

    if (!$xml->asXML($xmlFile)) {
        error_log("Failed to create empty addresses.xml for user: " . $username);
        $response = ['error' => 'Could not create addresses.xml. Check directory permissions.'];
        echo json_encode($response);
        exit();
    }
    echo json_encode([]); 
    exit();
}

$xml = simplexml_load_file($xmlFile);
if ($xml === false) {
    
    error_log("Failed to load XML from " . $xmlFile . " for user: " . $username);
    $response = ['error' => 'Failed to load addresses.xml. Check file syntax or permissions.'];
    echo json_encode($response);
    exit();
}

$userAddresses = [];
foreach ($xml->address as $addr) {
    
    if (isset($addr->username) && (string)$addr->username === $username) {
        $userAddresses[] = [
            'house_no'    => (string)$addr->house_no,
            'street'      => (string)$addr->street,
            'brgy'        => (string)$addr->brgy,
            'city'        => (string)$addr->city,
            'province'    => (string)$addr->province,
            'country'     => (string)$addr->country,
            'postal_code' => (string)$addr->postal_code,
            'added_on'    => (string)$addr->added_on ?? '' 
        ];
    }
}

echo json_encode($userAddresses);
