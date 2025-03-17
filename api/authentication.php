<?php

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    $secretKey = "beko-corporate-sue";

    $headers = apache_request_headers();
    $token = isset($_COOKIE['jwt']) ? $_COOKIE['jwt'] : (isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '');

    if (!$token) {
        http_response_code(403);
        $data = [
            "error" => "Forbidden. Authentication required!"
        ];
        echo json_encode($data);
        exit();
    }

    try {
        global $secretKey ;
        
        // If jwt is invalid, redirect to login page.
        $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
        
        if(!isset($decoded->userId)){
            http_response_code(400);
            $data = [
                "error" => "Bad request. Invalid token!"
            ];
            echo json_encode($data);
            exit();
        }

        $userId = $decoded->userId;
        $sql = "SELECT * FROM users WHERE id='$userId'";
        $systemUsers = find($host, $user, $password, $database, $sql);
        $newArray = [];
        $userRoleId = 1;

        foreach ($systemUsers as $index => $systemUser) {
            # code...
            //unset($systemUser["deleted"]);
            $newArray[] = $systemUser;
            $userRoleId = $systemUser["role_id"];
        }

        if($userRoleId <= 0){
            http_response_code(403);
            $data = [
                "error" => "Forbidden. You don't have the permission to access this api endpoint!"
            ];
            echo json_encode($data);
            exit();
        }
        
        if($decoded->exp <= time()){
            http_response_code(400);
            $data = [
                "error" => "Bad request. Invalid token!"
            ];
            echo json_encode($data);
            exit();
        }


    } catch (Exception $e) {
        // Invalid token or expired
        http_response_code(400);
            $data = [
                "error" => "Bad request. Invalid token"
            ];
            echo json_encode($data);
            exit();
    }
?>
