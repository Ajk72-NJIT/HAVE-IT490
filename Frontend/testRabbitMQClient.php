#!/usr/bin/php
<?php
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

var_dump($_POST);

#$_POST['username'] = 'cobra';
#$_POST['password'] = 'cobra';
#$_POST['landing'] = '';

if (isset($_POST['dislike'])){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	$request['type'] = "rating";
	$request['destination'] = "database";
	$request['username'] = $_SESSION['name'];
	$request['token'] = $_SESSION['token'];
	$request['recipeName'] = $_POST['titleRating'];
	$request['rating'] = "dislike";
	
	$response = $client->send_request($request);
	
	if ($response['authed'] == "not authed"){
	header('Location: home.php');
	}
	
	if ($response['message'] === "success"){
		header('Location: landing.php');
	}else{
		echo "something went wrong";
	}
	
}

if (isset($_POST['like'])){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	$request['type'] = "rating";
	$request['destination'] = "database";
	$request['username'] = $_SESSION['name'];
	$request['token'] = $_SESSION['token'];
	$request['recipeName'] = $_POST['titleRating'];
	$request['rating'] = "like";
	
	$response = $client->send_request($request);
	if ($response['authed'] == "not authed"){
	header('Location: home.php');
	}
	if ($response['message'] === "success"){
		header('Location: landing.php');
	}else{
		echo "something went wrong";
	}
	
	
}

if (isset($_POST['getRecipes'])){
	if ($response['authed'] == "not authed"){
	header('Location: home.php');
	}
	header('Location: Recipes.php');
}

if (isset($_POST['landing'])){
	if ($response['authed'] == "not authed"){
	header('Location: home.php');
	}
	header('Location: landing.php');
}

if (isset($_POST['openFridge'])){
	if ($response['authed'] == "not authed"){
	header('Location: home.php');
	}
	header('Location: fridge.php');
}

if (isset($_POST['addFridge'])){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	$request['type'] = "addIngredient";
	$request['destination'] = "database";
	$request['username'] = $_SESSION['name'];
	$request['token'] = $_SESSION['token'];	
	$request['ingredient'] = $_POST['ingredientInput'];
	$request['quantity'] = 1;
	
	$response = $client->send_request($request);
	
	var_dump($response);
	
	if ($response['authed'] == "not authed"){
	header('Location: home.php');
	}
	
	if ($response['message'] === "success"){
		header('Location: landing.php');
	}else{
		echo "something went wrong";
	}
	
	
}

if (isset($_POST['login'])){
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

	$request = array();
	$request['type'] = "Login";
	$request['destination'] = "database";
	$request['username'] = $_POST["username"];
	$request['password'] = $_POST["password"];
	$request['message'] = 'Login request';
	
	$response = $client->send_request($request);
	
	var_dump($response);
	
	if ($response['message'] === "Account found"){
		$_SESSION["name"] = $response['username'];
		$_SESSION["token"] = $response['token'];
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
		
		var_dump($request);
	
		$response = $client->send_request($request);
		
		var_dump($response);
	
		if ($response['message'] === "success"){
			header('Location: home.php');
		}else{
			echo "something went wrong";
		}
		
	}else{
		header('Location: home.php');
	}
}


