<?php
include_once ('frontend/dbHandler.class.php');
include_once('frontend/contentpart_1.class.php');

$htmlPage = new ContentPage;


$htmlPage->setTitle('home');
$htmlPage->setCss('../css/style.css');
//$htmlPage->setMenu('');
//$htmlPage->setLogin();
$htmlPage->setContent('home');

echo $htmlPage->render();
?>