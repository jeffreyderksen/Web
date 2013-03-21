<?php
include_once('include/database_handler.class.php');

class FrameWorkBackend
{
	private $databaseHandler;
	
	private $title;
	private $cssFile;
	private $header;
	private $menu;
	private $content;
	private $footer;

	//constructor
	public function __construct()
	{
		//maak object voor de database handler
		$this->databaseHandler = new DataBaseHandler();
		$this->databaseHandler->openConnection('localhost', 'test', 'test', 'gametriangle');
	}
	
	public function setTitle($value)
	{
		$result = $this->databaseHandler->executePreparedQuery('title', $value);
		$this->title = $result;
	}
	
	public function cssFile($value)
	{
		$result = $this->databaseHandler->executePreparedQuery('css', $value);
		$this->cssFile = $result;
	}
	
	public function setHeader($value)
	{
		$result = $this->databaseHandler->executePreparedQuery('header', $value);
		$this->header = $result;
	}
	
	public function setMenu($value)
	{
		$query = 'SELECT page_type, menu_item FROM admin_menu';
		$menuItems = $this->databaseHandler->executeQuery($query);
		
		$menu = '';
		$menu .= '<ul>';
		for($i = 0; $i < sizeof($menuItems['page_type']); $i++)
		{
			$menu .= '<li>';
			$menu .= '<a href="?page='. strtolower($menuItems['page_type'][$i]). '">' . $menuItems['menu_item'][$i] . '</a>';
			$menu .= '</li>';
		}
		$menu .= '</ul>';
		
		$this->menu = $menu;
	}
	
	public function setContent($value)
	{
		$result = $this->databaseHandler->executePreparedQuery('content', $value);
		$this->content = $result;
	}
	
	public function setFooter($value)
	{
		$result = $this->databaseHandler->executePreparedQuery('footer', $value);
		$this->footer = $result;
	}
	
	//get form variable GET or POST
	public function getFormVariable($value)
	{
		if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET[$value]))
		{
			return $_GET[$value];
		}
		else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[$value]))
		{
			return $_POST[$value];
		}
		else
		{
			return null;
		}
	}
	
	public function showLoginForm($error)
	{
		return '
			<html>
			<head>
				<title>Login</title>
				<link href="../css/default.css" rel="stylesheet" type="text/css" />
			</head>
			<body>
			<div id="login">
				<form action="index.php" method="post">
				<div class="error">
					'. $error .'
				</div>
				<fieldset >
					<legend>Login</legend>
					<label for="username" >Username:</label>
					<input type="text" name="username" id="username"  maxlength="50" />
			
					<label for="password" >Password:</label>
					<input type="password" name="password" id="password" maxlength="50" />
			
					<input type="submit" name="loginbutton" value="Login" />
					<input type="hidden" name="page" value="dashboard" />
					<input type="hidden" name="action" value="verifylogin" />
				</fieldset>
				</form>
			</div>
			</body>
			</html>';
	}
	
	public function display()
	{
		$pagina = '';
		$pagina .= '
		<html>
		<head>
			<title>'. $this->title .'</title>
			<link href="'. $this->cssFile .'" rel="stylesheet" type="text/css" />
		</head>
		<body>
		<div id="container">
			<div id="header">'. $this->header .'</div>
			<div id="menu">'. $this->menu.'</div>
			<div id="content">' . $this->content . '</div>
			<div id="footer">'. $this->footer .'</div>
		</div>
		</body>
		</html>';
		
		//close connection
		$this->databaseHandler->closeConnection();
		
		return $pagina;
	}
}