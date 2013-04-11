<?php
include_once('frontend/contentpart_1.class.php');
include_once ('frontend/user.class.php');

session_start();

$htmlPage = new ContentPage;
$user = new User();

$page = $htmlPage->getFormVariable('page');

if(empty($page))
{
	$page = 'home';
}

$htmlPage->setCss('../css/style.css');
$htmlPage->setMenu();

$action = $htmlPage->getFormVariable('action');

if(isset($_GET['un']))
{
	echo 'test';
}	

if($page == 'high_scores')
{
	if($user->isAuth())
	{
		$htmlPage->setContent($page);
		$htmlPage->setTitle($page);
	} else {
		$htmlPage->setLoginPage();
		$htmlPage->setTitle($page);
	}
} 
else 
{
	$htmlPage->setTitle($page);
	$htmlPage->setContent($page);
}

if($page == 'register')
{
	$htmlPage->setJScript('formvalidate.js', 'checkUsername.js');
}

if($action == 'register')
{
	$firstname = $htmlPage->getFormVariable('fname');
	$lastname = $htmlPage->getFormVariable('lname');
	$uname = $htmlPage->getFormVariable('uname');
	$pword = sha1($htmlPage->getFormVariable('pword'));
	$cpassword = sha1($htmlPage->getFormVariable('confirmpword'));
	$email = $htmlPage->getFormVariable('email');
	if($pword == $cpassword)
	{
		$htmlPage->addUser($firstname, $lastname, $uname, $pword, $email);
	} else
	{
		echo 'Adding user did not work';
	}
}

if($action == 'verifylogin')
{
	$username = $htmlPage->getFormVariable('username');
	$password = $htmlPage->getFormVariable('password');
	$user->setAuth($username, sha1($password));
	
	if( !$user->setAuth($username, sha1($password)) )
		$errorMessage = 'Geen juiste gegevens ingevuld';
}

if($action == 'logout')
{
	$user->removeAuth();
}

if($user->isAuth())
{
	$htmlPage->setLogin('greeting');
} else { 
	$htmlPage->setLogin('login');
}





echo $htmlPage->render();
?>