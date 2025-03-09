<?php
class Auth {
    private $conn;
    private $secret_key = "your_secret_key";

    public function __construct() {
        $this->conn = mysqli_connect("localhost", "username", "password", "your_database");
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function register($email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $email = mysqli_real_escape_string($this->conn, $email);
        $query = "INSERT INTO users (email, password) VALUES ('$email', '$hashedPassword')";
        return mysqli_query($this->conn, $query);
    }

    public function login($email, $password) {
        $email = mysqli_real_escape_string($this->conn, $email);
        $query = "SELECT id, email, password FROM users WHERE email = '$email'";
        $result = mysqli_query($this->conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $payload = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'exp' => time() + (60 * 60) // 1-hour expiry
                ];
                return $this->generateJWT($payload);
            }
        }
        return false;
    }

    private function generateJWT($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);
        
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        
        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $this->secret_key, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }

    public function isAuthenticated() {
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return false;
        }
        
        $token = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        return $this->validateJWT($token);
    }

    private function validateJWT($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = $parts;
        
        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $this->secret_key, true);
        $expectedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        if (!hash_equals($expectedSignature, $base64UrlSignature)) {
            return false;
        }
        
        $payload = json_decode(base64_decode($base64UrlPayload), true);
        return ($payload && isset($payload['exp']) && $payload['exp'] > time()) ? $payload : false;
    }
}
