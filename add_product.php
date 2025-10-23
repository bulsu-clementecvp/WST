<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $tags = $_POST['tags'];
    
    $imageName = '';
    if (isset($_FILES['photo'])) {
        $imageName = basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $imageName);
    }

    $xmlFile = 'products.xml';
    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
    } else {
        $xml = new SimpleXMLElement('<products></products>');
    }

    $product = $xml->addChild('product');
    $product->addChild('name', $name);
    $product->addChild('category', $category);
    $product->addChild('price', $price);
    $product->addChild('description', $description);
    $product->addChild('quantity', $quantity);
    $product->addChild('tags', $tags);
    $product->addChild('photo', $imageName);

    $xml->asXML($xmlFile);

    echo json_encode(['success' => true, 'message' => 'Product added successfully']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>