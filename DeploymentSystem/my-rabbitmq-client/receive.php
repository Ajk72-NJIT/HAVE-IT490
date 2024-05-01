<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('10.211.55.6', 5672, 'dmz', 'dmz');
$channel = $connection->channel();

$channel->queue_declare('QA', false, false, false, false);

echo " ... Waiting for messages\n";

$callback = function($msg) {
  echo " ~ Received ", $msg->body, "\n";
  $copyScript = '/home/parallels/HAVE-IT490/DeploymentSystem/DMZcopy.sh';
  $output = shell_exec($copyScript);
  echo "$output";
};

$channel->basic_consume('QA', '', false, true, false, false, $callback);

while($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
