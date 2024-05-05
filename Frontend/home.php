<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Digital Fridge Login</h1>
        <form action="testRabbitMQClient.php" method="POST" class="login-form">
            <label for="login-username">Username</label>
            <input type="text" id="login-username" name="username" class="input-field">
            <label for="login-password">Password</label>
            <input type="password" id="login-password" name="password" class="input-field">
            <button type="submit" name="login">Login</button>
        </form>
        <h4>Don't have an account? Register!</h4>
        <form action="testRabbitMQClient.php" method="POST" class="register-form">
            <label for="register-username">Username</label>
            <input type="text" id="register-username" name="username" class="input-field">
            <label for="register-password">Password</label>
            <input type="password" id="register-password" name="password" class="input-field">
            <label for="register-cpassword">Confirm Password</label>
            <input type="password" id="register-cpassword" name="cpassword" class="input-field">
            <label for="register-email">Email</label>
            <input type="email" id="register-email" name="email" class="input-field">
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>
</html>
