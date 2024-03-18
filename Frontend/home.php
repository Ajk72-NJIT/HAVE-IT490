<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Digital Fridge Login</h1>
        <form action="testRabbitMQClient.php" method="POST" class="login-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="input-field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="input-field">
            <button type="submit" name="login" class="submit-button">Login</button>
        </form>
        <h4>Don't have an account? Register!</h4>
        <form action="testRabbitMQClient.php" method="POST" class="register-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="input-field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="input-field">
            <label for="cpassword">Confirm Password</label>
            <input type="password" id="cpassword" name="cpassword" class="input-field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="input-field">
            <button type="submit" name="register" class="submit-button">Register</button>
        </form>
    </div>
</body>
</html>
