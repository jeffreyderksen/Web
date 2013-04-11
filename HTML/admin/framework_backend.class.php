<?php
include_once('../include/database_handler.class.php');
include_once('include/action_handler.class.php');

class FrameWorkBackend
{
	public $databaseHandler;
	
	private $page;
	
	private $charset;
	private $author;
	private $cssFile;
	
	private $title;
	private $header;
	private $menu;
	private $content;
	private $footer;
	
	private $message;

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
			$query = 'SELECT page_menu, page_title, page_header, page_footer, page_text FROM admin_pages WHERE page_menu=:page_menu';
			$result = $this->databaseHandler->executeQuery($query, $param)->fetchAll();
			
			if(is_array($result))
			{
				$this->page = $result[0];
				//Welcome message
				if($page == 'dashboard')
				{
					$this->setMessage('<p>Welcome '. $_SESSION['firstname'] .' ' . $_SESSION['lastname'] . '. </br>Last activity: <i>'. $_SESSION['activity'] .'</i></p>');
				}
				return true;
			}
			else
			{
				return 'Error loading page';
			}
		}
	}
	
	/* DISPLAY SUCCES OR ERROR MESSAGE */
	public function setMessage($value)
	{
		$this->message = $value;
	}
	
	/*DEFAULT PAGE SETTINGS */
	public function setCharset($value)
	{
		$this->charset = $value;
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
		$content = '<div id="content-page">';
		
		$page = $this->page['page_menu'];
		//check voor statische tekst in database
		if(empty($this->page[$value]))
		{
			//laad frontend CONTENT
			if($this->page['page_menu'] == 'manage_pages')
			{
				$content .= '<h3>Current pages</h3>
							 <a href="?page='. $page .'&action=new">
							 <img src="images/icons/add.png"/> New page</a>';
				
				$query = 'SELECT content_id,content_menu FROM content';
				$result = $this->databaseHandler->executeQuery($query)->fetchAll();
									
				//table weergeven met alle pages
				$content .= '<table>';
				$content .= '<tr><th>Settings</th><th>#</th><th>Page menu-item</th></tr>';
				for($i = 0; $i < count($result); $i++)
				{
					$content .= '<tr>';
					$content .= '<td><a href="?page='. $page .'&action=load&row='. $result[$i]['content_id'] .'"><img src="images/icons/table_edit.png"/> Edit</a> <a href="?page='. $page .'&action=delete&row='. $result[$i]['content_id'] .'"><img src="images/icons/delete.png"/>Delete</a></td>';
					$content .= '<td>'. ($i+1) .'</td><td>'. $result[$i]['content_menu'] .'</td>';
					$content .= '</tr>';
				}
				$content .= '</table>';
			}
			//laad frontend USERS
			else if($this->page['page_menu'] == 'manage_members')
			{
				$content .= '<h3>Members</h3><a href="?page='. $page .'&action=new"><img src="images/icons/add.png"/> New member</a>';
				
				$query = 'SELECT member_id, member_uname FROM member';
				$result = $this->databaseHandler->executeQuery($query)->fetchAll();
									
				//table weergeven met alle pages
				$content .= '<table>';
				$content .= '<tr><th>Settings</th><th>#</th><th>User</th></tr>';
				for($i = 0; $i < count($result); $i++)
				{
					$content .= '<tr>';
					$content .= '<td><a href="?page='. $page .'&action=load&row='. $result[$i]['member_id'] .'"><img src="images/icons/table_edit.png"/> Edit<a> <a href="?page='. $page .'&action=delete&row='. $result[$i]['member_id'] .'"><img src="images/icons/delete.png"/>Delete</a></td>';
					$content .= '<td>'. ($i+1) .'</td><td>'. $result[$i]['member_uname'] .'</td>';
					$content .= '</tr>';
				}
				$content .= '</table>';
			}
			//laad ADMIN USERS
			else if($this->page['page_menu'] == 'manage_admins')
			{
				$content .= '<h3>Administrators</h3><a href="?page='. $page .'&action=new"><img src="images/icons/add.png"/> New admin</a>';
				
				$query = 'SELECT admin_id, admin_uname,admin_activity FROM admin_member';
				$result = $this->databaseHandler->executeQuery($query)->fetchAll();
									
				//table weergeven met alle pages
				$content .= '<table>';
				$content .= '<tr><th>Settings</th><th>#</th><th>Username</th><th>Last activity</th></tr>';
				for($i = 0; $i < count($result); $i++)
				{
					$content .= '<tr>';
					$content .= '<td><a href="?page='. $page .'&action=load&row='. $result[$i]['admin_id'] .'"><img src="images/icons/table_edit.png"/> Edit</a> 
								     <a href="?page='. $page .'&action=delete&row='. $result[$i]['admin_id'] .'"><img src="images/icons/delete.png"/>Delete</a></td>';
					$content .= '<td>'. ($i+1) .'</td>
							     <td>'. $result[$i]['admin_uname'] .'</td>
							     <td>'. $result[$i]['admin_activity'] .'</td>';
					$content .= '</tr>';
				}
				$content .= '</table>';
			}
			else if($this->page['page_menu'] == 'logs')
			{
				$content .= '<h3>Logs</h3>';
				
				$query = 'SELECT * FROM admin_logs';
				$result = $this->databaseHandler->executeQuery($query)->fetchAll();
					
				//table weergeven met alle pages
				$content .= '<table>';
				$content .= '<tr><th>#</th><th>Action</th><th>Details</th><th>By</th></tr>';
				for($i = 0; $i < count($result); $i++)
				{
					$content .= '<tr>';
					$content .= '<td>'. ($i+1) .'</td>
								 <td>'. $result[$i]['log_action'] .'</td>
								 <td>'. $result[$i]['log_details'] .'</td>
								 <td>'. $result[$i]['log_who'] .'</td>';
					$content .= '</tr>';
				}
				$content .= '</table>';
			}
		}
		else
		{
			$content .= $this->page['page_text'];
		}
		$content .= '</div>';
		$this->content .= $content;
	}
	
	public function setMenu()
	{
		$query = 'SELECT page_menu FROM admin_pages';
		$menuItems = $this->databaseHandler->executeQuery($query)->fetchAll();
		
		$menu = '';
		$menu .= '<ul>';
		for($i = 0; $i < sizeof($menuItems); $i++)
		{
			$menu .= '<li>';
			$menu .= '<a href="?page='. $menuItems[$i]['page_menu'] . '">'. $this->getMenuTitle($menuItems[$i]['page_menu']) .'</a>';
			$menu .= '</li>';
		}
		$menu .= '<li><a href="?page=dashboard&action=logout">Logout</a></li>';
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
			case 'new': 
			{
				$this->content .= $this->displayEditPage($page, $action); break;
				
			}
			case 'load':
			{
				$row = $this->getFormVariable('row');
				$this->content .= $this->displayEditPage($page, $action, $row); break;
			}
			case 'delete': 
			{
				$row = $this->getFormVariable('row');
				if($actionHandler->deleteRow($page, $row))
					$this->setMessage('<p style="color:green">Action: "Delete" was executed succesfully.</p>');
				else
					$this->setMessage('<p style="color:red">Action: "Delete" could not be executed.</p>');
				break;
			}

			case 'update':
			{
				$row = $this->getFormVariable('row');
				if($actionHandler->updateRow($page, $row))
					$this->setMessage('<p style="color:green">Action: "Edit" was executed succesfully.</p>');
				else
					$this->setMessage('<p style="color:red">Action: "Edit" could not be executed.</p>');
				break;
			}
			case 'insert':
			{
				if($actionHandler->newRow($page))
					$this->setMessage('<p style="color:green">Action: "New" was executed succesfully.</p>'); 
				else
					$this->setMessage('<p style="color:red">Action: "New" could not be executed.</p>');
				break;
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
			case 'manage_members' : return 'member';
			case 'manage_admins' : return 'admin_member';
		}
	}
	private function getColumns($tabel)
	{
		switch($tabel)
		{
			case 'content' : return array('content_id', 'content_menu', 'content_title', 'content_text');
			case 'member' : return array('member_id', 'member_fname', 'member_lname', 'member_uname', 'member_pass','member_email');
			case 'admin_member' : return array('admin_id', 'admin_fname', 'admin_lname', 'admin_uname', 'admin_pass','admin_email');
		}
	}
	
	public function displayEditPage($page, $action, $row=null)
	{
		$tabel = $this->getTabel($page);
		$columns = $this->getColumns($tabel);
		
		$content = '<div id="edit-page">
					<h3>Tabel "'. $tabel .'"</h3>';
		if(empty($row))
			$content .= '<h4>Row "new"</h4>';
		else
			$content .= '<h4>Row "'. $row .'"</h4>';
		$content .= '<form action="index.php" method="POST">';
		
		//gegevens ophalen
		if($action == 'load')
		{
			$param = array(':id' => $row);
			$query = 'SELECT * FROM '. $tabel .' WHERE '. $columns[0] .'=:id';
			echo $query;
			$result = $this->databaseHandler->executeQuery($query, $param)->fetchAll();
			
			for($i = 0; $i < count($result[0])/2; $i++)
			{
				$content .= '<label for="'. $columns[$i] .'">Change '. $columns[$i] .'</label>';
				//id - disabled
				if($i == 0)
					$content .= '<input name="'. $columns[$i] .'" value="'.$result[0][$columns[$i]].'" disabled/>';
				//ajax oninput ..
				else if($columns[$i] == 'content_menu')
				{
					$content .= '<input id="'. $columns[$i] .'" name="'. $columns[$i] .'" value="'.$result[0][$columns[$i]].'" oninput="checkPageExist()" autocomplete="off"/>';
					$content .= '<span id="validation-text"></span>';
				}
				else if($columns[$i] == 'member_uname')
				{
					$content .= '<input id="'. $columns[$i] .'" name="'. $columns[$i] .'" value="'.$result[0][$columns[$i]].'" oninput="checkUsername()" autocomplete="off"/>';
					$content .= '<span id="validation-text"></span>';
				}
				else if($columns[$i] == 'admin_uname')
				{
					$content .= '<input id="'. $columns[$i] .'" name="'. $columns[$i] .'" value="'.$result[0][$columns[$i]].'" oninput="checkAdminUsername()" autocomplete="off"/>';
					$content .= '<span id="validation-text"></span>';
				}
				//textarea..
				else if($columns[$i] == 'content_text')
				{
					$content .= '<textarea id="editor" name="'. $columns[$i] .'" >'. $result[0][$columns[$i]] .'</textarea>';
				}
				//normaal
				else
					$content .= '<input name="'. $columns[$i] .'" value="'. $result[0][$columns[$i]] .'"/>';
			}
		}
		//new..
		else
		{
			for($i = 0; $i < count($columns); $i++)
			{
				$content .= '<label for="'. $columns[$i] .'">Change '. $columns[$i] .'</label>';
				
				//id - disabled
				if($i == 0)
					$content .= '<input name="'. $columns[$i] .'" value="" disabled/>';
				//ajax content_menu oninput ..
				else if($columns[$i] == 'content_menu')
				{
					$content .= '<input id="'. $columns[$i] .'" name="'. $columns[$i] .'" value="" oninput="checkPageExist()" autocomplete="off"/>';
					$content .= '<span id="validation-text"></span>';
				}
				else if($columns[$i] == 'member_uname')
				{
					$content .= '<input id="'. $columns[$i] .'" name="'. $columns[$i] .'" value="" oninput="checkUsername()" autocomplete="off"/>';
					$content .= '<span id="validation-text"></span>';
				}
				else if($columns[$i] == 'admin_uname')
				{
					$content .= '<input id="'. $columns[$i] .'" name="'. $columns[$i] .'" value="" oninput="checkAdminUsername()" autocomplete="off"/>';
					$content .= '<span id="validation-text"></span>';
				}
				//textarea
				else if($columns[$i] == 'content_text')
				{
					$content .= '<textarea id="editor" name="'. $columns[$i] .'" ></textarea>';
					$content .= '';
				}
				//normaal
				else
					$content .= '<input name="'. $columns[$i] .'" value=""/>';
			}
		}

		//buttons
		if($action == 'load')
		{
			$content .= '<input type="hidden" name="action" value="update"/>
						 <input type="button" id="button-submit" class="button-submit" name="confirm" onclick="confirmMessage(this)" value="Change"/>
					 	 <input type="hidden" name="row" value="'. $row .'"/>';
		}
		else if($action == 'new')
		{
			$content .= '<input type="hidden" name="action" value="insert"/>
						 <input type="button" id="button-submit" class="button-submit" name="confirm" onclick="confirmMessage(this)" value="New"/>';
		}
			
		
		$content .= '<input type="hidden" name="page" value="'. $page .'"/>';
		$content .= '</form></div>';
		
		return $content;
	}
	
	public function showLoginForm($error)
	{
		return '
			<html>
			<head>
				<title>Login</title>
				<link href="css/login.css" rel="stylesheet" type="text/css" />
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
				<meta name="author" content="'. $this->author .'">
				<title>'. $this->title .'</title>
				<link href="'. $this->cssFile .'" rel="stylesheet" type="text/css" />
				<link type="text/css" rel="stylesheet" href="_js/jQuery-TE_v.1.3.5/jquery-te-1.3.5.css">
				<script type="text/javascript" src="http://code.jquery.com/jquery.min.js" charset="utf-8"></script>
				<script type="text/javascript" src="_js/jQuery-TE_v.1.3.5/jquery-te-1.3.5.min.js" charset="utf-8"></script>
						
				<script src="_js/check.js"></script>	
				<script src="_js/confirm.js"></script>		

				<script type="text/javascript" charset="utf-8">
				$(document).ready(function() {
					$("#editor").jqte();
				});
				</script>
						
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
					<div id="message">'. $this->message .'</div>
					<div id="content">' . $this->content . '</div>
					<div id="footer">'. $this->footer .'</br><i>Logged in as: ' . $_SESSION['username'] . '</i></div>
				</div>
				
			</div>
			</body>
			</html>';
		
		return $pagina;
	}
}