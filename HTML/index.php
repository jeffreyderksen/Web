<?php
include_once('framework.class.php');

$framework = new FrameWork("leeg");

$content = $framework->getContent('home');

echo $framework->display();

//echo phpinfo();