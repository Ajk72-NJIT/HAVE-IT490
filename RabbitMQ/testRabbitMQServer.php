#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function goDatabase($request)
{	
	include "testRabbitMQClient (copy).php"; 
	return $response;
}

function goDMZ($username)
{

}

function requestProcessor($request)
{
  echo "=======================================".PHP_EOL;
  echo "received request".PHP_EOL;
  echo "---------------------------------------\n";
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['destination'])
  {
    case "database":
	    return goDatabase($request);
	    break;
  }
  return $finalResponse;
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");
echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

