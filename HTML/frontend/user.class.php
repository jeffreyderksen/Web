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
		
		$login = $this->dbHandle->executeQuery($query,$param)->fetch();
		
		if(!empty($login))
		{
			if($username == $login['member_uname'] && $password == $login['member_pass'])
			{
				$result = true;
					
				$_SESSION['username'] = $username;
				$_SESSION['password'] = $password;
			}
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