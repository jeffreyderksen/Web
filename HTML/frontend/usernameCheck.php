<?php
include_once ('dbHandler.class.php');

$db = new dbHandler();
$db->openConnection("localhost","User","bassie","gametriangle");

	$un = $_GET["un"];
	$check = $db->checkUserName($un);
	echo $check;


?>