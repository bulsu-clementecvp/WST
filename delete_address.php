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

$indexToDelete = $_POST['index'] ?? null;

if (!isset($indexToDelete) || !is_numeric($indexToDelete)) {
    $response['message'] = 'Invalid index provided for deletion.';
    echo json_encode($response);
    exit();
}

if (!file_exists($xmlFile)) {
    $response['message'] = 'addresses.xml not found.';
    echo json_encode($response);
    exit();
}

$dom = new DOMDocument();
libxml_use_internal_errors(true);
if (!$dom->load($xmlFile)) {
    $errors = libxml_get_errors();
    libxml_clear_errors();
    $response['message'] = 'Failed to load addresses.xml for deletion. Check file syntax or permissions.';
    error_log('delete_address.php: Failed to load addresses.xml using DOMDocument. Errors: ' . print_r($errors, true));
    echo json_encode($response);
    exit();
}
libxml_use_internal_errors(false);

$xpath = new DOMXPath($dom);
$userAddressNodes = $xpath->query('//addresses/address[username="' . $username . '"]');

if ($userAddressNodes->length > 0 && $indexToDelete >= 0 && $indexToDelete < $userAddressNodes->length) {
    $nodeToDelete = $userAddressNodes->item($indexToDelete);
    if ($nodeToDelete) {
        $nodeToDelete->parentNode->removeChild($nodeToDelete);
        if ($dom->save($xmlFile)) {
            $response['success'] = true;
            $response['message'] = 'Address deleted successfully!';
        } else {
            $response['message'] = 'Failed to save addresses.xml after deletion. Check file permissions.';
            error_log('delete_address.php: Failed to save XML to addresses.xml after deletion.');
        }
    } else {
        $response['message'] = 'Address node not found at specified index for this user (should not happen).';
    }
} else {
    $response['message'] = 'Address not found at the specified index for this user, or invalid index.';
}

echo json_encode($response);
?>