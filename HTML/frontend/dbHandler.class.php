<?php
class dbHandler
{
	private $dbHandle;
	private $query = array(
			'title' => 'SELECT content_title FROM content WHERE content_type=?',
			'content' => 'SELECT content_text FROM content WHERE content_type=?'
	);
	
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
	
	public function executePreparedQuery($query, $value)
	{
		$preparedQuery = $this->dbHandle->prepare($this->query[$query]);
		
		$preparedQuery->bind_param('s', $value);
		$preparedQuery->execute();
		$preparedQuery->bind_result($result);
		$preparedQuery->fetch();
		
		return $result;
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