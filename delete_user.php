<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userIdToDelete = trim($_POST['id']);

    $xmlFile = 'users.xml';

    if (!file_exists($xmlFile)) {
        $response['message'] = 'users.xml not found.';
        echo json_encode($response);
        exit();
    }

    $xml = simplexml_load_file($xmlFile);
    if ($xml === false) {
        $response['message'] = 'Failed to load users.xml.';
        echo json_encode($response);
        exit();
    }

    $userFound = false;
    $i = 0;
    foreach ($xml->user as $user) {
        if ((string)$user->ID === $userIdToDelete) {
            unset($xml->user[$i]);
            $userFound = true;
            break;
        }
        $i++;
    }

    if ($userFound) {
        if ($xml->asXML($xmlFile)) {
            $response['success'] = true;
            $response['message'] = 'User deleted successfully.';
        } else {
            $response['message'] = 'Failed to save users.xml. Check file permissions.';
        }
    } else {
        $response['message'] = 'User not found.';
    }

} else {
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
?>