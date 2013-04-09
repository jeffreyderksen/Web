<?php
include_once('../include/database_handler.class.php');
include_once('framework_backend.class.php');

class ActionHandler extends FrameWorkBackend
{
	public function updateRow($page, $id)
	{
		if(empty($id))
			return;
		
		$tabel = $this->getTabel($page);
		$columns = $this->getColumns($tabel);

		$queryString = $this->getUpdateString($columns);
		
		$query = 'UPDATE '. $tabel . ' SET ' . $queryString . ' WHERE ' . $columns[0] . '=' . $id;
		$keys = $this->getParamArray($page);
		$param = $this->getUpdateParam($keys, $columns);
		
		if($this->databaseHandler->executeQuery($query, $param))
			return true;
		else
			return false;
	}
	
	public function newRow($page)
	{
		$tabel = $this->getTabel($page);
		$columns = $this->getColumns($tabel);
		
		//content_menu,content_title.....
		$columnString = $this->getColumnsString($columns, false);
		
		//'key' => ''
		$keys = $this->getParamArray($page);
		//:content_menu, :content_title
		$valueString = $this->getValuesString($this->getColumns($tabel));
		
		//vul array met keys en values
		$param = $this->getUpdateParam($keys, $columns);
		
		echo '<pre>';
		print_r($param);
		echo '</pre>';
		
		$query = 'INSERT into '. $tabel .' ('. $columnString .') VALUES('. $valueString .')';
		echo $query;
		
		if($this->databaseHandler->executeQuery($query, $param))
			return true;
		else
			return false;
	}
	
	public function deleteRow($page, $id)
	{
		if(empty($id))
			return;
		
		$tabel = $this->getTabel($page);
		$columns = $this->getColumns($tabel);
		
		//execute
		$query = 'DELETE FROM '. $tabel . ' WHERE ' . $columns[0] . '=:id';
		$param = array(':id' => $id);
		echo $query . "</br>";
		
		if($this->databaseHandler->executeQuery($query, $param))
			return true;
		else
			return false;
	}
	
	public function getTabel($page)
	{
		switch($page)
		{
			case 'manage_pages' : return 'content';
			case 'manage_members' : return 'member';
			case 'manage_admins' : return 'admin_member';
		}
	}
	
	public function getColumns($tabel)
	{
		switch($tabel)
		{
			case 'content' : return array('content_id', 'content_menu', 'content_title', 'content_text');
			case 'member' : return array('member_id', 'member_fname', 'member_lname', 'member_uname', 'member_pass','member_email');
			case 'admin_member' : return array('admin_id', 'admin_fname', 'admin_lname', 'admin_uname', 'admin_pass','admin_email');
		}
	}
	
	public function getParamArray($page)
	{
		switch($page)
		{
			case 'manage_pages' : return array(':content_menu' => '', ':content_title' => '', ':content_text' => '');
			case 'manage_accounts' : return array(':member_fname' => '', ':member_lname' => '', ':member_uname' => '', ':member_pass' => '', ':member_email' => '');
			case 'manage_admins' : return array(':admin_fname' => '', ':admin_lname' => '', ':admin_uname' => '', ':admin_pass' => '', ':admin_email' => '');
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
			if($columns[$i] == 'admin_pass' || $columns[$i] == 'member_pass')
				$value = sha1($this->getFormVariable($columns[$i]));
			else
				$value = $this->getFormVariable($columns[$i]);
			if(!empty($value))
				$keysArray[$keys[$i]] = $value;			
			else
				$keysArray[$keys[$i]] = 'leeg';
		}
		
		return $keysArray;
	}
}