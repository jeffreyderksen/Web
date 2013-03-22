<?php
class dbHandler
{
	private $dbHandle;
	private $queries = array(
			'title' => 'SELECT content_title FROM content WHERE content_type=?',
			'content' => 'SELECT content_text FROM content WHERE content_type=?',
			'menu' => 'SELECT menu_title, menu_item FROM menu'
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
	public function executeQuery($query)
	{
		if(!is_null($this->dbHandle))
		{
			if($query == 'menu')
			{				
				$result = $this->dbHandle->query($this->queries[$query]);
				$menu_items = array(
								'menu_title' => array(),
								'menu_item' => array()
								);
				
				while($row = $result->fetch_array())
				{
					array_push($menu_items['menu_title'], $row['menu_title']);
					array_push($menu_items['menu_item'], $row['menu_item']);					
				}
				return $menu_items;
			}
		}
		
	}
	
	public function executePreparedQuery($query, $value)
	{
		$preparedQuery = $this->dbHandle->prepare($this->queries[$query]);
		
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