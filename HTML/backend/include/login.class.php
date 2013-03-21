<?php
include_once('user.class.php');

class Login extends FrameWorkBackend
{
	private $user;
	
	public function processLogin($action)
	{
		$this->user = new User();
		
		if($action == 'verifylogin')
		{
			$username = $this->getFormVariable('username');
			$password = $this->getFormVariable('password');
			
			if(empty($username) || empty($password))
			{
				return '<img src="images/oops.png" alt="error" width="150" height="150"> Oops, it looks like you have a bad memory..';
			}
	
			$this->user->setAuthentication($username, sha1($password));
		}
		else if($action == 'logout')
		{
			$this->user->removeAuthentication();
			return 'Why?? =(((((((';
		}
	
		//always check if user is authenticated
		if($this->user->isAuthenticated())
		{
			echo 'Currently logged in as: "' . $_SESSION['username'] . '" with password: "' . $_SESSION['password']  . '"';
			return 'succes';
		}
		else
		{
			echo 'Not Logged in';
			return false;
		}
			
	}
	
	public function checkLogout($action)
	{
	
	}
}