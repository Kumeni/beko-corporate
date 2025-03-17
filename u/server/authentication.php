<?php
require 'vendor/autoload.php'; // Include PHPMailer or use PHP mail() function

function generateVerificationCode() {
    return rand(100000, 999999); // 6-digit code
}

function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $message = "Your verification code is: $code";
    $headers = "From: no-reply@yourdomain.com";

    return mail($email, $subject, $message, $headers);
}

function storeVerificationCode($email, $code) {
    $pdo = new PDO("mysql:host=localhost;dbname=your_db", "username", "password");
    
    $expiry = time() + 600; // Code valid for 10 minutes

    $stmt = $pdo->prepare("INSERT INTO users (email, verification_code, code_expiry) 
                           VALUES (:email, :code, :expiry) 
                           ON DUPLICATE KEY UPDATE verification_code=:code, code_expiry=:expiry");

    $stmt->execute(['email' => $email, 'code' => $code, 'expiry' => $expiry]);
}

// Handle authentication request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $code = generateVerificationCode();

    storeVerificationCode($email, $code);
    sendVerificationEmail($email, $code);

    echo json_encode(['message' => 'Verification code sent']);
}
?>
