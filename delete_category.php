<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['index'])) {
    $indexToDelete = (int)$_POST['index'];

    $categoriesXmlFile = 'categories.xml';
    $productsXmlFile = 'products.xml';

    if (!file_exists($categoriesXmlFile)) {
        $response['message'] = 'categories.xml not found.';
        echo json_encode($response);
        exit();
    }

    $categoriesXml = simplexml_load_file($categoriesXmlFile);
    if ($categoriesXml === false) {
        $response['message'] = 'Failed to load categories.xml.';
        echo json_encode($response);
        exit();
    }

    if (!isset($categoriesXml->category[$indexToDelete])) {
        $response['message'] = 'Category index out of bounds.';
        echo json_encode($response);
        exit();
    }

    $categoryNameToDelete = (string)$categoriesXml->category[$indexToDelete];

    if (file_exists($productsXmlFile)) {
        $productsXml = simplexml_load_file($productsXmlFile);
        if ($productsXml === false) {
            $response['message'] = 'Failed to load products.xml (cannot delete associated products).';
            echo json_encode($response);
            exit();
        }

        $productsRemovedCount = 0;
        $productsToKeep = new SimpleXMLElement('<products></products>');

        foreach ($productsXml->product as $product) {
            if ((string)$product->category === $categoryNameToDelete) {
                $photoPath = (string)$product->photo;
                if (file_exists($photoPath) && is_file($photoPath)) {
                    unlink($photoPath);
                }
                $productsRemovedCount++;
            } else {
                $newProduct = $productsToKeep->addChild('product');
                foreach ($product->children() as $child) {
                    $newProduct->addChild($child->getName(), (string)$child);
                }
            }
        }

        if ($productsToKeep->asXML($productsXmlFile)) {
            $response['message'] = "Category '{$categoryNameToDelete}' and {$productsRemovedCount} associated products deleted. ";
        } else {
            $response['message'] = "Category '{$categoryNameToDelete}' deleted, but failed to update products.xml after removing {$productsRemovedCount} products.";
            $response['success'] = false;
            echo json_encode($response);
            exit();
        }
    } else {
        $response['message'] = "products.xml not found. Only category '{$categoryNameToDelete}' will be deleted. ";
    }

    unset($categoriesXml->category[$indexToDelete]);

    if ($categoriesXml->asXML($categoriesXmlFile)) {
        $response['success'] = true;
        $response['message'] .= "Category '{$categoryNameToDelete}' successfully deleted.";
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to save categories.xml after deleting category '{$categoryNameToDelete}'.";
    }

} else {
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
?>