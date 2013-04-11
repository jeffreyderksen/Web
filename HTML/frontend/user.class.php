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
		
		$param = array(':member_uname' => $username);
		$query = 'SELECT member_uname, member_pass FROM member WHERE member_uname=:member_uname';
		
		$login = $this->dbHandle->executeQuery($query,$param)->fetchAll();
		
		
		
		if($username == $login[0]['member_uname'] && $password == $login[0]['member_pass'])
		{
			$result = true;
			
			$_SESSION['username'] = $username;
			$_SESSION['password'] = $password;
		}
		
		return $result;
	}
	
	public function isAuth()
	{
		$result = false;
		
		if(isset($_SESSION['ausername']) && isset($_SESSION['apassword']))
		{
			$result = $this->setAuth($_SESSION['ausername'],$_SESSION['apassword']);
		}
		
		return $result;
	}
	
	public function removeAuth()
	{
		unset($_SESSION['ausername']);
		unset($_SESSION['apassword']);
	}	
}
?>