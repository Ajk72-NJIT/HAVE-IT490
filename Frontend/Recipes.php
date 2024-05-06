<?php
session_start();

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

$request = array();
$request['type'] = "getFridgeRecipe";
$request['destination'] = "database";
$request['username'] = $_SESSION['name'];
$request['token'] = $_SESSION['token'];
$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");
$response = $client->send_request($request);

if ($response['authed'] == "not authed") {
    header('Location: home.php');
} else {
    $RecipeArray = $response['message']['recipes'];
}
?>

<html>

    <head>
        <title>Recipes</title>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <div class="recipe-container">
            <?php
            foreach ($RecipeArray as $recipe) {
                $title = $recipe['title'];
                $id = $recipe['recipe_id'];
                $missed = $recipe['missed_ingredients'];
                $used = $recipe['available_ingredients'];
                $instructions = $recipe['recipe_instructions'];

                echo '
                <div class="recipe-card">
                    <h2 class="recipe-title">' . $title . '</h2>
                    <div class="ingredients">
                        <h3 class="subtitle">Missing ingredients:</h3>';
                foreach ($missed as $miss) {
                    $missIngre = $miss['original_unit'];
                    echo '<p class="ingredient">' . $missIngre . '</p>';
                }

                echo '<h3 class="subtitle">Used ingredients:</h3>';
                foreach ($used as $use) {
                    $useIngre = $use['original_unit'];
                    echo '<p class="ingredient">' . $useIngre . '</p>';
                }

                echo '<h3 class="subtitle">Instructions:</h3>';
                foreach ($instructions as $instruction) {
                    $number = $instruction['number'];
                    $step = $instruction['step'];
                    echo '<p class="instruction">' . $number . '. ' . $step . '</p>';
                }

                echo '
                    </div>
                    <form action="testRabbitMQClient.php" method="POST" class="rating-form">
                        <input type="hidden" name="titleRating" value="' . $id . '">
                        <button type="submit" name="like" class="rating-button">Like</button>
                        <button type="submit" name="dislike" class="rating-button">Dislike</button>
                    </form>
                </div>';
            }
            ?>
        </div>
    </body>
</html>