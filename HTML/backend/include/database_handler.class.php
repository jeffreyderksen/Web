<?php
class DatabaseHandler
{
	private $connection;
	
	private $querys = array(
			'title' 	=> 'SELECT page_title FROM admin_pages WHERE page_type=?',
			'css' 		=> 'SELECT theme_file FROM admin_themes WHERE theme_name=?',
			'header' 	=> 'SELECT page_header FROM admin_pages WHERE page_type=?',
			'footer' 	=> 'SELECT page_footer FROM admin_pages WHERE page_type=?',
			'content' 	=> 'SELECT page_text FROM admin_pages WHERE page_type=?',
			'login' 	=> 'SELECT username, password FROM users WHERE username=? && password=?'
	);
	
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
	
	public function executeQuery($query)
	{
		$result = $this->connection->query($query);
		$menu_items = array(
				'page_type' => array(),
				'menu_item' => array(),
				);
		
		while($row = $result->fetch_array())
		{
			array_push($menu_items['page_type'], $row['page_type']);
			array_push($menu_items['menu_item'], $row['menu_item']);
		}
		
		return $menu_items;
	}
	
	public function executePreparedQuery($query, $value)
	{
		$result = null;
		
		$preparedQuery = $this->connection->prepare($this->querys[$query]);
		
		if(!is_array($value))
		{
			$preparedQuery->bind_param('s', $value);
			$preparedQuery->execute();
			$preparedQuery->bind_result($result);
			$preparedQuery->fetch();
			return $result;
		}
		else
		{			
			$preparedQuery->bind_param('ss', $value[0], $value[1]);
			$preparedQuery->execute();
			$preparedQuery->bind_result($username, $password);
			$preparedQuery->fetch();
			
			return $result = array(
					'username' => $username, 
					'password' => $password
			);
		}		
		
		
	}
}