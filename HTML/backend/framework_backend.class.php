<?php
include_once('include/database_handler.class.php');
include_once('include/action_handler.class.php');

class FrameWorkBackend
{
	public $databaseHandler;
	
	private $page;
	
	private $charset;
	private $metakeywords;
	private $metadescription;
	private $author;
	private $cssFile;
	
	private $title;
	private $header;
	private $menu;
	private $content;
	private $footer;

	//constructor
	public function __construct()
	{
		//maak object voor de database handler
		$this->databaseHandler = new DataBaseHandler();
		
		if(!$this->databaseHandler->openConnection('mysql:dbname=gametriangle;host=localhost', 'test', 'test'))
			echo '<p style="color: red">Error connecting to database.</p>';
	}
	
	//Laad alle page settings(behalve het menu)
	public function loadPage($page)
	{
		if($page != 'edit')
		{
			$param = array(':page_menu' => $page);
			$query = 'SELECT page_menu, page_title, page_header, page_footer, page_text FROM admin_pages
				  WHERE page_menu=:page_menu';
			$result = $this->databaseHandler->executeQuery($query, $param);
			
			if(is_array($result))
			{
				$this->page = $result[0];
				return true;
			}
			else
			{
				return 'Error loading page';
			}
		}
	}
	
	/*DEFAULT PAGE SETTINGS */
	public function setCharset($value)
	{
		$this->charset = $value;
	}
	public function setMetaKeywords($value)
	{
		$this->metakeywords = $value;
	}
	public function setMetaDescription($value)
	{
		$this->metadescription = $value;
	}
	public function setAuthor($value)
	{
		$this->author = $value;
	}
	public function setCssFile($value)
	{
		$this->cssFile = $value;
	}
	
	/* CURRENT PAGE SETTINGS */
	public function setTitle($value)
	{
		$this->title = $this->page[$value];
	}
	public function setHeader($value)
	{
		$this->header = $this->page[$value];
	}
	
	public function setFooter($value)
	{
		$this->footer = $this->page[$value];
	}
	
	public function setContent($value)
	{
		$content = '';
		$page = $this->page['page_menu'];
		//check voor statische tekst in database
		if(empty($this->page[$value]))
		{
			//laad frontend CONTENT
			if($this->page['page_menu'] == 'manage_pages')
			{
				
				$query = 'SELECT content_id,content_menu, content_text FROM content';
				$result = $this->databaseHandler->executeQuery($query);
									
				//table weergeven met alle pages
				$content .= '<table>';
				$content .= '<tr><th>Settings</th><th>#</th><th>Page menu-item</th><th>Page text</th></tr>';
				for($i = 0; $i < count($result); $i++)
				{
					$content .= '<tr>';
					$content .= '<td><a href="?page='. $page .'&action=load&row='. $result[$i]['content_id'] .'"><img src="images/icons/delete.png"/> Edit<a> <a href=""><img src="images/icons/table_edit.png"/>Delete</a></td>';
					$content .= '<td>'. ($i+1) .'</td><td>'. $this->getMenuTitle($result[$i]['content_menu']) .'</td><td>'. $result[$i]['content_text'] .'</td>';
					$content .= '</tr>';
				}
				$content .= '</table>';
			}
			//laad frontend USERS
			else if($this->page['page_menu'] == 'manage_accounts')
			{
				//TODO
			}
			
			/*check voor edit pagina
			$row = $this->getFormVariable('row');
			$tabel = $this->getFormVariable('tabel');
			$columns = $this->getColumns($tabel);
				
			$query = 'SELECT * FROM '. $tabel .' WHERE '. $columns[0] .'=' . $row;
				
			$param = array(':' => $value);
			$result = $this->databaseHandler->executeQuery($query, $param);
				
			$content .= $this->displayEditPage($result, $tabel, $columns, $row);*/
			
			
		}
		else
		{
			$content .= $this->page['page_text'];
		}
	
		$this->content .= $content;
	}
	
	public function setMenu()
	{
		$query = 'SELECT page_menu FROM admin_pages';
		$menuItems = $this->databaseHandler->executeQuery($query);
		
		$menu = '';
		$menu .= '<ul>';
		for($i = 0; $i < sizeof($menuItems); $i++)
		{
			$menu .= '<li>';
			$menu .= '<a href="?page='. $menuItems[$i]['page_menu'] . '">'. $this->getMenuTitle($menuItems[$i]['page_menu']) .'</a>';
			$menu .= '</li>';
		}
		$menu .= '<li><a href="?action=logout">Logout</a></li>';
		$menu .= '</ul>';
		
		$this->menu = $menu;
	}
	
	private function getMenuTitle($value)
	{
		//haal alle '_' weg en eerste letter UPPERCASE
		return $value = ucfirst(str_replace(array('_'), array(' ',), $value));
	}
	
	public function handleAction($page, $action)
	{
		$actionHandler = new ActionHandler();
		
		switch($action)
		{
			case 'insert': 
			{
				$actionHandler->newRow($page); break;
			}
			case 'delete': 
			{
				$row = $this->getFormVariable('row');
				$actionHandler->deleteRow($page, $row); break;
			}
			case 'load':
			{
				$row = $this->getFormVariable('row'); 
				$this->content .= $this->displayEditPage($page, $row); break;
			}
			case 'edit':
			{
				$row = $this->getFormVariable('row');
				$actionHandler->updateRow($page, $row); break;
			}
		}
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
	
	private function getTabel($page)
	{
		switch($page)
		{
			case 'manage_pages' : return 'content';
			case 'manage_accounts' : return 'users';
		}
	}
	private function getColumns($tabel)
	{
		switch($tabel)
		{
			case 'content' : return array('content_id', 'content_menu', 'content_title', 'content_header', 'content_footer', 'content_text');
			case 'users' : return array('user_id', 'username', 'password', 'email', 'admin');
		}
	}
	
	public function displayEditPage($page, $row)
	{
		$tabel = $this->getTabel($page);
		$columns = $this->getColumns($tabel);
		
		$content = '<div id="edit-page">
					<h3>Tabel "'. $tabel .'"</h3>
					<h4>Row "'. $row .'"</h4>
					<form action="index.php" method="get">';
		
		$param = array(':id' => $row);
		$query = 'SELECT * FROM '. $tabel .' WHERE '. $columns[0] .'=:id';
		$result = $this->databaseHandler->executeQuery($query, $param);
		
		echo '<pre>';
		print_r($result);
		echo '</pre>';
		
		for($i = 0; $i < count($result[0])/2; $i++)
		{
			$content .= '<label for="'. $columns[$i] .'">Change '. $columns[$i] .'</label>';
			if($columns[$i] != 'content_text')
				$content .= '<input name="'. $columns[$i] .'" value="'. $result[0][$columns[$i]] .'"/>';
			else
				$content .= '<textarea name="'. $columns[$i] .'" >'. $result[0][$columns[$i]] .'</textarea>
							 <script>CKEDITOR.replace( "content_text" );</script>';
			
		}

		//buttons
		$content .= '<input type="submit" name="submit" value="Wijzig"/>
					 <input type="hidden" name="row" value="'. $row .'"/>
					 <input type="hidden" name="action" value="edit"/>
					 <input type="hidden" name="page" value="'. $page .'"/>';
		
		$content .= '</form></div>';
		
		return $content;
	}
	
	public function showLoginForm($error)
	{
		return '
			<html>
			<head>
				<title>Login</title>
				<link href="css/default.css" rel="stylesheet" type="text/css" />
			</head>
			<body>
			<div class="logo">
				<img class="logo-img" src="images/logo.png" />
			</div>
			<div id="login">
				<div class="error">
					'. $error .'
				</div>
				<form id="loginform" action="index.php" method="post">
					<label for="username" >Username:</label>
					<input type="text" name="username" id="username"  maxlength="50" />
			
					<label for="password" >Password:</label>
					<input type="password" name="password" id="password" maxlength="50" />
							
					<a class="ww-link" href="#">Wachtwoord vergeten?</a>
							
					<input type="submit" class="submit" name="loginbutton" value="Login" />
					<input type="hidden" name="page" value="dashboard" />
					<input type="hidden" name="action" value="verifylogin" />
				
				</form>
			</div>
			</body>
			</html>';
	}
	
	public function display()
	{
		$pagina = '';
		$pagina .= '
			<!DOCTYPE html>
			<html>
			<head>			
				<meta charset="'. $this->charset .'">
				<meta name="keywords" content="'. $this->metakeywords .'">
				<meta name="description" content="'. $this->metadescription .'">
				<meta name="author" content="'. $this->author .'">
				<title>'. $this->title .'</title>
				<link href="'. $this->cssFile .'" rel="stylesheet" type="text/css" />
				<script src="_js/ckeditor/ckeditor.js"></script>
			</head>
			<body>
			<div id="wrap">
				<div id="sidebar">
					<div class="logo">
						<img class="logo-img" src="images/logo.png" />
					</div>
					<div id="menu">'. $this->menu.'</div>
  				</div>
				<div id="container">
					<div id="header">'. $this->header .'</div>
					<div id="content">' . $this->content . '</div>
					<div id="footer">Logged in as: "' . $_SESSION['username'] . '"</div>
				</div>
				
			</div>
			</body>
			</html>';
		
		return $pagina;
	}
}