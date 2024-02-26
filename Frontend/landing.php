<?php
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	
$request = array();
$request['type'] = "Auth";
$request['destination'] = "database";
$request['username'] = $_SESSION['name'];
$request['message'] = 'Authorizing Token'; 

$response = $client->send_request($request);
if ($response['message'] != "authed"){
	header('Location: home.php');
}
?>

<html>
<h1>Landing</h1>
<body>

<?php
echo 'welcome ';
echo $_SESSION['name'];
echo '!';
?>

<form action="testRabbitMQClient.php" method="POST">
	<button type='submit' name="logout">logout</button>
</form>
</body>
</html>
