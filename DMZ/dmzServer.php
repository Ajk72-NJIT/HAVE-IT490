#!/usr/bin/php
<?php

require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$apiKey = 'af935f674c7243b59e152b70834a8dd3';

function getSimilarRecipes($recipe_id)
	{
	$searchUrl = "https://api.spoonacular.com/recipes/$recipe_id/similar&number=3";
	$curl = curl_init($searchUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$searchResponse = curl_exec($curl);
	curl_close($curl);
	$similarRecipes = json_decode($searchResponse, true);

	$similarRecipesDetails = [];

	foreach ($similarRecipes as $similarRecipe) {

		$instructionsUrl = "https://api.spoonacular.com/recipes/{$similarRecipe['id']}/analyzedInstructions?apiKey={$apiKey}";
		curl_setopt_array($curl = curl_init($instructionsUrl), [CURLOPT_RETURNTRANSFER => true]);
        	$instructionsResponse = curl_exec($curl);
        	$instructionsData = json_decode($instructionsResponse, true);
		
		$ingredientsUrl = "https://api.spoonacular.com/recipes/{$similarRecipe['id']}/ingredientWidget.json?apiKey={$apiKey}";
		curl_setopt_array($curl = curl_init($ingredientsUrl), [CURLOPT_RETURNTRANSFER => true]);
		$ingredientsResponse = curl_exec($curl);
        	$ingredientsData = json_decode($ingredientsResponse, true);

		$formattedSteps = [];
	        foreach ($instructionsData as $instruction) {
	            foreach ($instruction['steps'] as $step) {
	                $formattedSteps[] = [
	                    'number' => $step['number'],
	                    'step' => $step['step'],
			        ];
			}
		}
		$ingredients = [];
	        foreach ($ingredientsData['ingredients'] as $ingredient) {
		        $ingredients[] = [
		                //'ingredient_id' => $ingredient['id'],
		                'ingredient_name' => $ingredient['name'],
		                'amount' => $ingredient['amount']['us']['value'],
				'unit' => $ingredient['amount']['us']['unit']
		        ];
	            }

		$formattedSimilarRecipe = [
	            'recipe_id' => $recipeId,
	            'ingredients' => $ingredients,
	            'recipe_instructions' => $formattedSteps
	        	];

	$formattedSimilarRecipes = array();
	$formattedSimilarRecipes['type'] = "push recipes";
	$formattedSimilarRecipes['destination'] = "database";
	$formattedSimilarRecipes['similarRecipes'] = $formattedSimilarRecipe;
	}
	return $formattedSimilarRecipes;
	}

function getIngredients($ingredient_name)
	{
	
	}

function getRecipes($ingredientsArray)
	{
	$ingredients = implode(',', $ingredientsArray);
	$searchUrl = "https://api.spoonacular.com/recipes/findByIngredients?ingredients={$ingredients}&apiKey={$apiKey}&number=5";
	$curl = curl_init($searchUrl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$searchResponse = curl_exec($curl);
	curl_close($curl);
	$recipes = json_decode($searchResponse, true);
	
	$formattedRecipes = [];
	
	foreach ($recipes as $recipe) {
	    $instructionsUrl = "https://api.spoonacular.com/recipes/{$recipe['id']}/analyzedInstructions?apiKey={$apiKey}";
	    curl_setopt_array($curl = curl_init($instructionsUrl), [
	        CURLOPT_RETURNTRANSFER => true,
	    ]);
	    $instructionsResponse = curl_exec($curl);
	    curl_close($curl);
	    $instructionsData = json_decode($instructionsResponse, true);
	
	    $formattedSteps = [];
	    foreach ($instructionsSteps as $ins) {
	        foreach ($ins['steps'] as $step) {
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
            case "get ingredients":
                $response = getIngredients($request['ingredients']);
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
