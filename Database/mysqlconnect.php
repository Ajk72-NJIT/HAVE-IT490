#!/usr/bin/php
<?php

$mydb = new mysqli('127.0.0.1','test','test','testdb');

if ($mydb->errno != 0)
{
	echo "failed to connect to database: ". $mydb->error . PHP_EOL;
	exit(0);
}

//echo "successfully connected to database".PHP_EOL;

?>
