<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$input = fopen ("php://stdin","r");
echo "(QA/PROD)? ";
$input_clean = trim(fgets($input));

if($input_clean == "QA"){
	$q = "QA_PUSH";
} else if ($input_clean == "PROD"){
	$q = "PROD_PUSH";
};

fclose($input);

$version = fopen ("php://stdin","r");
echo "VERSION#: ";
$version_clean = trim(fgets($version));

$channel->exchange_declare($q, 'fanout', false, false, false);
#$channel->queue_declare($q, false, false, false, false);

if ($q == "PROD_PUSH"){
	include 'mysqlconnect.php';

	$sql = "SELECT state FROM bundle WHERE version = $version_clean;";

	$result = $mydb->query($sql);

	// Fetch the result
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc(); 
		$state = $row['state'];
		echo "State is $state. ";
	} else {
		echo "No results found";
	};
	
	if ($state == "FAIL" || $state == "NEW"){
		die("Only state 'PASS can be sent to PROD\n");
	}
};

$msg = new AMQPMessage($version_clean);
$channel->basic_publish($msg, $q);

echo " ~ Sent to $input_clean\n";

$channel->close();
$connection->close();

