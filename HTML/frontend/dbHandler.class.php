<?php
class dbHandler
{
	private $dbHandle;
	
	public function openConnection($host,$user,$password,$database)
	{
		$result = true;
		
		//open connection
		$this->dbHandle = new mysqli($host,$user,$password,$database);
		// check error
		if(mysqli_connect_errno())
		{
			echo "Error: Could not connect to database. Please try again later.";
			$result = false;
		}
		
		return $result;
	}
	
	//execute functie
	public function executeQuery($sql_query)
	{
		if(!is_null($this->dbHandle))
		{
		return $this->dbHandle->query($sql_query);
		}
	}
	
	//close connection
	public function closeConnection()
	{
		if(!is_null($this->dbHandle))
		{
		return $this->dbHandle->close();
		}
	}
}
?>