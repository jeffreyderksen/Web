<?php
include_once('database_handler.class.php');
include_once('framework_backend.class.php');

class ActionHandler extends FrameWorkBackend
{
	public function updateRow($page, $id)
	{
		if(empty($id))
			return;
		
		$tabel = $this->getTabel($page);
		$columns = $this->getColumns($page);
		$values = $this->getUpdateValuesArray($columns);
		
		$query = 'UPDATE '. $tabel . ' SET ';
		for($i = 0; $i < count($values); $i++)
		{
			//SET column1=value, column2=value2,...WHERE some_column=some_value
			$query .= $columns[$i] . '= "'. $values[$i] . '"';
			if($i != count($values)-1)
				$query .= ', ';
		}
		$query .= ' WHERE '. $columns[0] .'="'. $id .'"';
		echo $query . '</br>';
		$this->databaseHandler->updateQuery($query);
	}
	
	public function newRow($page)
	{
		$tabel = $this->getTabel($page);
			
		$array = $this->getColumns($page);
		$columnsString = $this->getColumnsString($array,false);
		$values = $this->getValuesString($array);
		
		$query = 'INSERT INTO '. $tabel .' (' . $columnsString . ') VALUES ('. $values .')';
		echo $query . "</br>";
		$this->databaseHandler->updateQuery($query);
	}
	
	public function deleteRow($page, $id)
	{
		if(empty($id))
			return;
		
		$tabel = $this->getTabel($page);
		$columns = $this->getColumns($page);
		
		//execute
		$query = 'DELETE FROM '. $tabel . ' WHERE ' . $columns[0] . '=' . $id;
		echo $query . "</br>";
		$this->databaseHandler->executeQuery($query, false);
	}
	
	public function getTabel($page)
	{
		switch($page)
		{
			case 'manage_pages' : return 'content';
			case 'manage_accounts' : return 'users';
		}
	}
	
	public function getColumns($page)
	{
		switch($page)
		{
			case 'manage_pages' : return array('content_id', 'content_menu', 'content_title', 'content_header', 'content_footer', 'content_text');
			case 'manage_accounts' : return array('user_id', 'username', 'password', 'email', 'admin');
		}
	}
	
	private function getColumnsString($array, $primarykey)
	{
		$columns = '';
		for($i = 0; $i < count($array); $i++)
		{
			//geen primarykey(id)
			if($i != 0 && !$primarykey)
				$columns .= $array[$i];
			//wel.....
			else if($i == 0 && $primarykey)
				$columns .= $array[$i];
			if($i != count($array)-1 && $i != 0)
				$columns .= ',';
			else if($i != count($array)-1 && $primarykey)
				$columns .= ',';
		}
		return $columns;
	}
	
	private function getValuesString($array)
	{
		$values = '';
		//niet met 0 beginnen! Dat is het ID(AUTO INCREMENT)
		for($i = 1; $i < count($array); $i++)
		{			
			$values .= '0';
			if($i != count($array)-1)
				$values .= ',';
		}
		return $values;
	}
	
	private function getUpdateValues($columns)
	{
		$valuesString = '';
		
		for($i = 0; $i < count($columns); $i++)
		{
			$value = $this->getFormVariable($columns[$i]);
			$valuesString .= $value;
			
			if($i != count($columns)-1)
				$valuesString .= ',';
		}

		return $valuesString;
	}
	
	private function getUpdateValuesArray($columns)
	{
		$values = array();
	
		for($i = 0; $i < count($columns); $i++)
		{
			if($columns[$i] == 'password')
				array_push($values, sha1($this->getFormVariable($columns[$i])));
			else
				array_push($values, $this->getFormVariable($columns[$i]));
		}
	
		return $values;
	}
	
	
}