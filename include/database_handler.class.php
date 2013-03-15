<?php
class DatabaseHandler
{
	private $connection;
	
	public function __construct($host, $user, $pass, $database)
	{
		$connection = new mysqli($host, $user, $pass, $database);
	}
}