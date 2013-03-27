<?php
include_once('include/database_handler.class.php');
include_once('include/action_handler.class.php');

class FrameWorkBackend
{
	public $databaseHandler;
	
	private $title;
	private $cssFile;
	private $header;
	private $menu;
	private $content;
	private $footer;
	
	//dashboard variables
	private $newRow;

	//constructor
	public function __construct()
	{
		//maak object voor de database handler
		$this->databaseHandler = new DataBaseHandler();
		$this->databaseHandler->openConnection('localhost', 'test', 'test', 'gametriangle');
	}
	
	public function setTitle($query, $value)
	{
		$result = $this->databaseHandler->executePreparedQuery($query, $value);
		$this->title = $result;
	}
	
	public function cssFile($query, $value)
	{
		$result = $this->databaseHandler->executePreparedQuery($query, $value);
		$this->cssFile = $result;
	}
	
	public function setHeader($query, $value)
	{
		$result = $this->databaseHandler->executePreparedQuery($query, $value);
		$this->header = $result;
	}
	
	public function setMenu()
	{
		$menuItems = $this->databaseHandler->executeQuery('admin_menu');
		
		$menu = '';
		$menu .= '<ul>';
		for($i = 0; $i < sizeof($menuItems); $i++)
		{
			$menu .= '<li>';
			$menu .= '<a href="?page='. strtolower($menuItems[$i]['page_type']). '">' . $menuItems[$i]['menu_item'] . '</a>';
			$menu .= '</li>';
		}
		$menu .= '</ul>';
		
		$this->menu = $menu;
	}
	
	public function setContent($query)
	{
		$result = $this->databaseHandler->executeQuery($query);
		$content = '';
		
		if(is_array($result))
		{
			//start form
			$content .= '<form id="tabelform" action="index.php" method="post">';
			//get column names for table
			$columns = $this->getColumns($result);
			
			//add buttons
			$content .= $this->addButtons();
			
			if(!empty($columns))
			{
				//start table
				$content .= '<table>';
	
				/*echo '<pre>';
				print_r($result);
				echo  '</pre>';*/
				
				//add settings column
				$content .= '<th></th>';
				//columns
				for($i = 0; $i < count($columns[0]); $i++)
				{
					$content .= '<th>' . $columns[0][$i] . '</th>';
				}
				//rows
				for($i = 0; $i < count($result); $i++)
				{
					$content .= '<tr>';
					for($j = -1; $j < count($result[$i]); $j++)
					{
						//add settings
						if($j == -1)
							$content .= '<td><a href="?page=manage_pages&action=delete&id='. $result[$i][$columns[0][0]] .'">Delete</a></td>';
						else
							$content .= '<td><input type="textarea" value="' . $result[$i][$columns[0][$j]] . '"/></td>';
					}
					$content .= '</tr>';
				}
				//check for new row
				if($this->newRow)
				{
					$content .= '<tr>';
					for($i = 0; $i < count($columns[0]); $i++)
					{
						$content .= '<td><input type="textarea" value="0"/></td>';
					}			
					
					$content .= '</tr>';
				}
				
				//end table
				$content .= '</table>';
			
				$this->content = $content;
			}
			else
			{
				$content .= 'No results in database';
			}
			$content .= '</form>';
			$this->content = $content;
		}
		else
		{
			$this->content = $result;
		}
	}
	
	public function setFooter($query, $value)
	{
		$result = $this->databaseHandler->executePreparedQuery($query, $value);
		$this->footer = $result;
	}
	
	public function getColumns($array)
	{
		$columns = array();
		//get column names
		if(!empty($array))
			array_push($columns, array_keys($array[0]));
		
		return $columns;
	}
	
	public function addButtons()
	{
		$content = '';
		$content .= '<input type="submit" value="Nieuwe rij" name="submit"/>
					 <input type="hidden" name="action" value="newRow" />
					 <input type="hidden" name="page" value="manage_pages" />
					 </br>';
		
		return $content;
	}
	
	public function handleAction($page, $action)
	{
		$actionHandler = new ActionHandler();
		
		switch($action)
		{
			case 'newRow': 
				$actionHandler->newRow($page);
			case 'delete': 
			{
				$id = $this->getFormVariable('id');
				$actionHandler->deleteRow($page, $id);
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
				<meta charset="UTF-8">
				<title>'. $this->title .'</title>
				<link href="'. $this->cssFile .'" rel="stylesheet" type="text/css" />
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
		
		//close connection
		$this->databaseHandler->closeConnection();
		
		return $pagina;
	}
}