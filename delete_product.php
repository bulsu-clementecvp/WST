<?php
header('Content-Type: application/json');

if (!isset($_POST['name']) || empty($_POST['name'])) {
    echo json_encode(['success' => false, 'message' => 'No product name provided.']);
    exit;
}

$productName = trim($_POST['name']);
$xmlFile = 'products.xml';

if (!file_exists($xmlFile)) {
    echo json_encode(['success' => false, 'message' => 'XML file not found.']);
    exit;
}

$xml = new DOMDocument();
$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;
$xml->load($xmlFile);

$products = $xml->getElementsByTagName('product');
$found = false;

foreach ($products as $product) {
    $nameNode = $product->getElementsByTagName('name')->item(0);
    if ($nameNode && $nameNode->nodeValue === $productName) {
        $product->parentNode->removeChild($product);
        $found = true;
        break;
    }
}

if ($found) {
    $xml->save($xmlFile);
    echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found in XML.']);
}
?>