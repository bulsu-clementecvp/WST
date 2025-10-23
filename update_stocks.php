<?php
$data = json_decode(file_get_contents('php://input'), true);
$productName = $data['productName'] ?? '';
$addedQuantity = (int)($data['addedQuantity'] ?? 0);

if (!$productName || $addedQuantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$xml = simplexml_load_file('products.xml');
if (!$xml) {
    echo json_encode(['success' => false, 'message' => 'Failed to load XML']);
    exit;
}

$product = null;
foreach ($xml->product as $p) {
    if ((string)$p->name === $productName) {
        $product = $p;
        break;
    }
}

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

$currentQty = (int)$product->quantity;
$product->quantity = $currentQty + $addedQuantity;

if ($xml->asXML('products.xml')) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save XML']);
}
?>