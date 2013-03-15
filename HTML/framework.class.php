<?php
class FrameWork
{
	private $content;
	
	public function setContent($value)
	{
		$this->content = $value;
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
			<div id="header"><h1>Game Triangle</h1></div>
			<div id="menu">
			<ul>
				<li><a href="#">Home</a></li>
				<li><a href="#">Categorieën</a></li>
				<li><a href="#">Highscores</a></li>
				<li><a href="#">Inschrijven</a></li>
			</ul>
			</div>
			<div id="content"><p>' . $this->content . '</p></p></div>
			<div id="footer"><h3>Powered by Game Triangle</h3></div>
		</div>
		</body>
		</html>';
		
		return $content;
	}
}