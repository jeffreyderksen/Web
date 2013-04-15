<?php
include_once('include/database_handler.class.php');
class ContentPage 
{
	// Layout
	private $menu;
	private $loginForm;
	private $content;
	private $login;
	private $page;
	private $error;
	
	// Html head
	private $title;
	private $cssFile;
	private $jScript;
	
	// Connectie
	public $dbHandle;
	
	
	// constructor die een connectie maakt met de database
	public function __construct()
	{
		//maak object voor de database handler
		$this->dbHandle = new DatabaseHandler();
		
		if(!$this->dbHandle->openConnection('mysql:dbname=gametriangle;host=localhost', 'test', 'test'))
			echo '<p style="color: red">Error connecting to database.</p>';	
	}
	
	// Als er een error plaatsvindt wordt die hier in een variabele gezet
	public function setError($value)
	{
		$this->error = $value;
	}
	
	// Kijkt welke pagina er is opgevraagd en slaat die op
	public function setPage($value)
	{
		$this->page = $value;
	}
	
	// Haalt de menu gegevens op uit de database in maakt die op in html
	public function setMenu($value)
	{
		$query = 'SELECT content_menu FROM content';
		$param = array();
		$menu_items = $this->dbHandle->executeQuery($query, $param)->fetchAll();
			
		$result = '';
		
		// value 1 is als er niet is ingelogd, value 2 bij wel 
		if($value == 1)
		{			
			for($i = 0; $i < sizeof($menu_items); $i++)
			{
				$result .= '<li>';
				$result .= '<a href="?page='. strtolower($menu_items[$i]['content_menu']). '">' . $this->getMenuTitle($menu_items[$i]['content_menu']) . '</a>';
				$result .= '</li>';
			}			
		} else if ($value == 2)
		{
			// kijkt of de gegevens ongelijk zijn aan register zodat die knop niet verschijnt wanneer iemand is ingelogd
			for($i = 0; $i < sizeof($menu_items); $i++)
			{
				if($menu_items[$i]['content_menu'] != 'register')
				{
					$result .= '<li>';
					$result .= '<a href="?page='. strtolower($menu_items[$i]['content_menu']). '">' . $this->getMenuTitle($menu_items[$i]['content_menu']) . '</a>';
					$result .= '</li>';
				} 
			}
		}
		
		// Slaat de html op in variabel menu
		$this->menu = $result;		
	}
	
	// Zorgt ervoor dat het tekst van de menu buttons de juiste opmaak hebben
	public function getMenuTitle($value)
	{
		return $value = ucfirst(str_replace(array('_'), array(' '), $value));
	}
	
	// Stelt het formulier in wat rechts in het menu staat
	public function setLogin($value)
	{		
			// Kijkt naar de value die is gegeven en welke content er moet worden gegeven. 
			if($value == 'login') // Bezoeker is niet ingelogd
			{	
				$result = '<form method="post" action="index.php">					
								<table>
									<tr>
										<td>
											<label>Login:</label>
										</td>
										<td>
											<input type="text" name="username"/>
										</td>
									</tr>
									<tr>
										<td>
											<label>Password:</label>
										</td>
										<td>
											<input type="password" name="password"/>
										</td>						
									</tr>
									<tr>
										<td colspan=2><p class="error">'.$this->error.'</p></td>
									</tr>
								</table>
								<input class="button" type="submit" name="loginbutton" value="Login"/>
								<input type="hidden" name="action" value="login"/>
								<input type="hidden" name="action" value="verifylogin" />
								<input type="hidden" name="page" value="'.$this->page.'"/>
							</form>';
			} else { // Bezoeker is wel ingelogd
				$result = '<p>Welcome back '.$_SESSION['username'].'</br> hopefully you will enjoy your visit</br>
							<a href="?page=home&action=logout">Logout</a></p>';
				
			}
			
			$this->login = $result;
	}
	
	// Als een pagina niet beschikbaar is voor niet-ingelogde bezoeker wordt deze pagina weergeven
	public function setLoginPage()
	{
		$result = '<div class=formdiv>
					<h2>For viewing this page you need to be a registered member and logged in to the site</h2>
					<form method="post" action="index.php">					
					<table>
						<tr>
							<td>
								<label>Login:</label>
							</td>
							<td>
								<input type="text" name="username"/>
							</td>
						</tr>
						<tr>
							<td>
								<label>Password:</label>
							</td>
							<td>
								<input type="password" name="password"/>
							</td>						
						</tr>
						<tr>
							<td colspan=2><p class="error">'.$this->error.'</p></td>
						</tr>
					</table>
					<input class="button" type="submit" name="loginbutton" value="Login"/>
					<input type="hidden" name="action" value="login"/>
					<input type="hidden" name="action" value="verifylogin" />
					<input type="hidden" name="page" value="'.$this->page.'"/>					
				</form>	
			</div>';
		$this->content = $result;
	}
	
	// Haalt de content uit de database en slaat die op in variabel content
	public function setContent($value)
	{

		$param = array(':content_menu' => $value);
		$query = 'SELECT content_text FROM content WHERE content_menu=:content_menu';
		$content = $this->dbHandle->executeQuery($query,$param)->fetchAll();		

		$this->content = $content[0]['content_text'];
	}
	
	// Haalt de title uit de database en slaat die op in variabel title
	public function setTitle($value)
	{
		$param = array(':content_menu' => $value);
		$query = 'SELECT content_title FROM content WHERE content_menu=:content_menu';
		$result = $this->dbHandle->executeQuery($query,$param)->fetchAll();
		$this->title = $result[0]['content_title'];
	}
	
	// Haalt de css op uit index en slaat die op in variabel CssFile
	public function setCss($value)
	{
		$this->cssFile = $value;
	}
	
	// Haalt de javascript bestanden op en slaat die op in variabel jScript
	public function setJScript($value,$val)
	{
		$this->jScript = 	'<script src="../javascript/'.$value.'"></script>
							<script src="../javascript/'.$val.'"></script>';
	}
	
	// Haalt de gegevens op uit index als er een nieuwe gebruiker wordt ingeschreven en stuurt die naar de database
	public function addUser($fn,$ln,$un,$pw,$em)
	{
		// Haalt de username uit de database om te kijken of die nog niet in gebruik is
		$param = array(':un' => $un);
		$query = 'SELECT member_uname FROM member WHERE member_uname=:un';
		$check = $this->dbHandle->executeQuery($query,$param)->fetch();
		if(empty($check)) // De username is nog niet bekend in de database
		{
			// Geeft een melding dat een account succesvol aangemaakt is.
			$result = 	'<div class=formdiv><h2>Thank you for creating an account on our site. </h2>
						<h2>We hope you have a great time on our site</h2>
						<p>You will be redirected to the homepage in several seconds</p>
						<p>If nothing happens click <a href="index.php">here</a></p>
						</div>';
			$param = array(':fn' => $fn,':ln' => $ln,':un' => $un,':pw' => $pw,':em' => $em,);
			$query = 	'INSERT INTO member(member_fname, member_lname, member_uname, member_pass, member_email) 
						VALUES(:fn,:ln,:un,:pw,:em)';
			$this->dbHandle->executeQuery($query,$param)->fetchAll();
			$this->content = $result;
			
			// Stuurt de gebruiker na 8 seconden terug naar de homepage
			header('Refresh: 8; index.php');
		} 
		else // De username is al in gebruik
		{
			// Geeft een melding dat de account niet is aangemaakt vanwege een probleem
			$result = 	'<div class=formdiv><h2>An error occured while submitting your account</h2>
						<h2>Please be sure the username is not used yet</h2>
						<p>You will be redirected to the register page in several seconds</p>
						<p>If nothing happens click <a href="index.php?page=register">here</a></p>
						</div>';
			$this->content = $result;
			
			// Stuurt de gebruiker na 8 seconden terug naar de registerpagina
			header('Refresh: 8; index.php?page=register');
		}	
	}
	
	// Haalt variabelen uit een formulier en kijkt of het een GET of POST is.
	public function getFormVariable($value)
	{
		if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET[$value]))
		{
			return $_GET[$value];
		}
		else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[$value]))
		{
			return $_POST[$value];
		} else {
			return NULL;
		}
	}
	
	// Rendert de pagina met standaard opmaak en zet de juiste gegevens erin
 	public function render()
	{
		$result = '';
		
		$result = '	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
					<html>
					<head>
					<meta http-equiv="Content-Type" content="text/html;">
					<meta name=description content="beschrijving van de webpagina">
					<meta name=keywords content="trefwoorden,gescheiden, door, komma\'s">
					<link rel=stylesheet href="'.$this->cssFile.'">
					<script src="../javascript/jquery-1.6.3.min.js"></script>
					<script src="../javascript/jquery.validate.min.js"></script>
					'.$this->jScript.'
					<title>'.$this->title.'</title>
					</head>
					<body>
						<div id="container">
							<div id="header">
								<h1>GameTriangle - '.$this->title.'</h1>
							</div>
							
							<div id="menu">
								<ul id="menulist">
									'.$this->menu.'
								</ul>
								<div id="login">
									'.$this->login.'
								</div>
							</div>
							
							<div id="content">
								'.$this->content.'
							</div>
							
							<div id="footer">
								<h4>GameTriangle.inc</h4>
							</div>
						</div>
					</body>
					</html>';
		
		return $result;
	}
}
?>