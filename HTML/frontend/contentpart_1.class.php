<?php
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
	public $sqlConnection;
	
	
	
	public function __construct()
	{
		$this->sqlConnection = new dbHandler();
		$this->sqlConnection->openConnection("localhost","User","bassie","gametriangle");		
	}
	
	public function setMenu()
	{
		$menu_items = $this->sqlConnection->executeQuery('menu');
		
		$result = '';
		
		for($i = 0; $i < sizeof($menu_items['menu_title']); $i++)
		{
				$result .= '<li>';
				$result .= '<a href="?page='. strtolower($menu_items['menu_title'][$i]). '">' . $menu_items['menu_item'][$i] . '</a>';
				$result .= '</li>';
		}		
		
		$this->menu = $result;
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
	
	public function setContent($value)
	{
		$result = $this->sqlConnection->executePreparedQuery('content',$value);
		$this->content = $result;
	}
	
	public function setTitle($value)
	{
		$result = $this->sqlConnection->executePreparedQuery('title',$value);
		$this->title = $result;
	}
	
	public function setCss($value)
	{
		$this->cssFile = $value;
	}
	
	public function setJScript($value)
	{
		$this->jScript = $value;
	}
	
	public function addUser($fn,$ln,$un,$pw,$em)
	{
		$this->sqlConnection->addUserQuery($fn,$ln,$un,$pw,$em);
	}
	
	public function setUser($value)
	{
		
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