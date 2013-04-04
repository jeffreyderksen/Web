<?php
//start session
session_start();

include_once('framework_backend.class.php');
include_once('include/login.class.php');
include_once('include/config.class.php');

$framework = new FrameWorkBackend();
$config = new Config();
$login = new Login();

/* DEFAULT PAGE SETTINGS */
$framework->setCharset($config->getCharset());
$framework->setMetaKeywords($config->getMetaKeywords());
$framework->setMetaDescription($config->getMetaDescription());
$framework->setAuthor($config->getAuthor());
$framework->setCssFile($config->getCssFile());

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

