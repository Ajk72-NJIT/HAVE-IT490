<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('10.211.55.6', 5672, 'fe', 'fe');
$channel = $connection->channel();

$q = 'FE.RB';

$channel->queue_declare($q, false, false, false, false);

$channel->queue_bind($q, 'PROD_ROLLBACK');

echo " ... Waiting for messages\n";

$callback = function($msg) {
  echo " ~ Received ", $msg->body, "\n";
  $bundle_name = $msg->body;
  $copyScript = "/home/parallels/FErollback.sh {$bundle_name}";
  $output = shell_exec($copyScript);
  echo "$output";
};

$channel->basic_consume($q, '', false, true, false, false, $callback);

while($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
