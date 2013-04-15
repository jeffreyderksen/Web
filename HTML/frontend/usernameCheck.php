<?php
// Dit bestand werkt in combinatie met checkUsername.js om aan te geven of een username al in gebruik is.
include_once ('../include/database_handler.class.php');

// Maakt een verbinding met de database
$db = new DatabaseHandler();
if(!$db->openConnection('mysql:dbname=gametriangle;host=localhost', 'test', 'test'))
{
echo '<p style="color: red">Error connecting to database.</p>';	
}

// Krijgt de username uit de checkUsername.js, kijkt of die al in de database bestaat
	$un = $_GET["un"];
	$param = array(':un' => $un);
	$query = 'SELECT member_uname FROM member WHERE member_uname=:un';
	$check = $db->executeQuery($query,$param)->fetch();
	// Als $check geen gegevens bevat is de username nog niet in gebruik en wordt er een positief antwoord naar het javascript gestuurd. 
	if(empty($check)) 
	{
		echo 'This username is available for use';
	} else 
	{
		echo 'This username is already in use';
	}	
?>