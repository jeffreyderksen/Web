<?php
/* Dit php-script checkt of de pagina die de administrator toe wilt voegen al bestaat
 * door true of false terug te sturen naar AJAX.
*/
include_once('../../include/database_handler.class.php');

$db = new DatabaseHandler();
if(!$db->openConnection('mysql:dbname=gametriangle;host=localhost', 'test', 'test'))
	echo 'geen connectie';

//haal form variable op
$pagina_menu = $_GET['content_menu'];


if(!empty($pagina_menu))
{
	$query = 'SELECT content_menu FROM content WHERE content_menu=:content_menu';
	$param = array(':content_menu' => $_GET['content_menu']);
	$check = $db->executeQuery($query, $param)->fetchAll();
	
	if(empty($check[0]['content_menu']))
		echo true;
	else
		echo false;
}