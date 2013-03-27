<?php
class DatabaseHandler
{
	private $connection;
	
	private $querys = array(
			'title' 			=> 'SELECT page_title FROM admin_pages WHERE page_type=?',
			'css' 				=> 'SELECT theme_file FROM admin_themes WHERE theme_name=?',
			'header' 			=> 'SELECT page_header FROM admin_pages WHERE page_type=?',
			'admin_menu'		=> 'SELECT page_type, menu_item FROM admin_menu',
			'footer' 			=> 'SELECT page_footer FROM admin_pages WHERE page_type=?',
			'dashboard'			=> 'SELECT page_text FROM admin_pages WHERE page_type="dashboard"',
			'manage_pages' 		=> 'SELECT * FROM content',
			'manage_menu' 		=> 'SELECT * FROM menu',
			'manage_accounts' 	=> 'SELECT * FROM users',
			'login' 			=> 'SELECT username, password FROM users WHERE username=? && password=?',
	);
	
	private $menu_items = array(
			'page_type' => array(),
			'menu_item' => array(),
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
		if(array_key_exists($query, $this->querys))
			$resultSet = $this->connection->query($this->querys[$query]);
		else
		{
			$resultSet = $this->connection->query($query);
			return;
		}
	
		$result = '';
		
		if($query != 'dashboard')
		{
			return $this->toArray($resultSet);
		}
		else
		{
			while($row = $resultSet->fetch_array())
			{
				$result .= $row[0];
			}
		}
		
		return $result;
	}
	
	public function executePreparedQuery($query, $value)
	{
		$result = null;
		echo $query . '</br>';
		
		$preparedQuery = $this->connection->prepare($this->querys[$query]);
		
		if(is_array($value))
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
		else if(!is_array($value) && !empty($value))
		{			
			$preparedQuery->bind_param('s', $value);
			$preparedQuery->execute();
			$preparedQuery->bind_result($result);
			$preparedQuery->fetch();
			return $result;
		}
		else
			return 'No results in database';	
	}
	
	public function toArray($resultSet)
	{
		$array = array();
	
		while($row = $resultSet->fetch_assoc())
		{
			array_push($array, $row);
		}
		
		/*echo '<pre>';
		print_r($array);
		echo  '</pre>';*/
		
		return $array;
	}
}