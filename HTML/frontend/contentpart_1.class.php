<?php
include_once('include/database_handler.class.php');
class ContentPage 
{
	//layout
	private $menu;
	private $loginForm;
	private $content;
	private $login;
	
	//header
	private $title;
	private $cssFile;
	private $jScript;
	
	//connection 
	public $dbHandle;
	
	
	
	public function __construct()
	{
		//maak object voor de database handler
		$this->dbHandle = new DatabaseHandler();
		
		if(!$this->dbHandle->openConnection('mysql:dbname=gametriangle;host=localhost', 'test', 'test'))
			echo '<p style="color: red">Error connecting to database.</p>';	
	}
	
	public function setMenu()
	{
		$query = 'SELECT content_menu,content_title FROM content';
		$param = array();
		$menu_items = $this->dbHandle->executeQuery($query, $param);
		
		$result = '';
		
		for($i = 0; $i < sizeof($menu_items); $i++)
		{
				$result .= '<li>';
				$result .= '<a href="?page='. strtolower($menu_items[$i]['content_menu']). '">' . $this->getMenuTitle($menu_items[$i]['content_title']) . '</a>';
				$result .= '</li>';
		}		
		
		$this->menu = $result;
	}
	
	public function getMenuTitle($value)
	{
		return $value = ucfirst(str_replace(array('_'), array(' '), $value));
	}
	
	public function setLogin($value)
	{
			if($value == 'login')
			{	
				$result = '<form method="post" action="index.php">					
								<table>
									<tr>
										<td>
											<label>Login:</label>
										</td>
										<td>
											<input type="text" name="username"></input>
										</td>
									</tr>
									<tr>
										<td>
											<label>Password:</label>
										</td>
										<td>
											<input type="password" name="password"></input>
										</td>						
									</tr>
								</table>
								<input class="button" type="submit" name="loginbutton" value="Login"></input>
								<input type="hidden" name="action" value="login"/>
								<input type="hidden" name="action" value="verifylogin" />
							</form>';
				$this->login = $result;
			} else {
				$result = '<p>Welcome back '.$_SESSION['username'].'</br> hopefully you will enjoy your visit</br>
							<a href="?page=home&action=logout">Logout</a></p>';
				$this->login = $result;
			}
	}
	
	public function setLoginPage()
	{
		$result = '<div class=formdiv><h2>For viewing this page you need to be a registered member and logged in to the site</h2><form method="post" action="index.php">					
					<table>
						<tr>
							<td>
								<label>Login:</label>
							</td>
							<td>
								<input type="text" name="username"></input>
							</td>
						</tr>
						<tr>
							<td>
								<label>Password:</label>
							</td>
							<td>
								<input type="password" name="password"></input>
							</td>						
						</tr>
					</table>
					<input class="button" type="submit" name="loginbutton" value="Login"></input>
					<input type="hidden" name="action" value="login"/>
					<input type="hidden" name="action" value="verifylogin" />
				</form></div>';
		$this->content = $result;
	}
	
	public function setContent($value)
	{

		$param = array(':content_menu' => $value);
		$query = 'SELECT content_text FROM content WHERE content_menu=:content_menu';
		$content = $this->dbHandle->executeQuery($query,$param);		

		$this->content = $content[0]['content_text'];
	}
	
	public function setTitle($value)
	{
		$param = array(':content_menu' => $value);
		$query = 'SELECT content_title FROM content WHERE conent_menu=:content_menu';
		$result = $this->dbHandle->executeQuery($query,$value);
		$this->title = $result[0]['content_title'];
	}
	
	public function setCss($value)
	{
		$this->cssFile = $value;
	}
	
	public function setJScript($value,$val)
	{
		$this->jScript = 	'<script src="../javascript/'.$value.'"></script>
							<script src="../javascript/'.$val.'"></script>';
	}
	
	public function addUser($fn,$ln,$un,$pw,$em)
	{
		$param = array(':fn' => $fn,':ln' => $ln,':un' => $un,':pw' => $pw,':em' => $em,);
		$query = 'INSERT INTO member(member_fname, member_lname, member_uname, member_pass, member_email) 
					VALUES(":fn",":ln",":un",":pw",":em")';
		$this->dbHandle->executeQuery($query,$param);
	}
	
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
								<h1>GameTriangle</h1>
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