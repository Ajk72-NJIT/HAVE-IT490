#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function goDatabase($request)
{	
	$queue = 'dataQueue';
	include "testRabbitMQClient (copy).php"; 

	return requestProcessor($response);
}

function goDMZ($request)
{
	$queue = 'dmzQueue';
	include "testRabbitMQClient (copy).php"; 
	return requestProcessor($response);
}


function requestProcessor($request)
{
  if ($request['destination'] != 'frontend'){
  echo "=======================================".PHP_EOL;
  echo "received request".PHP_EOL;
  echo "---------------------------------------\n";
  var_dump($request);
  }
  switch ($request['destination'])
  {
    case "database":
	    return goDatabase($request);
	    break;
    case "dmz":
    	    return goDMZ($request);
    	    break;
    case "frontend":
    	    return $request;
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

