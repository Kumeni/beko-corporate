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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(isset($_POST['jwt'])){
            $jwt = $_POST['jwt'];

            global $secretKey ;
        
            
            /**
             * If code is expired, return 401 unauthorized access,
             */
            $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
            
            if(!isset($decoded->userId)){
                http_response_code(400);
                $data = [
                    "error" => "Invalid jwt token!"
                ];

                echo json_encode($data);
                die();
            }

            // Check if the token is close to expiry (e.g., within 1 hour minutes)
            $userId = $decoded->userId;
            $email = $decoded->email;

            //check if code matches the one stored in the db
            //Get all users with the email;
            $sql = "SELECT * FROM users WHERE id='$userId'";
            $systemUsers = find($host, $user, $password, $database, $sql);

            $newArray = [];

            foreach ($systemUsers as $index => $systemUser) {
                # code...
                unset($systemUser["deleted"]);
                $newArray[] = $systemUser;
            }

            $systemUsers = $newArray;
            $systemUser = $systemUsers[0];
            if($systemUser["jwt_token"] != $jwt){
                //Throw 400, Invalid token
                http_response_code(400);
                $data = [
                    "error" => "Invalid jwt token!"
                ];

                echo json_encode($data);
                die();
            }

            if($decoded->exp < time()){
                http_response_code(400);
                $data = [
                    "error" => "Token expired!"
                ];

                echo json_encode($data);
                die();
            } else if ($decoded->exp - time() < 3600) {
                
                $newJwt = generateJWT($userId, $email);

                $issuedAt = time();
                $expirationTime = $issuedAt + 12*3600; // Token valid for 12 hours
                
                //store the jwt in the database;
                $sql = "UPDATE users SET jwt_token='$newJwt', jwt_expiry='$expirationTime' WHERE id=$userId";
                update($host, $user, $password, $database, $sql);

                $data = [
                    "jwt" => $jwt,
                ];

                http_response_code(200);
                echo json_encode($data);
                die();
            } else {
                $data = [
                    "jwt" => $jwt
                ];

                http_response_code(200);
                echo json_encode($data);
                die();
            }
        }
    }
?>