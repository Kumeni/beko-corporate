<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    require './db.php';
    require './db-operations.php';
    require './upload.php';
    require './send-email.php';
    require '../vendor/autoload.php'; // Include PHPMailer or use PHP mail() function

    
    function generateVerificationCode($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $code = '';
        $maxIndex = strlen($characters) -1;
        
        for($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, $maxIndex)];
        }
        
        return $code;
    }

/*function sendVerificationEmail($email, $code) {
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
}*/

// Handle authentication request
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if(isset($_POST['email'])){
            $email = $_POST['email'];
            $code = generateVerificationCode(6);
            $issuedAt = time();
            $expirationTime = $issuedAt + 1800; // Token valid for 1 hour
            
            //if email don't exist, create a user with the email
            /**
             * Get all users with the email;
             */
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

                $sustemUser = $systemUsers[0];
                $userId = $systemUser["id"];
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
                //update user verification code;
                $sql = "UPDATE users SET verification_code='$code', code_expiry='$expirationTime' WHERE id=$userId";
                update($host, $user, $password, $database, $sql);

                
            } else {
                //create user with verification code
                $sql = "INSERT INTO users(`email`, `verification_code`, `code_expiry`) VALUES('$email', '$code', '1200000')";
                $userId = create($host, $user, $password, $database, $sql);

                //Throw an error
                $data = [
                    "error" => "You're forbidden from admin panel. Contact super-admin@bekocorporatesolutions@gmail.com for further assistance.",
                ];

                http_response_code(403);
                echo json_encode($data);
                die();
            }
            
            $mail = new PHPMailer(true);

            $name = $email;
            //$email = ;
    
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'mail.bekocorporatesolutions.com:465'; // SMTP server address : The smtp test host server address
            $mail->SMTPAuth = true;
            $mail->Username = 'bekocorp'; // SMTP username: Always replace this with the business gmail address for authenticity
            $mail->Password = 'JubY7R;5Gc86n['; // SMTP password
            $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 25; // TCP port to connect to
    
            // Sender and recipient settings
            $mail->setFrom('super-admin@bekocorporatesolutions.com', 'Beko Corporate Solutions Admin'); // Sender's email and name
            $mail->addAddress($email, $name); // Recipient's email and name
            $mail->addReplyTo('super-admin@bekocorporatesolutions.com', 'Reply To'); //The email option for customers to reply to
    
            // Email content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Login Code'; //The usual email subject
            $mail->Body    = "<div style='text-align: center;'><p>The login code is <br /><b>$code</b></p></div>"; //System auto generated message to users every time they send a message to us.
            $mail->AltBody = "The login code is " . $code;
    
            // Send email
            $mail->send();

            /*echo json_encode([
                'code' => $code
            ]);*/
            echo json_encode(
                [
                    'message' => "Login code sent to $email successfully.",
                ]);
        } else {
            /**
             * Throw a bad request error, say, email is required.
             */
        }
        
    } else {
        
    }
?>
