<?php
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['name'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit;
}

$xml = new DOMDocument();
$xml->load('products.xml');
$products = $xml->getElementsByTagName('product');
$found = false;

foreach ($products as $product) {
    $name = $product->getElementsByTagName('name')[0]->nodeValue;
    if ($name === $data['name']) {
        $product->getElementsByTagName('category')[0]->nodeValue = $data['category'];
        $product->getElementsByTagName('description')[0]->nodeValue = $data['description'];
        $product->getElementsByTagName('tags')[0]->nodeValue = $data['tags'];
        $product->getElementsByTagName('price')[0]->nodeValue = $data['price'];
        $product->getElementsByTagName('quantity')[0]->nodeValue = $data['quantity'];
        $product->getElementsByTagName('photo')[0]->nodeValue = $data['photo'];
        $found = true;
        break;
    }
}

if ($found) {
    $xml->save('products.xml');
    echo json_encode(['success' => true, 'message' => 'Product updated.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
}
?>