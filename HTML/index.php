<?php
include_once ('frontend/dbHandler.class.php');
include_once('frontend/contentpart_1.class.php');

$htmlPage = new ContentPage;

$page = $htmlPage->getFormVariable('page');

if(empty($page))
{
	$page = 'home';
}

$htmlPage->setTitle($page);
$htmlPage->setCss('../css/style.css');
$htmlPage->setMenu();
//$htmlPage->setLogin();
$htmlPage->setContent($page);

echo $htmlPage->render();
?>