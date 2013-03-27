<?php
class dbHandler
{
	private $dbHandle;
	private $queries = array(
			'title' => 'SELECT content_title FROM content WHERE content_type=?',
			'content' => 'SELECT content_text FROM content WHERE content_type=?',
			'menu' => 'SELECT menu_title, menu_item FROM menu',
			'user' => 'SELECT member_uname FROM member WHERE member_uname=?',
			'pass' => 'SELECT member_pass FROM member WHERE member_uname=?');
	
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
			} else if ($query == 'login' || $query == 'greeting')
			{
				$result = $this->dbHandle->query($this->queries[$query]);
				return $result;
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
	
	public function addUserQuery($fn,$ln,$un,$pw,$em)
	{
		$result = 	'INSERT INTO member(member_fname, member_lname, member_uname, member_pass, member_email) 
					VALUES("'.$fn.'","'.$ln.'","'.$un.'","'.$pw.'","'.$em.'")';
		
		$this->dbHandle->query($result);
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