<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" type="text/css" href="./login.css" />

    <title>Login | Beko Corporate Solutions</title>
</head>
<body>
    <section class="login-form-container">
        <img src="../../assets/icons/Beko Corporate Solutions Logo BLUE.png" alt="Beko Corporate Solutions Logo" />
        <h1>Admin Login</h1>
        <form class="login-form">
            <label> Email </label>
            <div>
                <input onchange="handleEmailChange(event)" type="email" placeholder="email@example.com"/>
                <span name="email-error" id="email-error" class="error"></span>
            </div>

            <div class="get-code-container">
                <button onclick="getCode(event)">Get Code</button>
                <span style="display: none;" class="success" id="code-sent-successfully">Check your email, email, for code</span>
                <span style="display: none;" id="code-not-sent" class="error">Something went wrong, try again later</span>
            </div>

            <label> Code </label>
            <div>
                <input id="login-code" type="text" placeholder="ECODEE" onchange="handleCodeChange(event)"/>
                <span name="error" id="code-error" class=error></span>
            </div>
            
            <input type="submit" value="LOGIN" onclick="login(event)"/>
        </form>
    </section>

    <script src="login.js"></script>
</body>
</html>