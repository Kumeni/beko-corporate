<?php
function refreshJWT($jwt) {
    global $secretKey;

    try {
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
        
        // Check if the token is close to expiry (e.g., within 10 minutes)
        if ($decoded->exp - time() < 600) { 
            return generateJWT($decoded->sub, $decoded->email);
        }
        
        return $jwt; // Return the same token if still valid
    } catch (Exception $e) {
        return null; // Invalid token, require re-login
    }
}

// Handle JWT refresh request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $newToken = refreshJWT($_POST['token']);

    if ($newToken) {
        echo json_encode(['token' => $newToken, 'message' => 'Token refreshed']);
    } else {
        echo json_encode(['message' => 'Session expired, please log in again'], JSON_PRETTY_PRINT);
    }
}
?>
