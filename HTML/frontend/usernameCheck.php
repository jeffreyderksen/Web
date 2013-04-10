<?php
include_once ('../include/database_handler.class.php');

$db = new DatabaseHandler();
if(!$db->openConnection('mysql:dbname=gametriangle;host=localhost', 'test', 'test'))
{
echo '<p style="color: red">Error connecting to database.</p>';	
}

	$un = $_GET["un"];
	$param = array(':un' => $un);
	$query = 'SELECT member_uname FROM member WHERE member_uname=:un';
	$check = $db->executeQuery($query,$param);
	if($check[0]['member_uname'] == '')
	{
		echo 'This username is available for use';
	} else 
	{
		echo 'This username is already in use';
	}
	
?>