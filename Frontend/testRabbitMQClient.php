#!/usr/bin/php
<?php
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

$request = array();
$request['type'] = "Login";
//$request['username'] = "testUsername";
//$request['password'] = "testPassword";
$request['username'] = $_POST["username"];
$request['password'] = $_POST["password"];

$request['message'] = 'test';
$response = $client->send_request($request);
//$response = $client->publish($request);
if ($response['message'] === "Account found"){
	//	echo "success";
	//var_dump($response);
	header("Location: success.html");
}else{
	//	echo "fail";
	//var_dump($response);
	header('Location: index.html');
}




