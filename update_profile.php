<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['username'])) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit();
}

$username = $_SESSION['username'];

$host = "localhost";
$dbname = "sample";
$dbuser = "root";
$dbpass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $firstName = trim($_POST['firstName'] ?? '');
    $middleName = trim($_POST['middleName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $newUsername = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($firstName) || empty($lastName) || empty($newUsername) || empty($email)) {
        $response['message'] = 'First Name, Last Name, Username, and Email are required.';
        echo json_encode($response);
        exit();
    }

    $stmt = $pdo->prepare("SELECT Username, Email FROM user WHERE (Username = ? OR Email = ?) AND Username != ?");
    $stmt->execute([$newUsername, $email, $username]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        if ($existingUser['Username'] === $newUsername) {
            $response['message'] = 'This username is already taken by another account.';
        } elseif ($existingUser['Email'] === $email) {
            $response['message'] = 'This email is already registered to another account.';
        }
        echo json_encode($response);
        exit();
    }

    $sql = "UPDATE user SET FirstName = ?, MiddleName = ?, LastName = ?, Username = ?, Email = ?";
    $params = [$firstName, $middleName, $lastName, $newUsername, $email];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", Password = ?";
        $params[] = $hashed_password;
    }

    $sql .= " WHERE Username = ?";
    $params[] = $username;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() > 0) {
        $_SESSION['username'] = $newUsername;
        $response['success'] = true;
        $response['message'] = 'Profile updated successfully!';
        $response['data'] = [
            'FirstName' => $firstName,
            'MiddleName' => $middleName,
            'LastName' => $lastName,
            'Username' => $newUsername,
            'Email' => $email
        ];
    } else {
        $response['message'] = 'No changes made or user not found.';
    }

} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?>