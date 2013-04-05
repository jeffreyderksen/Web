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

		$queryString = $this->getUpdateString($columns);
		
		$query = 'UPDATE '. $tabel . ' SET ' . $queryString . ' WHERE ' . $columns[0] . '=' . $id;
		$keys = $this->getParamArray($page);
		$param = $this->getUpdateParam($keys, $columns);
		echo $query;
		
		$this->databaseHandler->executeQuery($query, $param);

	}
	
	public function newRow($page)
	{
		$tabel = $this->getTabel($page);
		$columns = $this->getColumns($page);
		
		//content_menu,content_title.....
		$columnString = $this->getColumnsString($columns, false);
		
		//'key' => ''
		$keys = $this->getParamArray($page);
		//:content_menu, :content_title
		$valueString = $this->getValuesString($this->getColumns($page));
		
		//vul array met keys en values
		$param = $this->getUpdateParam($keys, $columns);
		
		echo '<pre>';
		print_r($param);
		echo '</pre>';
		
		$query = 'INSERT into '. $tabel .' ('. $columnString .') VALUES('. $valueString .')';
		echo $query;
		
		$this->databaseHandler->executeQuery($query, $param);
	}
	
	public function deleteRow($page, $id)
	{
		if(empty($id))
			return;
		
		$tabel = $this->getTabel($page);
		$columns = $this->getColumns($page);
		
		//execute
		$query = 'DELETE FROM '. $tabel . ' WHERE ' . $columns[0] . '=:id';
		$param = array(':id' => $id);
		echo $query . "</br>";
		$this->databaseHandler->executeQuery($query,$param);
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
	
	public function getParamArray($page)
	{
		switch($page)
		{
			case 'manage_pages' : return array(':content_menu' => '', ':content_title' => '', ':content_header' => '', ':content_footer' => '', ':content_text' => '');
			case 'manage_accounts' : return array(':username' => '', ':password' => '', ':email' => '', ':admin' => '');
		}
	}
	
	private function getColumnsString($columns, $primarykey)
	{
		$columnsString = '';
		for($i = 0; $i < count($columns); $i++)
		{
			//geen primarykey(id)
			if($i != 0 && !$primarykey)
				$columnsString .= $columns[$i];
			//wel.....
			else if($i == 0 && $primarykey)
				$columnsString .= $columns[$i];
			if($i != count($columns)-1 && $i != 0)
				$columnsString .= ',';
			else if($i != count($columns)-1 && $primarykey)
				$columnsString .= ',';
		}
		return $columnsString;
	}
	
	private function getValuesString($array)
	{
		$values = '';
		//niet met 0 beginnen! Dat is het ID(AUTO INCREMENT)
		for($i = 1; $i < count($array); $i++)
		{			
			$values .= ':' . $array[$i];
			if($i != count($array)-1)
				$values .= ',';
		}
		return $values;
	}
	
	private function getUpdateValues($columns)
	{
		$valuesString = '';
		
		for($i = 1; $i < count($columns); $i++)
		{
			$value = $this->getFormVariable($columns[$i]);
			$valuesString .= $value;
			
			if($i != count($columns)-1)
				$valuesString .= ',';
		}

		return $valuesString;
	}
	
	private function getUpdateString($columns)
	{
		//UPDATE table_name SET column1=value, column2=value2,...
		$queryString = '';
		
		for($i = 1; $i < count($columns); $i++)
		{
			$value = ':' . $columns[$i];
			$queryString .= $columns[$i]. '=' . $value;
			
			if($i != count($columns)-1)
				$queryString .= ',';
		}
		
		return $queryString;
	}
	
	private function getUpdateParam($keysArray, $columns)
	{
		$keys = array_keys($keysArray);
		
		//remove first column(ID)!
		unset($columns[0]);
		$columns = array_values($columns);
		
		for($i = 0; $i < count($keysArray); $i++)
		{
			$value = $this->getFormVariable($columns[$i]);
			if(!empty($value))
				$keysArray[$keys[$i]] = $value;			
			else
				$keysArray[$keys[$i]] = 'leeg';
		}
		
		return $keysArray;
	}
}