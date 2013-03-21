<?php
include_once('../include/database_handler.class.php');

class FrameWorkBackend
{
	private $databaseHandler;
	
	private $content;
	private $title;
	private $cssFile;
	private $header;
	private $footer;

	//constructor
	public function __construct()
	{
		//maak object voor de database handlerr
		$this->databaseHandler = new DataBaseHandler();
		$this->databaseHandler->openConnection('localhost', 'root', '', 'gametriangle');
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
		$pagina = '';
		$pagina .= '
		<html>
		<head>
			<title>Game Triangle</title>
			<link href="../css/style.css" rel="stylesheet" type="text/css" />
		</head>
		<body>
		<div id="container">
			<div id="header"><h1>Header</h1></div>
			<div id="menu"></div>
			<div id="content"><p>' . $this->content . '</p></p></div>
			<div id="footer"><h3>Footer</h3></div>
		</div>
		</body>
		</html>';
		
		//close connection
		$this->databaseHandler->closeConnection();
		
		return $pagina;
	}
}