<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$id || !in_array($status, ['APPROVED', 'DECLINED'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid parameters']);
        exit();
    }

    $xml = simplexml_load_file('transactions.xml');
    foreach ($xml->transaction as $transaction) {
        if ((string)$transaction->id == $id) {
            $transaction->status = $status;
            $xml->asXML('transactions.xml');
            echo json_encode(['success' => true]);
            exit();
        }
    }

    http_response_code(404);
    echo json_encode(['error' => 'Transaction not found']);
    exit();
}
?>