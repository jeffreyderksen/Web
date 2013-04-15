<?php
include_once ('../HTML/frontend/contentpart_1.class.php');

class User extends ContentPage{
	
	// Kijkt of de inloggegevens juist zijn en maakt een sessie aan
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
	
	// Kijkt of er een sessie loopt en kijkt of de inloggegevens kloppen
	public function isAuth()
	{
		$result = false;
		
		if(isset($_SESSION['username']) && isset($_SESSION['password']))
		{
			$result = $this->setAuth($_SESSION['username'],$_SESSION['password']);
		}
		
		return $result;
	}
	
	// Verwijdert de sessies
	public function removeAuth()
	{
		unset($_SESSION['username']);
		unset($_SESSION['password']);
	}	
}
?>