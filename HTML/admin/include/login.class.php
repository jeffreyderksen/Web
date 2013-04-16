<?php
include_once('../include/database_handler.class.php');
include_once('framework_backend.class.php');

class Login extends FrameWorkBackend
{
	public $error;
	
	/**
	 * Functie die het login en logout proces behandeld en checkt voordurend voor authenticatie
	 * 
	 * @param $action - de actie de uitgevoerd moet worden(login of logout)
	 * @return boolean - true of false uitgaande van ingelogd of niet ingelogd.
	 */
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
	
	/**
	 * Deze functie zet de authenticatie van de gebruiker in de SESSION variabele wanneer de gegevens die de gebruiker
	 * ingevoerd heeft klopt. Dit controleert hij door de database te queryën om te kijken of de username of password klopt.
	 * 
	 * @param $username - ingevoerd username
	 * @param $password - ingevoerd password
	 * @return boolean - true als er authenticatie is gemaakt, anders false
	 */
	public function setAuthentication($username, $password)
	{			
		//SHA1 versleuteling
		if(strlen($password) != 40)
		{
			$this->error = '<img class="error-oops" src="images/oops.png" alt="error"></br> Oops, it looks like you have a bad memoryyy..';
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
				$_SESSION['ausername'] = $username;
				$_SESSION['apassword'] = $passwordData;
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
		return false;
	}
	
	/**
	 * Controleert of de user authenticated is door de SESSION variabelen mee te geven aan de setAuthentication functie.
	 * Deze controleert vervolgens of de gegevens (nog) kloppen. Als er geen SESSION variabelen bestaan returned hij false.
	 * 
	 * @return boolean - true als er authenticatie is gemaakt, anders false
	 */
	public function isAuthenticated()
	{	
		//check session
		if(isset($_SESSION['ausername']) && isset($_SESSION['apassword'])	)
			return $result = $this->setAuthentication($_SESSION['ausername'], $_SESSION['apassword']);
	
		return false;
	}
	
	/**
	 * Haalt alle SESSION variabele weg wanneer de gebruiker uitlogt.
	 */
	public function removeAuthentication()
	{
		unset($_SESSION['ausername']);
		unset($_SESSION['apassword']);
		unset($_SESSION['firstname']);
		unset($_SESSION['lastname']);
		unset($_SESSION['email']);
		unset($_SESSION['lastlogin']);
	}
}