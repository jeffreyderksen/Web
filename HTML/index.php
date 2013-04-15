<?php
include_once('frontend/contentpart_1.class.php');
include_once ('frontend/user.class.php');

// Start de sessie die gebruikt wordt bij het inloggen op de site
session_start();

// Objecten van gebruikte classes aanmaken
$htmlPage = new ContentPage;
$user = new User();

// Slaat de opgevraagde pagina op in een variabele en stelt die vast in de contentpage voor later gebruik
$page = $htmlPage->getFormVariable('page');
$htmlPage->setPage($page);

// Als er geen page is meegegeven wordt die standaard op home ingesteld
if(empty($page))
{
	$page = 'home';
}

// Zet het CSS bestand vast
$htmlPage->setCss('../css/style.css');

// Kijkt of de gebruiker is ingelogd en past het menu daarop aan
if($user->isAuth()){
	$htmlPage->setMenu(2);
	$htmlPage->setLogin('greeting');
} else {
	$htmlPage->setMenu(1);
	$htmlPage->setLogin('login');
}	

// Stelt de variabele action vast die gebruikt wordt bij formulieren
$action = $htmlPage->getFormVariable('action');

// Als de registerpagina wordt geopend zullen twee javascript bestanden geladen worden
if($page == 'register')
{
	$htmlPage->setJScript('formvalidate.js', 'checkUsername.js');
}

// Gaat na of de inlog gegevens correct zijn en handelt daarnaar
if($action == 'verifylogin')
{
	$username = $htmlPage->getFormVariable('username');
	$password = $htmlPage->getFormVariable('password');
	$user->setAuth($username, sha1($password));
	
	if( !$user->setAuth($username, sha1($password)) )
	{
		$errorMessage = 'Username/Password is incorrect';
		$htmlPage->setError($errorMessage);
	}
}

// Als de highscores pagina wordt geopend kijkt het script of de gebruiker is ingelogd en handelt daarnaar
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

// Gebruiker wordt uitgelogd
if($action == 'logout')
{
	$user->removeAuth();
}

// Als het register formulier is ingevoerd, slaat dit de waardes op en stuurt die naar de database
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

// Voert de render functie uit
echo $htmlPage->render();
?>