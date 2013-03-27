<?php
include_once ('../HTML/frontend/contentpart_1.class.php');

class User extends ContentPage{

	public function setAuth($username, $password)
	{
		if(strlen($password) != 40)
		{
			return false;
		}
		$result = false;
		
		
		$usernameData = $this->sqlConnection->executePreparedQuery('user', $username);
		$passwordData = $this->sqlConnection->executePreparedQuery('pass', $username);
		
		if($username == $usernameData && $password == $passwordData)
		{
			$result = true;
			
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $passwordData;
		}
		
		return $result;
	}
	
	public function isAuth()
	{
		$result = false;
		
		if(isset($_SESSION['username']) && isset($_SESSION['password']))
		{
			$result = $this->setAuth($_SESSION['username'],$_SESSION['password']);
		}
		
		return $result;
	}
	
	public function removeAuth()
	{
		unset($_SESSION['username']);
		unset($_SESSION['password']);
	}	
}
?>