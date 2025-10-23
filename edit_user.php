<?php

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = trim($_POST['userId'] ?? '');
    $firstName = trim($_POST['firstName'] ?? '');
    $middleName = trim($_POST['middleName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? ''; 

    if (empty($userId) || empty($firstName) || empty($lastName) || empty($username) || empty($email)) {
        $response['message'] = 'Missing required fields.';
        echo json_encode($response);
        exit();
    }

    $xmlFile = 'users.xml';

    if (!file_exists($xmlFile)) {
        $response['message'] = 'users.xml not found.';
        echo json_encode($response);
        exit();
    }

    $xml = simplexml_load_file($xmlFile);
    if ($xml === false) {
        $response['message'] = 'Failed to load users.xml.';
        echo json_encode($response);
        exit();
    }

    $userFound = false;
    foreach ($xml->user as $user) {
        if ((string)$user->ID === $userId) {
            $user->FirstName = $firstName;
            $user->MiddleName = $middleName;
            $user->LastName = $lastName;
            $user->Username = $username;
            $user->Email = $email;
            if (!empty($password)) {
                
                $user->Password = $password; 
            }
            $userFound = true;
            break;
        }
    }

    if ($userFound) {
        if ($xml->asXML($xmlFile)) {
            $response['success'] = true;
            $response['message'] = 'User updated successfully.';
        } else {
            $response['message'] = 'Failed to save users.xml. Check file permissions.';
        }
    } else {
        $response['message'] = 'User not found.';
    }

} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>