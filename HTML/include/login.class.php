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
				return '<img class="error-oops" src="images/oops.png" alt="error"></br> Oops, it looks like you have a bad memory..';
			}
			echo sha1($password);
			$this->setAuthentication($username, sha1($password));
		}
		else if($action == 'logout')
		{
			$this->removeAuthentication();
			return 'You are now logged out.';
		}
	
		//always check if user is authenticated
		if($this->isAuthenticated())
		{
			return true;
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
		$query = 'SELECT admin_uname, admin_pass FROM admin_member WHERE admin_uname=:admin_uname && admin_pass=:admin_pass';
		$values = array(':admin_uname' => $username, ':admin_pass' => $password);
		$result = $this->databaseHandler->executeQuery($query, $values);
		
		if(!empty($result[0]) && is_array($result[0]))
		{
			$usernameData = $result[0]['admin_uname'];
			$passwordData = $result[0]['admin_pass'];
			
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