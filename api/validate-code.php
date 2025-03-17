<?php

    require './db.php';
    require './db-operations.php';
    require './upload.php';
    require '../vendor/autoload.php'; // Include PHPMailer or use PHP mail() function

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    $secretKey = "beko-corporate-sue";

    function generateJWT($userId, $email) {
        global $secretKey;
    
        $issuedAt = time();
        $expirationTime = $issuedAt + 12*3600; // Token valid for 1 hour
    
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'userId' => $userId,
            'email' => $email
        ];
    
        return JWT::encode($payload, $secretKey, 'HS256');
    }    

    function generateVerificationCode($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $code = '';
        $maxIndex = strlen($characters) -1;

        for($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, $maxIndex)];
        }
        
        return $code;
    }

    function refreshJWT($jwt) {
        global $secretKey;
    
        try {
            $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
            var_dump($decoded);
            // Check if the token is close to expiry (e.g., within 10 minutes)
            if ($decoded->exp - time() < 600) { 
                return generateJWT($decoded->sub, $decoded->email);
            }
            
            return $jwt; // Return the same token if still valid
        } catch (Exception $e) {
            return null; // Invalid token, require re-login
        }
    }

    function sendEmail(){

    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['email']) && isset($_POST["code"])){
            $email = $_POST['email'];
            $code = $_POST['code'];

            //if email don't exist, create a user with the email
            
            //Get all users with the email;
            $sql = "SELECT * FROM users WHERE email='$email'";
            $systemUsers = find($host, $user, $password, $database, $sql);

            $newArray = [];

            foreach ($systemUsers as $index => $systemUser) {
                # code...
                unset($systemUser["deleted"]);
                $newArray[] = $systemUser;
            }

            $systemUsers = $newArray;

            if(count($systemUsers) > 0){

                $systemUser = $systemUsers[0];
                $userId = $systemUser["id"];
                $userVerificationCode = $systemUser['verification_code'];
                $failedCodeAttempts = $systemUser["failed_code_attempts"];
                $remainingCodeAttempts = 4 - $failedCodeAttempts;
                $userRole = $systemUser["role_id"];

                if($userRole < 1){
                    //Throw an error
                    $data = [
                        "error" => "You're forbidden from admin panel. Contact super-admin@bekocorporatesolutions@gmail.com for further assistance.",
                    ];

                    http_response_code(403);
                    echo json_encode($data);
                    die();
                }

                if($failedCodeAttempts >= 5){
                    //Throw an error
                    $data = [
                        "error" => "Due to 5 failed login attempts, your email is blocked. Contact, super-admin@bekocorporatesolutions@gmail.com for further assistance.",
                    ];

                    http_response_code(403);
                    echo json_encode($data);
                    die();
                }

                if($code == $userVerificationCode){
                    //generatejwt containing, user-email and send it back
                    $jwt = generateJWT($userId, $email);
                    $issuedAt = time();
                    $expirationTime = $issuedAt + 12*3600; // Token valid for 1 hour

                    //store the jwt in the database;
                    $sql = "UPDATE users SET jwt_token='$jwt', jwt_expiry='$expirationTime' WHERE id=$userId";
                    update($host, $user, $password, $database, $sql);

                    $sql = "UPDATE users SET failed_code_attempts='0' WHERE id=$userId";
                    update($host, $user, $password, $database, $sql);

                    $data = [
                        "jwt" => $jwt,
                    ];

                    //store the jwt in the cookie
                    setcookie('jwt', $jwt, time() + 3600, '/', '', false, true);

                    http_response_code(200);
                    echo json_encode($data);

                    // Redirect to dashboard
                    //header("Location: ../SXk1aj/products.php");
                    exit();
                } else {
                    /**
                     * Throw bad request - Invalid code.
                     */
                    $failedCodeAttempts++;

                    $sql = "UPDATE users SET failed_code_attempts='$failedCodeAttempts' WHERE id=$userId";
                    update($host, $user, $password, $database, $sql);

                    if($failedCodeAttempts >= 5){
                        //Throw an error
                        $data = [
                            "error" => "Due to 5 failed login attempts, your email is blocked. Contact, super-admin@bekocorporatesolutions@gmail.com for further assistance.",
                        ];
    
                        http_response_code(403);
                        echo json_encode($data);
                        die();
                    }

                    $attemptsRemaining = "1 attempt";
                    if($remainingCodeAttempts <= 1){
                        $attemptsRemaining = "1 attempt";
                    } else {
                        $attemptsRemaining = "$remainingCodeAttempts attempts";
                    }
                    $data = [
                        "error" => "Invalid Code. $attemptsRemaining remaining. Check your email, $email, for correct code, and try again!"
                    ];
                    http_response_code(400);
                    echo json_encode($data);
                    die();
                }
            } else {
                //create user with verification code
                $code = generateVerificationCode(6);
                $issuedAt = time();
                $expirationTime = $issuedAt + 12*3600; // Token valid for 1 hour
                $sql = "INSERT INTO users(`email`, `verification_code`, `code_expiry`) VALUES('$email', '$code', '$expirationTime')";
                $userId = create($host, $user, $password, $database, $sql);

                /**
                 * Email the login code to the user;
                 */
                //Throw invalide code; Recommend check email for code.
                $data = [
                    "error" => "Invalid Code. Check your email, $email, for correct code, and try again!"
                ];
                http_response_code(400);
                echo json_encode($data);
                die();
                /**
                 * Return success message for user to check
                 */
            }
        } else {
            /**
             * Throw a bad request error, say, email or code or both is required accordingly
             */
        }
    }
?>