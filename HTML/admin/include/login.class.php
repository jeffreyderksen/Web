<?php
include_once('../include/database_handler.class.php');
include_once('framework_backend.class.php');

class Login extends FrameWorkBackend
{
	public $error;
	
	public function processLogin($action)
	{
		if($action == 'verifylogin')
		{
			$username = $this->getFormVariable('username');
			$password = $this->getFormVariable('password');
			
			if(empty($username) || empty($password))
			{
				$this->error = '<img class="error-oops" src="images/oops.png" alt="error"></br> Oops, it looks like you have a bad memory..';
				return false;
			}

			return $this->setAuthentication($username, sha1($password));
		}
		else if($action == 'logout')
		{
			$this->removeAuthentication();
		}
	
		//always check if user is authenticated
		if($this->isAuthenticated())
		{
			return true;
		}
		else
		{
			//$this->error = '<img class="error-oops" src="images/oops.png" alt="error"></br> Oops, it looks like you have a bad memory..';
			return false;
		}
	}
	
	public function setAuthentication($username, $password)
	{			
		//SHA1 versleuteling
		if(strlen($password) != 40)
		{
			$this->error = '<img class="error-oops" src="images/oops.png" alt="error"></br> Oops, it looks like you have a bad memory..';
			return false;
		}
		
		//database connectie
		$query = 'SELECT admin_id,admin_fname, admin_lname, admin_uname, admin_pass, admin_email, admin_activity FROM admin_member 
					WHERE admin_uname=:admin_uname && admin_pass=:admin_pass';
		$values = array(':admin_uname' => $username, ':admin_pass' => $password);
		$result = $this->databaseHandler->executeQuery($query, $values)->fetchAll();
		
		if(!empty($result[0]) && is_array($result[0]))
		{
			$usernameData = $result[0]['admin_uname'];
			$passwordData = $result[0]['admin_pass'];
			
			if($username == $usernameData && $password == $passwordData)
			{
				$_SESSION['username'] = $username;
				$_SESSION['password'] = $passwordData;
				$_SESSION['firstname'] = $result[0]['admin_fname'];
				$_SESSION['lastname'] = $result[0]['admin_lname'];
				$_SESSION['email'] = $result[0]['admin_email'];

				$_SESSION['activity'] = $result[0]['admin_activity'];
				
				//update last activity + timezone to database
				date_default_timezone_set('Europe/Amsterdam');
				$time = date('d-F-Y'). ' at ' .date('H:i:s');
				
				$query = 'UPDATE admin_member SET admin_activity=:admin_activity WHERE admin_id=:admin_id';
				$values = array(':admin_activity' => $time, ':admin_id' => $result[0]['admin_id']);
				$this->databaseHandler->executeQuery($query, $values);
			
				return true;
			}
		}

		$this->error = '<img class="error-oops" src="images/oops.png" alt="error"></br> Oops, it looks like you have a bad memory..';
		return false;
	}
	
	public function isAuthenticated()
	{	
		//check session
		if(isset($_SESSION['username']) && isset($_SESSION['password'])	)
			return $result = $this->setAuthentication($_SESSION['username'], $_SESSION['password']);
	
		return false;
	}
	
	public function removeAuthentication()
	{
		unset($_SESSION['username']);
		unset($_SESSION['password']);
		unset($_SESSION['firstname']);
		unset($_SESSION['lastname']);
		unset($_SESSION['email']);
		unset($_SESSION['lastlogin']);
	}
}