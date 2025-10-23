<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $index = intval($_POST['index']);
    $newName = trim($_POST['new_name']);
    $xmlFile = 'categories.xml';

    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
        if (isset($xml->category[$index])) {
            $xml->category[$index] = $newName;
            $xml->asXML($xmlFile);
            echo json_encode(['success' => true, 'message' => 'Category updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid index']);
        }
    }
}
?>