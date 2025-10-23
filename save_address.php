<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$username = $_SESSION['username'];
$action = $_POST['action'] ?? '';
$index = isset($_POST['index']) ? intval($_POST['index']) : null;


function validate_address($data) {
    $required = ['house_no', 'street', 'brgy', 'city', 'province', 'country', 'postal_code'];
    foreach ($required as $field) {
        if (empty(trim($data[$field] ?? ''))) {
            return "Field '$field' is required.";
        }
    }
    return null;
}

$validationError = validate_address($_POST);
if ($validationError) {
    echo json_encode(['success' => false, 'message' => $validationError]);
    exit;
}

$xmlFile = 'addresses.xml';


if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
    if ($xml === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to load XML file']);
        exit;
    }
} else {
    $xml = new SimpleXMLElement('<addresses></addresses>');
}

if ($action === 'add') {

    $newAddr = $xml->addChild('address');
    $newAddr->addChild('username', htmlspecialchars($username));
    $newAddr->addChild('house_no', htmlspecialchars($_POST['house_no']));
    $newAddr->addChild('street', htmlspecialchars($_POST['street']));
    $newAddr->addChild('brgy', htmlspecialchars($_POST['brgy']));
    $newAddr->addChild('city', htmlspecialchars($_POST['city']));
    $newAddr->addChild('province', htmlspecialchars($_POST['province']));
    $newAddr->addChild('country', htmlspecialchars($_POST['country']));
    $newAddr->addChild('postal_code', htmlspecialchars($_POST['postal_code']));
    $newAddr->addChild('added_on', date('Y-m-d H:i:s'));

    $result = $xml->asXML($xmlFile);
    if ($result === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to save XML']);
    } else {
        echo json_encode(['success' => true]);
    }
    exit;
} elseif ($action === 'edit' && $index !== null) {

    $addresses = [];
    foreach ($xml->address as $addr) {
        if ((string)$addr->username === $username) {
            $addresses[] = $addr;
        }
    }

    if (!isset($addresses[$index])) {
        echo json_encode(['success' => false, 'message' => 'Address not found']);
        exit;
    }

   
    $addrToEdit = $addresses[$index];
    $addrToEdit->house_no = htmlspecialchars($_POST['house_no']);
    $addrToEdit->street = htmlspecialchars($_POST['street']);
    $addrToEdit->brgy = htmlspecialchars($_POST['brgy']);
    $addrToEdit->city = htmlspecialchars($_POST['city']);
    $addrToEdit->province = htmlspecialchars($_POST['province']);
    $addrToEdit->country = htmlspecialchars($_POST['country']);
    $addrToEdit->postal_code = htmlspecialchars($_POST['postal_code']);

    $result = $xml->asXML($xmlFile);
    if ($result === false) {
        echo json_encode(['success' => false, 'message' => 'Failed to save XML']);
    } else {
        echo json_encode(['success' => true]);
    }
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}
