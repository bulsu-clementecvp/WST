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

$index = $_POST['index'] ?? null;
$house_no = $_POST['house_no'] ?? '';
$street = $_POST['street'] ?? '';
$brgy = $_POST['brgy'] ?? '';
$city = $_POST['city'] ?? '';
$province = $_POST['province'] ?? '';
$country = $_POST['country'] ?? '';
$postal_code = $_POST['postal_code'] ?? '';

if (!isset($index) || !is_numeric($index) || empty($house_no) || empty($street) || empty($brgy) || empty($city) || empty($province) || empty($country) || empty($postal_code)) {
    $response['message'] = 'Invalid data provided for update.';
    echo json_encode($response);
    exit();
}

if (!file_exists($xmlFile)) {
    $response['message'] = 'addresses.xml not found.';
    echo json_encode($response);
    exit();
}

$xml = simplexml_load_file($xmlFile);
if ($xml === false) {
    $response['message'] = 'Failed to load addresses.xml for update. Check file syntax or permissions.';
    error_log('update_address.php: Failed to load addresses.xml for updating.');
    echo json_encode($response);
    exit();
}

$userAddresses = [];
$actualAddressNodes = [];
foreach ($xml->address as $addr) {
    if (isset($addr->username) && (string)$addr->username === $username) {
        $actualAddressNodes[] = $addr;
    }
}

if (isset($actualAddressNodes[$index])) {
    $targetAddress = $actualAddressNodes[$index];

    $targetAddress->house_no = htmlspecialchars($house_no);
    $targetAddress->street = htmlspecialchars($street);
    $targetAddress->brgy = htmlspecialchars($brgy);
    $targetAddress->city = htmlspecialchars($city);
    $targetAddress->province = htmlspecialchars($province);
    $targetAddress->country = htmlspecialchars($country);
    $targetAddress->postal_code = htmlspecialchars($postal_code);

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());

    if ($dom->save($xmlFile)) {
        $response['success'] = true;
        $response['message'] = 'Address updated successfully!';
    } else {
        $response['message'] = 'Failed to save addresses.xml after update. Check file permissions.';
        error_log('update_address.php: Failed to save XML to addresses.xml after update.');
    }
} else {
    $response['message'] = 'Address not found at the specified index for this user.';
}

echo json_encode($response);
?>