<?php

    function sendEmail($mail, $sender, $senderName, $recepient, $recepientName,  $replyTo, $message, $subject){
        
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'mail.sirneil.com:465'; // SMTP server address : The smtp test host server address
        $mail->SMTPAuth = true;
        $mail->Username = 'sirneilc'; // SMTP username: Always replace this with the business gmail address for authenticity
        $mail->Password = '0geSE(31Yja[8G'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25; // TCP port to connect to

        // Sender and recipient settings
        $mail->setFrom($sender, $senderName); // Sender's email and name
        $mail->addAddress($recepient, $recepientName); // Recipient's email and name
        $mail->addReplyTo($replyTo, 'Reply To'); //The email option for customers to reply to
       
        // Email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject; //The usual email subject
        $mail->Body    = $message; //System auto generated message to users every time they send a message to us.
        $mail->AltBody = $message;

        // Send email
        $mail->send();
    }
?>