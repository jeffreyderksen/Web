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

/* USER LOGGED IN */
$page = $framework->getFormVariable('page');
if(empty($page))
	$page = 'dashboard';

/* LOAD PAGE */
$framework->setTitle($page);
$framework->cssFile('default');
$framework->setHeader($page);
$framework->setMenu('menu');
$framework->setContent($page);
$framework->setFooter($page);

echo $framework->display();

//echo phpinfo();

