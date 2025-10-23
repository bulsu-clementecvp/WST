<?php

header('Content-Type: application/json');


$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}


if (
    !isset($data['cart']) || !is_array($data['cart']) || count($data['cart']) === 0 ||
    !isset($data['payment_method']) || empty($data['payment_method']) ||
    !isset($data['shipping_address']) || !is_array($data['shipping_address']) ||
    !isset($data['total']) ||
    !isset($data['checkout_datetime'])
) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}


$xmlFile = 'transactions.xml';


if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
} else {
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><transactions></transactions>');
}


$transaction = $xml->addChild('transaction');


$transactionId = uniqid('TXN_');
$transaction->addChild('id', $transactionId);
$transaction->addChild('payment_method', htmlspecialchars($data['payment_method']));
$transaction->addChild('total', number_format(floatval($data['total']), 2, '.', ''));
$transaction->addChild('checkout_datetime', htmlspecialchars($data['checkout_datetime']));


$address = $transaction->addChild('shipping_address');
foreach ($data['shipping_address'] as $key => $value) {
    $address->addChild($key, htmlspecialchars($value));
}


$items = $transaction->addChild('items');
foreach ($data['cart'] as $item) {
    $cartItem = $items->addChild('item');
    $cartItem->addChild('name', htmlspecialchars($item['name']));
    $cartItem->addChild('price', number_format(floatval($item['price']), 2, '.', ''));
    $cartItem->addChild('quantity', intval($item['quantity']));
    $cartItem->addChild('size', htmlspecialchars($item['size'] ?? ''));
    $cartItem->addChild('color', htmlspecialchars($item['color'] ?? ''));
    $cartItem->addChild('photo', htmlspecialchars($item['photo']));
}


if ($xml->asXML($xmlFile)) {
    echo json_encode(['success' => true, 'transaction_id' => $transactionId]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save XML']);
}
