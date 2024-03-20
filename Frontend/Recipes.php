<?php
session_start();

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	
	
$request = array();
$request['type'] = "getFridgeRecipe";
$request['destination'] = "database";
$request['username'] = $_SESSION['name'];
$request['token'] = $_SESSION['token'];
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");		
$response = $client->send_request($request);

	
if ($response['authed'] == "not authed"){
	header('Location: home.php');
}else{
$RecipeArray = $response['message']['recipes'];
//print_r($ingredientArray);
	
}
?>


<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <?php
    $recipeCount = 0;
    
    foreach ($RecipeArray as $recipe){
    $title = $recipe['title'];
    $id = $recipe['recipe_id'];
    $missed = $recipe['missed_ingredients'];
    $used = $recipe['available_ingredients'];
    $instructions = $recipe['recipe_instructions'];
    
    echo '
    <form action="testRabbitMQClient.php" method="POST" class="login-form">
    	<input type="hidden" id="titleRating" name="titleRating" value='.$id.'>
	<div class="container">
        <h1 class="title" id = "recipeTitle" name="recipeTitle">'.$title.'</h1>
	<b><p class="thing">Missing ingredients:</p></b>';
	
	
	foreach ($missed as $miss){
		$missIngre = $miss['original_unit'];
		echo '<p class="thing">'.$missIngre.'</p>';
	}
	
     echo '<b><p class="thing">Used ingredients:</p></b>';
     
     	foreach ($used as $use){
		$useIngre = $use['original_unit'];
		echo '<p class="thing">'.$useIngre.'</p>';
	}
	
     echo ' <br> </br>
     <b><p class="thing">Instructions:</p></b>';
     
        foreach ($instructions as $instruction){
		$number = $instruction['number'];
		$step = $instruction['step'];
		echo '<p class="thing">'.$number.'. '.$step.'</p>';
		//echo '<p class="thing">.</p>';
		//echo '<p class="thing">'.$step.'</p>';
	}
	
	
     echo'
            <button type="submit" name="like" class="submit-button">Like</button>
            <button type="submit" name="dislike" class="submit-button">Dislike</button>

    	</div>
    </form>
    ';
    }
    
    ?>	
    
    
    
</body>
</html>
