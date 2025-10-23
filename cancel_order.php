<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $transactionId = trim($_POST['id']);
    $xmlFile = 'transactions.xml';

    if (!file_exists($xmlFile)) {
        $response['message'] = 'transactions.xml not found.';
        echo json_encode($response);
        exit();
    }

    $xml = simplexml_load_file($xmlFile);
    if ($xml === false) {
        $response['message'] = 'Failed to load transactions.xml.';
        echo json_encode($response);
        exit();
    }

    $transactionFound = false;
    foreach ($xml->transaction as $transaction) {
        if ((string)$transaction->id === $transactionId) {
            $transaction->status = 'Cancelled';
            $transactionFound = true;
            break;
        }
    }

    if ($transactionFound) {
        if ($xml->asXML($xmlFile)) {
            $response['success'] = true;
            $response['message'] = 'Transaction status updated to Cancelled.';
        } else {
            $response['message'] = 'Failed to save transactions.xml. Check file permissions.';
        }
    } else {
        $response['message'] = 'Transaction not found.';
    }

} else {
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
?>