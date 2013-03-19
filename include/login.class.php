<?php
class Login
{
	public function login()
	{
		if(empty($_POST['username']))
		{
			return $this->content .= '<p>Username is empty!</p>';
		}
	
		if(empty($_POST['password']))
		{
			return $this->content .= '<p>Password is empty!</p>';
		}
	
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
	
		if(!$this->CheckLogin($username, $password))
		{
			return false;
		}
	
		return true;
	}
	
	function CheckLoginInDB($username, $password)
	{
		if(!$this->DBLogin())
		{
			$this->HandleError("Database login failed!");
			return false;
		}
		$username = $this->SanitizeForSQL($username);
		$pwdmd5 = md5($password);
		$qry = "Select name, email from $this->tablename ".
				" where username='$username' and password='$pwdmd5' ".
				" and confirmcode='y'";
		 
		$result = mysql_query($qry,$this->connection);
		 
		if(!$result || mysql_num_rows($result) <= 0)
		{
			$this->HandleError("Error logging in. ".
					"The username or password does not match");
			return false;
		}
		return true;
	}
}
