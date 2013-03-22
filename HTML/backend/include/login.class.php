<?php
include_once('database_handler.class.php');
include_once('framework_backend.class.php');

class Login extends FrameWorkBackend
{
	public function processLogin($action)
	{
		if($action == 'verifylogin')
		{
			$username = $this->getFormVariable('username');
			$password = $this->getFormVariable('password');
			
			if(empty($username) || empty($password))
			{
				return '<img src="images/oops.png" alt="error" width="150" height="150"> Oops, it looks like you have a bad memory..';
			}
	
			$this->setAuthentication($username, sha1($password));
		}
		else if($action == 'logout')
		{
			$this->removeAuthentication();
			return 'Why?? =(((((((';
		}
	
		//always check if user is authenticated
		if($this->isAuthenticated())
		{
			return 'succes';
		}
		else
		{
			return false;
		}
	}
	
	public function setAuthentication($username, $password)
	{
		//SHA1 versleuteling
		if(strlen($password) != 40)
			return false;
	
		//database connectie
		$values = array($username, $password);
		$result = $this->databaseHandler->executePreparedQuery('login', $values);
		
		if(is_array($result))
		{
			$usernameData = $result['username'];
			$passwordData = $result['password'];
			
			if($username == $usernameData && $password == $passwordData)
			{
				$_SESSION['username'] = $username;
				$_SESSION['password'] = $passwordData;
				return true;
			}
		}
			
		return false;
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