<?php
//start session
session_start();

include_once('framework_backend.class.php');
include_once('include/login.class.php');
include_once('include/config.class.php');

$framework = new FrameWorkBackend();
$config = new Config();
$login = new Login();

/* ACTION */
$action = $framework->getFormVariable('action');

/* DEFAULT PAGE SETTINGS */
$framework->setCharset($config->getCharset());
$framework->setAuthor($config->getAuthor());
$framework->setCssFile($config->getCssFile());

/* CHECK LOGIN */
if($login->processLogin($action) != true)
{
	//show login form
	echo $framework->showLoginForm($login->error);
	return;
}

/* CHECK PAGE */
$page = $framework->getFormVariable('page');
if(empty($page))
	$page = 'dashboard';

/* HANDLE ACTION */
$framework->handleAction($page, $action);

/* USER LOGGED IN LOAD PAGE */
if(($error = $framework->loadPage($page)) == true)
{
	$framework->setTitle('page_title');
	$framework->setHeader('page_header');
	$framework->setFooter('page_footer');
	$framework->setContent('page_text');
	$framework->setMenu();
}
else
{
	//stop page render
	echo $error;
	return;
}

echo $framework->display();

//echo phpinfo();

