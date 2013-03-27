<?php
//start session
session_start();

include_once('framework_backend.class.php');
include_once('include/login.class.php');

$framework = new FrameWorkBackend();
$login = new Login();

/* CHECK LOGIN */
$action = $framework->getFormVariable('action');
if(($error = $login->processLogin($action)) != 'succes')
{
	//show login form
	echo $framework->showLoginForm($error);
	return;
}

/* CHECK PAGE */
$page = $framework->getFormVariable('page');
if(empty($page))
	$page = 'dashboard';

/* CHECK ACTION */
$framework->handleAction($page, $action);

/* USER LOGGED IN LOAD PAGE */
$framework->setTitle('title', $page);
$framework->cssFile('css', 'default');
$framework->setHeader('header', $page);
$framework->setMenu('menu', null);
$framework->setContent($page);
$framework->setFooter('footer', $page);

echo $framework->display();

//echo phpinfo();

