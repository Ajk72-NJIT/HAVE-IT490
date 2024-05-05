<?php

session_start();

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	
	
$request = array();
$request['type'] = "getFridge";
$request['destination'] = "database";
$request['username'] = $_SESSION['name'];
$request['token'] = $_SESSION['token'];
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");		
$response = $client->send_request($request);
$fridgeArray = $response['message'];

	
if ($response['authed'] == "not authed"){
	header('Location: home.php');
}else{	
}
?>



<html>
<head>
    <title>Contents</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
	<h1 class="title">Fridge Contents</h1>
	<?php
		
		foreach($fridgeArray as $item){	
			$ingredient = $item[0];
			$quantity = $item[1];
			echo "<a class = list>$ingredient </a>";
			echo "<br> </br>";
		}

        ?>
        <form action="testRabbitMQClient.php" method="POST" class="login-form">
        	<button type="submit" name = "landing" class="submit-button">Return to Landing</button>
        </form>
    </div> 
</body>
</html>
