<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$input = fopen ("php://stdin","r");
$input_clean = trim(fgets($input));

if($input_clean == "QA"){
	$q = "QA";
} else if ($input_clean == "PROD"){
	$q = "PROD";
};

$channel->queue_declare($q, false, false, false, false);

$msg = new AMQPMessage($input_clean);
$channel->basic_publish($msg, '', $q);

echo " ~ Sent to $input_clean\n";

$channel->close();
$connection->close();

