<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require 'vendor/autoload.php';

$secretKey = "your_secret_key"; // Keep this secure

function verifyCode($email, $code) {
    $pdo = new PDO("mysql:host=localhost;dbname=your_db", "username", "password");

    $stmt = $pdo->prepare("SELECT id, code_expiry FROM users WHERE email=:email AND verification_code=:code");
    $stmt->execute(['email' => $email, 'code' => $code]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['code_expiry'] < time()) {
        return false; // Invalid or expired code
    }

    return $user['id'];
}

function generateJWT($userId, $email) {
    global $secretKey;

    $issuedAt = time();
    $expirationTime = $issuedAt + 3600; // Token valid for 1 hour

    $payload = [
        'iat' => $issuedAt,
        'exp' => $expirationTime,
        'sub' => $userId,
        'email' => $email
    ];

    return JWT::encode($payload, $secretKey, 'HS256');
}

// Handle verification request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['code'])) {
    $email = $_POST['email'];
    $code = $_POST['code'];

    $userId = verifyCode($email, $code);
    
    if ($userId) {
        $jwt = generateJWT($userId, $email);
        
        echo json_encode(['token' => $jwt, 'message' => 'Authentication successful']);
    } else {
        echo json_encode(['message' => 'Invalid or expired code'], JSON_PRETTY_PRINT);
    }
}
?>
