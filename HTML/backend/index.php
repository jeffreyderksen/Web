<?php
include_once('framework_backend.class.php');
include_once('../include/login.class.php');

$framework = new FrameWorkBackend();

echo $framework->display();

//echo phpinfo();