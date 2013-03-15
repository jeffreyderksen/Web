<?php
include_once('framework.class.php');
include_once('../include/database_handler.class.php');

$framework = new FrameWork();
$databaseHandler = new DataBaseHandler('localhost', 'guest', '12345', 'gametriangle');



$framework->setContent("Testt");

echo $framework->display();