<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$input = fopen ("php://stdin","r");
echo "Rollback to (QA/PROD)?";
$input_clean = trim(fgets($input));

if($input_clean == "QA"){
	$q = "QA_ROLLBACK";
} else if ($input_clean == "PROD"){
	$q = "PROD_ROLLBACK";
};

$channel->exchange_declare($q, 'fanout', false, false, false);
#$channel->queue_declare($q, false, false, false, false);

include 'mysqlconnect.php';

$sql = "SELECT bundle_name FROM bundle WHERE state = 'PASS' ORDER BY created_date DESC LIMIT 1;";

$result = $mydb->query($sql);

// Fetch the result
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); 
    $bundle_name = $row['bundle_name'];
    echo "Most recent 'PASS' bundle name: " . $row['bundle_name'];
} else {
    echo "No results found";
};

$msg = new AMQPMessage($bundle_name);
$channel->basic_publish($msg, $q);

echo "\n ~ Sent to $input_clean\n";

$channel->close();
$connection->close();

