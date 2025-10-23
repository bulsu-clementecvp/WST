<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['username'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit();
}

$username = $_SESSION['username'];

$xmlFile = 'addresses.xml';

$house_no = $_POST['house_no'] ?? '';
$street = $_POST['street'] ?? '';
$brgy = $_POST['brgy'] ?? '';
$city = $_POST['city'] ?? '';
$province = $_POST['province'] ?? '';
$country = $_POST['country'] ?? '';
$postal_code = $_POST['postal_code'] ?? '';

if (empty($house_no) || empty($street) || empty($brgy) || empty($city) || empty($province) || empty($country) || empty($postal_code)) {
    $response['message'] = 'All address fields are required.';
    echo json_encode($response);
    exit();
}

if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
    if ($xml === false) {
        $response['message'] = 'Failed to load addresses.xml. Check file syntax or permissions.';
        error_log('add_address.php: Failed to load addresses.xml for writing.');
        echo json_encode($response);
        exit();
    }
} else {
    $xml = new SimpleXMLElement('<addresses></addresses>');
    if ($xml === false) {
        $response['message'] = 'Failed to create new addresses.xml file structure.';
        error_log('add_address.php: Failed to create new SimpleXMLElement for addresses.xml.');
        echo json_encode($response);
        exit();
    }
}

$address = $xml->addChild('address');
$address->addChild('username', $username);
$address->addChild('house_no', htmlspecialchars($house_no));
$address->addChild('street', htmlspecialchars($street));
$address->addChild('brgy', htmlspecialchars($brgy));
$address->addChild('city', htmlspecialchars($city));
$address->addChild('province', htmlspecialchars($province));
$address->addChild('country', htmlspecialchars($country));
$address->addChild('postal_code', htmlspecialchars($postal_code));
$address->addChild('added_on', date('Y-m-d H:i:s'));

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());

if ($dom->save($xmlFile)) {
    $response['success'] = true;
    $response['message'] = 'Address added successfully!';
} else {
    $response['message'] = 'Failed to save address to XML. Check file permissions.';
    error_log('add_address.php: Failed to save XML to addresses.xml. Check folder/file permissions.');
}

echo json_encode($response);
?>