<?php
    setcookie('jwt', '', time() - 3600, '/'); // Expire the cookie
    header("Location: login.php");
    exit();
?>