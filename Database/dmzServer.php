#!/usr/bin/php
<?php

require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;



function getRecipes($ingredients)
	{
	$apiKey = 'af935f674c7243b59e152b70834a8dd3';
	
	$searchUrl = "https://api.spoonacular.com/recipes/findByIngredients?ingredients={$ingredients}&apiKey={$apiKey}&number=5";
	
	$curl = curl_init($searchUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$searchResponse = curl_exec($curl);
	curl_close($curl);
	$recipes = json_decode($searchResponse, true);
	
	$formattedRecipes = [];
	
	foreach ($recipes as $recipe) {
	    // Second API call: Get Analyzed Recipe Instructions
	    $instructionsUrl = "https://api.spoonacular.com/recipes/{$recipe['id']}/analyzedInstructions?apiKey={$apiKey}";
	    curl_setopt_array($curl = curl_init($instructionsUrl), [
	        CURLOPT_RETURNTRANSFER => true,
	    ]);
	    $instructionsResponse = curl_exec($curl);
	    curl_close($curl);
	    $instructionsData = json_decode($instructionsResponse, true);
	
	    // Extracting steps and equipment from the instructions
	    $formattedSteps = [];
	    foreach ($instructionsData as $block) {
	        foreach ($block['steps'] as $step) {
	            $stepDetails = [
	                'number' => $step['number'],
	                'step' => $step['step'],
	            ];
	
	            $formattedSteps[] = $stepDetails;
	        }
	    }
	
	    // Format missed ingredients
	    $missedIngredients = array_map(function ($ingredient) {
	        return [
	            'ingredient_id' => $ingredient['id'],
	            'ingredient_name' => $ingredient['name'],
	            'unit' => $ingredient['unitShort'],
	            'original_unit' => $ingredient['original']
	        ];
	    }, $recipe['missedIngredients'] ?? []);
	
	    // Format used (available) ingredients
	    $availableIngredients = array_map(function ($ingredient) {
	        return [
	            'ingredient_id' => $ingredient['id'],
	            'ingredient_name' => $ingredient['name'],
	            'unit' => $ingredient['unitShort'],
	            'original_unit' => $ingredient['original']
	        ];
	    }, $recipe['usedIngredients'] ?? []);
	
	    $formattedRecipe = [
	        'recipe_id' => $recipe['id'],
	        'missed_ingredients' => $missedIngredients,
	        'available_ingredients' => $availableIngredients,
	        'recipe_instructions' => $formattedSteps
	    ];
	    
	    
	
	    //$formattedRecipes[] = $formattedRecipe;
	    
	    $formattedRecipes = array();
	    $formattedRecipes['type'] = "push recipes";
	    $formattedRecipes['destination'] = "database";
	    $formattedRecipes['recipes'] = $formattedRecipe;
	}
	
	//file_put_contents('/home/parallels/formatted_recipes_combined_with_details.json', json_encode($formattedRecipes, JSON_PRETTY_PRINT));
	//echo "Formatted recipes with detailed instructions have been saved.\n";
		
	 return $formattedRecipes;
}


$connection = new AMQPStreamConnection('172.23.62.86', 5672, 'test', 'test', 'testHost');
$channel = $connection->channel();

$channel->queue_declare('dataQueue', false, true, false, false);

echo "\ntestRabbitMQServer BEGIN".PHP_EOL;

$callback = function ($msg) use ($channel) {
    echo "======================================\n";
    echo "recieved request\n";
    echo "--------------------------------------\n\n";
    $request = json_decode($msg->body, true);
    var_dump($request);
    $response = '';

    try {
        switch ($request['type']) {
            case "get recipes":
                $response = getRecipes($request['ingredients']);
                break;
            case "get similar recipes":
                $response = getSimilarRecipes($request['recipe_id']);
                break;
            default:
                $response = ['success' => false, 'message' => "Request type not handled"];
                break;
        }
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => $e->getMessage()];
    }
    
    

    $responseMsg = new AMQPMessage(
        json_encode($response),
        array('correlation_id' => $msg->get('correlation_id'))
    );

    $channel->basic_publish($responseMsg, '', $msg->get('reply_to'));
    echo "Sending Response\n";
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('dataQueue', '', false, true, false, false, $callback);

try {
    while (true) {
        $channel->wait();
    }
} catch (Exception $e) {
    echo 'An error occurred: ', $e->getMessage(), "\n";
    $channel->close();
    $connection->close();
}

$channel->close();
$connection->close();
