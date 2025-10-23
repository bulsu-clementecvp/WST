<?php
session_start(); 
include 'db_connect.php'; 


define('RECAPTCHA_SECRET_KEY', '6LeBJUkrAAAAAEDtWaDi4XyMoFTINAvH_cgAkV3n'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    if (empty($recaptchaResponse)) {
    
        echo "<script>alert('Please complete the reCAPTCHA.'); window.location.href='loginpage.php';</script>";
        exit();
    }


    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $postData = [
        'secret' => RECAPTCHA_SECRET_KEY, 
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $verifyUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); 

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        error_log("cURL error during reCAPTCHA verification: " . $curlError);
        echo "<script>alert('An error occurred during reCAPTCHA verification. Please try again later.'); window.location.href='loginpage.php';</script>";
        exit();
    }

    $responseData = json_decode($response, true);

    if (!$responseData || !isset($responseData['success']) || !$responseData['success']) {
        
        error_log("reCAPTCHA verification failed. Response: " . print_r($responseData, true));
        $errorMsg = "reCAPTCHA verification failed. ";
        if (isset($responseData['error-codes'])) {
            $errorMsg .= "Error codes: " . implode(", ", $responseData['error-codes']);
        }
        echo "<script>alert('" . $errorMsg . " Please try again.'); window.location.href='loginpage.php';</script>";
        exit();
    }

   

    
    $fname = $_POST['fname'];
    $mname = $_POST['mname'] ?? ''; 
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password']; 
    $email = $_POST['Email'];

    if (empty($fname) || empty($lname) || empty($username) || empty($password) || empty($email)) {
        echo "<script>alert('All required fields must be filled.'); window.location.href='loginpage.php';</script>";
        exit();
    }


    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>alert('Username or Email already exists.'); window.location.href='loginpage.php';</script>";
        exit();
    }

    
    $stmt = $conn->prepare("INSERT INTO user (FirstName, MiddleName, LastName, Username, Password, Email) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fname, $mname, $lname, $username, $password, $email);

    if ($stmt->execute()) {
        echo "<script> window.location.href='loginpage.php?registered=success';</script>";
    } else {
        echo "<script>alert('Registration failed: " . $stmt->error . "'); window.location.href='loginpage.php';</script>";
    }
    $stmt->close();
    $conn->close();
} else {
   
    header("Location: loginpage.php");
    exit();
}
?>