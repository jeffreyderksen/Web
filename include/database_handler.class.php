<?php
class DatabaseHandler
{
	private $connection;
	
	public function openConnection($host, $user, $pass, $database)
	{
		$this->connection = new mysqli();
		$this->connection->connect($host, $user, $pass, $database);
		
		//check error
		if(mysqli_connect_errno())
		{
			echo "Error on connecting.";
			return false;
		}
		return true;
	}
	
	public function closeConnection()
	{
		$this->connection->close();
	}
	
	//SELECT
	public function executeQuery($sqlQuery, $type)
	{
		//handle it...
		return $this->handleResult($this->connection->query($sqlQuery), $type);
	}
	
	//INSERT
	public function updateQuery($sqlQuery)
	{
		
	}
	
	/**
	 * Haalt het mysqli_result(ResultSet) uit elkaar en verstuurd het terug in een String of Array.
	 * @param umysqli_result $resultSet
	 * @param Integer $type - 0 voor String, 1 voor array
	 * @return String | array
	 */
	public function handleResult($resultSet, $type)
	{	
		//check voor 0 of 1
		switch($type)
		{
			//geen array
			case 0:
			{
				$content = '';
				while($row = $resultSet->fetch_array())
				{
					$content .= $row[0];
				}
				return $content;	
			}
			//wel array
			case 1:
			{
				$content = array();
				while($row = $resultSet->fetch_array())
				{
					array_push($content, $row['menu_item']);
				}
				return $content;
			}
		}

		return null;
	}
}