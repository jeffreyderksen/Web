<?php
include_once('../include/database_handler.class.php');

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
		$result = $databaseHandler->executeQuery($query);
		
		if(is_array($result))
			$this->config = $result[0];
		else
			echo '<p style="color:red">Error could not load default page settings.</p>';
	}
	
	public function getCharset()
	{
		return $this->config['config_metacharset'];
	}
	
	public function getMetaKeywords()
	{
		return $this->config['config_metakeywords'];
	}
	
	public function getMetaDescription()
	{
		return $this->config['config_metadescription'];
	}
	
	public function getAuthor()
	{
		return $this->config['config_author'];
	}
	
	public function getCssFile()
	{
		return $this->config['config_css'];
	}
}