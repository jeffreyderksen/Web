<?php
class ContentPage 
{
	//layout
	private $menu;
	private $loginForm;
	private $content;
	
	//header
	private $title;
	private $cssFile;
	private $jScript;
	
	//connection 
	private $sqlConnection;
	
	
	
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
		$this->loginForm = $value;
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
									'.$this->loginForm.'
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