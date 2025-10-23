<?php
header('Content-Type: application/json');

$xmlFile = 'categories.xml';

if (!file_exists($xmlFile)) {
  echo json_encode([]);
  exit;
}

$xml = simplexml_load_file($xmlFile);
$categories = [];

foreach ($xml->category as $cat) {
  $category = (string)$cat;

  if (!in_array($category, $categories, true)) {
    $categories[] = $category;
  }
}

echo json_encode($categories);
?>