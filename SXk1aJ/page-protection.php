<?php
    //require 'jwt_helper.php'; // Include your JWT helper functions
    require '../vendor/autoload.php'; // Include PHPMailer or use PHP mail() function

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    $secretKey = "beko-corporate-sue";

    // Get the JWT token from session, cookies, or headers
    $headers = apache_request_headers();
    $token = isset($_COOKIE['jwt']) ? $_COOKIE['jwt'] : (isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '');
    
    if(!isset($_COOKIE['jwt'])){
        header("Location: ./auth/login.php");
        exit();
    }

    try {
        global $secretKey ;
        
        // If jwt is invalid, redirect to login page.
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        
        if(!isset($decoded->userId)){
            header("Location: ./auth/login.php");
            exit();
        }

    } catch (Exception $e) {
        // Invalid token or expired
        header("Location: ./auth/login.php");
        exit();
    }
?>
