<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    exit("Access denied");
}

$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) {
    http_response_code(500);
    exit("Database connection failed");
}

$sql = "SELECT ID, FirstName, MiddleName, LastName, Username, Email FROM user";
$result = $conn->query($sql);

$users = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($users);

$conn->close();
?>
