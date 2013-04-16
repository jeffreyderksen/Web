<?php
include_once('../include/database_handler.class.php');

/* Deze klas vraagt alle standaard instellingen van de website op uit de database zoals:
 * META tags, css, script enz..
 */

class Config
{
	private $config;
	
	public function __construct()
	{
		$databaseHandler = new DataBaseHandler();
		
		if(!$databaseHandler->openConnection('mysql:dbname=gametriangle;host=localhost', 'test', 'test'))
			echo '<p style="color: red">Error connecting to database.</p>';
		
		//query
		$query = 'SELECT * FROM admin_config';
		$result = $databaseHandler->executeQuery($query)->fetchAll();
		
		if(is_array($result))
			$this->config = $result[0];
		else
			echo '<p style="color:red">Error could not load default page settings.</p>';
	}
	
	/**
	 * @return stuurt een string terug die de charset bevat(UTF-8 ...)
	 */
	public function getCharset()
	{
		return $this->config['config_metacharset'];
	}
	
	/**
	 * @return stuurt een string terug die de auteur(s) bevat
	 */
	public function getAuthor()
	{
		return $this->config['config_author'];
	}
	
	/**
	 * @return stuurt een string terug naar de locatie van de CSS-file.
	 */
	public function getCssFile()
	{
		return $this->config['config_css'];
	}
}