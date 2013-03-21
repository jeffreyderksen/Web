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
	
	
	public function setMenu(ContentPart $value)
	{
		$this->menu = $value;
	}
	
	public function setLogin(ContentPart $value)
	{
		$this->loginForm = $value;
	}
	
	public function setContent(ContentPart $value)
	{
		$this->content = $value;
	}
	
	public function setTitle()
	{
		$this->title = $value;
	}
	
	public function setCss()
	{
		$this->cssFile = $value;
	}
	
	public function setJScript()
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
					<title>'.$this->title.'</title>
					</head>
					<body>
						<div id="container">
							<div id="header">
								<h1>GameTriangle</h1>
							</div>
							
							<div id="menu">
								<ul id="menulist">
									<li>Home</li>
									<li>Categories</li>
									<li>Subscribe</li>
								</ul>
								<div id="login">
									'.$this->loginForm->render().'
								</div>
							</div>
							
							<div id="content">
								'.$this->content->render().'
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