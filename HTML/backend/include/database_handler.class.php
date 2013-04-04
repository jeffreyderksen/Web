<?php
class DatabaseHandler
{
	private $connection;
	
	private $menu_items = array(
			'page_type' => array(),
			'menu_item' => array(),
	);
	
	public function openConnection($host, $user, $pass)
	{
		try
		{
			$this->connection = new PDO($host, $user, $pass);
		}
		catch(Exception $e)
		{
			return false;
		}
		return true;
	}
	
	public function executeQuery($query, $param=array())
	{
		$st = $this->connection->prepare($query);
		
		if($st->errorCode() == '')
			$st->execute($param);
		else
			return 'Error: query could not be executed.';

		//echo '<pre>';
		//print_r($menuItems);
		//echo '</pre>';
		
		return $st->fetchAll();
	}
}