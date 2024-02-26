#!/usr/bin/php
<?php
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

if (isset($_POST['login'])){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

	$request = array();
	$request['type'] = "Login";
	$request['destination'] = "database";
	$request['username'] = $_POST["username"];
	$request['password'] = $_POST["password"];

	$request['message'] = 'Login request';
	$response = $client->send_request($request);
	if ($response['message'] === "Account found"){
		$_SESSION["name"] = $response['username'];
		header("Location: landing.php");
	}else{
		header('Location: home.php');
	}
}

if (isset($_POST['logout'])){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	
	$request = array();
	$request['type'] = "Logout";
	$request['destination'] = "database";
	$request['username'] = $_SESSION['name'];
	$request['message'] = 'Log out request'; 
	
	$response = $client->send_request($request);
	
	if ($response['message'] === "success"){
		header('Location: home.php');
	}else{
		echo "something went wrong";
	}
}

if (isset($_POST['register'])){
	if ($_POST['password'] === $_POST['cpassword']){
		$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	
		$request = array();
		$request['type'] = "Register";
		$request['destination'] = "database";
		$request['username'] = $_POST["username"];
		$request['password'] = $_POST["password"];
		$request['email'] = $_POST["email"];

	
		$response = $client->send_request($request);
	
		if ($response['message'] === "success"){
			header('Location: home.php');
		}else{
			echo "something went wrong";
		}
		
	}else{
		header('Location: home.php');
	}
}

