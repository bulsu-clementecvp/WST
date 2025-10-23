<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $originalName = trim($_POST['originalName'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $quantity = intval($_POST['quantity'] ?? 0);
    $photoPath = '';

    if (empty($originalName) || empty($name) || empty($category) || $price <= 0) {
        $response['message'] = 'Missing required fields for update.';
        echo json_encode($response);
        exit();
    }

    $xmlFile = 'products.xml';

    if (!file_exists($xmlFile)) {
        $response['message'] = 'products.xml not found.';
        echo json_encode($response);
        exit();
    }

    $xml = simplexml_load_file($xmlFile);
    if ($xml === false) {
        $response['message'] = 'Failed to load products.xml.';
        echo json_encode($response);
        exit();
    }

    $productFound = false;
    foreach ($xml->product as $product) {
        if ((string)$product->name === $originalName) {
            $productFound = true;

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = basename($_FILES['photo']['name']);
                $targetFilePath = $uploadDir . $fileName;

                if ((string)$product->photo !== $targetFilePath && file_exists((string)$product->photo)) {
                    unlink((string)$product->photo);
                }

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
                    $photoPath = $targetFilePath;
                    $product->photo = $photoPath;
                } else {
                    $response['message'] = 'Failed to upload new photo.';
                    echo json_encode($response);
                    exit();
                }
            } else {
                $photoPath = (string)$product->photo;
            }

            $product->name = $name;
            $product->category = $category;
            $product->price = sprintf('%.2f', $price);
            $product->description = $description;
            $product->tags = $tags;
            $product->quantity = $quantity;

            break;
        }
    }

    if ($productFound) {
        if ($xml->asXML($xmlFile)) {
            $response['success'] = true;
            $response['message'] = 'Product updated successfully.';
            $response['photoPath'] = $photoPath;
        } else {
            $response['message'] = 'Failed to save products.xml. Check file permissions.';
        }
    } else {
        $response['message'] = 'Product not found for update (original name mismatch).';
    }

} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>