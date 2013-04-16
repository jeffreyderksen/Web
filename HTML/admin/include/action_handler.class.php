<?php
include_once('../include/database_handler.class.php');
include_once('framework_backend.class.php');

/* Deze klas behandeld alle actie's die de administrator uitvoert op het backend van de website. */

class ActionHandler extends FrameWorkBackend
{
	/**
	 * Deze functie update rows in de databases.
	 * 
	 * @param $page - de pagina waar de actie uitgevoerd wordt.
	 * @param $id - het ID van de row die bewerkt word.
	 * @return boolean true or false. Uitgaande of de query succesvol uitgevoerd wordt of niet.
	 */
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
		
		//return succes of error en update logs..
		if($this->databaseHandler->executeQuery($query, $param))
		{
			$param = array( ':log_action' => 'Edit',
							':log_details' => 'Updated row'. $id . ' in table ' .$tabel,
							':log_who' => $_SESSION['ausername']
			);
			$query = 'INSERT INTO admin_logs (log_action, log_details, log_who) 
					VALUES(:log_action, :log_details, :log_who)';
			$this->databaseHandler->executeQuery($query, $param);
			return true;
		}
		else
			return false;
	}
	
	/**
	 * Deze functie maakt nieuwe rows aan in de tabellen van databases.
	 * 
	 * @param $id - het ID van de row die bewerkt word.
	 * @return boolean true or false. Uitgaande of de query succesvol uitgevoerd wordt of niet.
	 */
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
		
		$query = 'INSERT into '. $tabel .' ('. $columnString .') VALUES('. $valueString .')';
		
		//return succes of error en update logs..
		if($this->databaseHandler->executeQuery($query, $param))
		{
			$param = array( ':log_action' => 'New',
							':log_details' => 'Inserted new row in table ' .$tabel,
							':log_who' => $_SESSION['ausername']
			);
			$query = 'INSERT INTO admin_logs (log_action, log_details, log_who) 
					VALUES(:log_action, :log_details, :log_who)';
			$this->databaseHandler->executeQuery($query, $param);
			return true;
		}
		else
			return false;
	}
	
	/**
	 * Deze functie delete rows uit een tabel van de database.
	 * 
	 * @param $page - de pagina waar de actie uitgevoerd wordt.
	 * @param $id - het ID van de row die bewerkt word.
	 * @return boolean true or false. Uitgaande of de query succesvol uitgevoerd wordt of niet.
	 */
	public function deleteRow($page, $id)
	{
		if(empty($id))
			return;
		
		$tabel = $this->getTabel($page);
		$columns = $this->getColumns($tabel);
		
		//execute
		$query = 'DELETE FROM '. $tabel . ' WHERE ' . $columns[0] . '=:id';
		$param = array(':id' => $id);
		
		//return succes of error en update logs..
		if($this->databaseHandler->executeQuery($query, $param))
		{
			$param = array( ':log_action' => 'Delete',
							':log_details' => 'Deleted row '. $id . ' from table ' .$tabel,
							':log_who' => $_SESSION['ausername']
			);
			$query = 'INSERT INTO admin_logs (log_action, log_details, log_who) 
					VALUES(:log_action, :log_details, :log_who)';
			$this->databaseHandler->executeQuery($query, $param);
			return true;
		}
		else
			return false;
	}
	
	/**
	 * Deze functie returned een tabelnaam doormiddel van de 'page' variabele.
	 * @param $page - de pagina waar de actie uitgevoerd wordt.
	 * @return string - tabelnaam
	 */
	private function getTabel($page)
	{
		switch($page)
		{
			case 'manage_pages' : return 'content';
			case 'manage_members' : return 'member';
			case 'manage_admins' : return 'admin_member';
		}
	}
	
	/**
	 * Deze functie returned een array met de kolomnamen van de tabel.
	 * 
	 * @param $tabel - tabelnaam
	 * @return array - kolom namen
	 */
	private function getColumns($tabel)
	{
		switch($tabel)
		{
			case 'content' : return array('content_id', 'content_menu', 'content_title', 'content_text');
			case 'member' : return array('member_id', 'member_fname', 'member_lname', 'member_uname', 'member_pass','member_email');
			case 'admin_member' : return array('admin_id', 'admin_fname', 'admin_lname', 'admin_uname', 'admin_pass','admin_email');
		}
	}
	
	/**
	 * Deze functie returned een array met klaargezette keys(kolomnamen) voor het binden van variablen aan de 'prepared' query.
	 * 
	 * @param $page - de pagina waar de actie uitgevoerd wordt.
	 * @return array - array met kolomnamen keys.
	 */
	private function getParamArray($page)
	{
		switch($page)
		{
			case 'manage_pages' : return array(':content_menu' => '', ':content_title' => '', ':content_text' => '');
			case 'manage_members' : return array(':member_fname' => '', ':member_lname' => '', ':member_uname' => '', ':member_pass' => '', ':member_email' => '');
			case 'manage_admins' : return array(':admin_fname' => '', ':admin_lname' => '', ':admin_uname' => '', ':admin_pass' => '', ':admin_email' => '');
		}
	}
	
	/**
	 * Deze functie returned een string die in de query wordt gezet. 
	 * Bijvoorbeeld: "SELECT id,text,datum,.....".
	 * @param $columns - kolomnamen array
	 * @param $primarykey - true voor het meenemen van het ID in de string. False voor niet.
	 * 
	 * @return string - kolom string voor in de query
	 */
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
	
	/**
	 * Deze functie bouwt een value string die in de query wordt gezet.
	 * 
	 * Bijvoorbeeld: ":content_menu, :content_title...".
	 * @param $array - kolom namen
	 * @return string - value string
	 */
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
	
	/**
	 * Deze functie bouwt een update string voor 'UPDATE' query's.
	 * 
	 * @param array - $columns
	 * @return columnstring
	 */
	private function getUpdateString($columns)
	{
		//UPDATE table_name SET column1=:column1, column2=:column2,...
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
	
	/**
	 * Deze functie vult een array die keys bevat met values.
	 * 
	 * @param $keysArray - array met keys(kolom namen)
	 * @param $columns - array met kolom namen
	 * @return array met keys die values bevat
	 */
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