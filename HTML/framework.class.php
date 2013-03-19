<?php
include_once('../include/database_handler.class.php');
include_once('../include/login.class.php');

class FrameWork
{
	private $databaseHandler;
	
	private $content;
	private $title;
	private $cssFile;
	private $header;
	private $footer;
	
	private $QUERY_MENU = 'SELECT menu_item FROM menu';
	
	//constructor
	public function __construct()
	{
		//maak object voor de database handlerr
		$this->databaseHandler = new DataBaseHandler();
		$this->databaseHandler->openConnection('localhost', 'root', '', 'gametriangle');
	}
	
	public function editData($content_type)
	{
		//edit?
		$this->databaseHandler->executeQuery(null, 0);
	}
	
	public function getContent($content_type)
	{
		//set the content
		$this->content = $this->databaseHandler->executeQuery('SELECT content_text FROM content WHERE content_type="'. $content_type . '"', 0);
	}
	
	public function getMenu()
	{
		$menu = '';
		$menu .= '<ul>';

		//ontvangt array
		$menuItems = $this->databaseHandler->executeQuery($this->QUERY_MENU, 1);
		
		foreach($menuItems as &$menuItem)
		{
			$menu .= '<li>';
			$menu .= '<a href="?page='. strtolower($menuItem). '">' . $menuItem . '</a>';
			$menu .= '</li>';
		}
		
		$menu .= '</ul>';
		
		return $menu;
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
		
	public function display()
	{
		$content = '';
		
		$content .= '
		<html>
		<head>
			<title>Game Triangle</title>
			<link href="../css/style.css" rel="stylesheet" type="text/css" />
		</head>
		<body>
		<div id="layout">
			<div id="header"><h1>Header</h1></div>
			<div id="menu">
				' . $this->getMenu() . '
			</div>
			<div id="content"><p>' . $this->content . '</p></p></div>
			<div id="footer"><h3>Footer</h3></div>
		</div>
		</body>
		</html>';
		
		//close connection
		$this->databaseHandler->closeConnection();
		
		return $content;
	}
}