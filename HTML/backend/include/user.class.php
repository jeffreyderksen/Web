<?php
class User
{
	public function setAuthentication($username, $password)
	{
		//SHA1 versleuteling
		if(strlen($password) != 40)
			return false;
		
		$result = false;
		
		//database connectie
		$usernameData = 'test';
		$passwordData = 'cf20bd4627f6ea59a9a2d67be878c201decd9f18';
		
		if($username == $usernameData && $password == $passwordData)
		{
			$result = true;
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $passwordData;
		}
		
		return $result;
	}
	
	public function isAuthenticated()
	{
		$result = false;
		
		//check session
		if(isset($_SESSION['username']) && isset($_SESSION['password'])	)
			$result = $this->setAuthentication($_SESSION['username'], $_SESSION['password']);

		return $result;
	}	
	
	public function removeAuthentication()
	{
		unset($_SESSION['username']);
		unset($_SESSION['password']);
	}
}