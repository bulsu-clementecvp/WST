<?php

header('Content-Type: text/html'); 

$xmlFile = 'categories.xml';

if (!file_exists($xmlFile)) {
    echo '<tr><td colspan="2" style="text-align: center;">No categories found.</td></tr>';
    exit;
}

$xml = simplexml_load_file($xmlFile);
if ($xml === false) {
    echo '<tr><td colspan="2" style="text-align: center; color: red;">Failed to load categories.xml.</td></tr>';
    exit;
}

$output = '';
$index = 0;
foreach ($xml->category as $cat) {
    $categoryName = htmlspecialchars((string)$cat); 
    $output .= '<tr>';
    $output .= '<td>' . $categoryName . '</td>';
    $output .= '<td>';
   
    $output .= '<button onclick="editCategory(' . $index . ')" style="margin-right: 5px;">Edit</button>';
    $output .= '<button onclick="deleteCategory(' . $index . ')">Delete</button>';
    $output .= '</td>';
    $output .= '</tr>';
    $index++; 
}

if (empty($output)) {
    echo '<tr><td colspan="2" style="text-align: center;">No categories found.</td></tr>';
} else {
    echo $output;
}
?>