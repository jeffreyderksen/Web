<?php
include_once('framework.class.php');

$framework = new FrameWork();

//menu sturing
$page = $framework->getFormVariable('page');
$content = $framework->getContent($page);

//check login
if($framework->getFormVariable('submitted'))
{
	$framework->login();
}

echo $framework->display();

//echo phpinfo();t