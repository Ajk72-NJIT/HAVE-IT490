<html>
<h1>login page</h1>
<body>

<form action="testRabbitMQClient.php" method="POST">
	username:<input type="text" id='username' name='username'><br>
	password: <input type="password" id='password' name='password'><br>
	<button type='submit' name="login">Login</button>
</form>

<h4> or register: </h4>

<form action="testRabbitMQClient.php" method="POST">
	username:<input type="text" id='username' name='username'><br>
	password: <input type="text" id='password' name='password'><br>
	confirm password: <input type="text" id='cpassword' name='cpassword'><br>
	email: <input type="text" id='email' name='email'><br>
	<button type='submit' name="register">Register</button>
</form>
</body>
</html>
