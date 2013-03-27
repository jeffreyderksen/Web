<?php
include_once('database_handler.class.php');
include_once('framework_backend.class.php');

class ActionHandler extends FrameWorkBackend
{
	
	public function newRow($page)
	{
		$tabel = $this->getTabel($page);
			
		$array = $this->getColumns($page);
		$columnsString = $this->getColumnsString($array);
		$values = $this->getValuesString($array);
		
		$query = 'INSERT INTO content (' . $columnsString . ') VALUES ('. $values .')';
		echo $query . "</br>";
		$this->databaseHandler->executeQuery($query);
	}
	
	public function deleteRow($page, $id)
	{
		if(empty($id))
			return;
		
		$tabel = $this->getTabel($page);
		
		//execute
		$query = 'DELETE FROM '. $tabel . ' WHERE ' . $tabel . '_id' . '=' . $id;
		echo $query . "</br>";
		$this->databaseHandler->executeQuery($query);
	}
	
	public function getTabel($page)
	{
		switch($page)
		{
			case 'manage_pages' : return 'content';
			case 'manage_menu' : return 'menu';
			case 'manage_accounts' : return 'users';
		}
	}
	
	public function getColumns($page)
	{
		switch($page)
		{
			case 'manage_pages' : return array('content_id', 'content_type', 'content_title', 'content_header', 'content_footer', 'content_text');
			case 'manage_menu' : return array('menu_id', 'page_type', 'menu_item');
			case 'manage_accounts' : return array('user_id', 'username', 'password', 'email', 'admin');
		}
	}
	
	private function getColumnsString($array)
	{
		$columns = '';
		for($i = 0; $i < count($array); $i++)
		{
			if($i != 0)
				$columns .= $array[$i];
			if($i != count($array)-1 && $i != 0)
				$columns .= ',';
		}
		return $columns;
	}
	
	private function getValuesString($array)
	{
		$values = '';
		for($i = 0; $i < count($array); $i++)
		{
			if($i != 0)
				$values .= '0';
			if($i != count($array)-1 && $i != 0)
				$values .= ',';
		}
		return $values;
	}
}