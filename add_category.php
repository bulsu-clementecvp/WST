<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $categoryName = trim($_POST['category_name']);
    $xmlFile = 'categories.xml';

    if (!file_exists($xmlFile)) {
        $xml = new SimpleXMLElement('<categories></categories>');
    } else {
        $xml = simplexml_load_file($xmlFile);
    }

    $xml->addChild('category', $categoryName);
    $xml->asXML($xmlFile);

    echo json_encode(['success' => true, 'message' => 'Category added successfully']);
}
?>