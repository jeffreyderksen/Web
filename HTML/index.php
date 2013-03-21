<?php
include_once ('dbHandler.class.php');
include_once('contentpart_1.class.php');

$html = new HTMLpage;


$htmlPage->setTitle($label_title_home);
$htmlPage->setCss('css/stijl1.css');
$htmlPage->setHeader('This is a header');
$htmlPage->setContent(new contentPart2());
$htmlPage->setFooter('This is a Footer');

//Connection database

//1. Open Connection
include('../cgi-bin/connector.php');

//2. Define Query
$sqlQuery = "SELECT render_content FROM content";

//3. Execute Query
$result = $sqlConnection->executeQuery($sqlQuery);

//4. Process Result
if(is_object($result) && $result->num_rows > 0)
{
	$row = $result->fetch_assoc();

	$htmlPage->setContent($row['render_content']);
} else
{
	$htmlPage->setContent('No result in database');
}

//5. Close Connection
$sqlConnection->closeConnection();

//end connection database

echo $htmlPage->render();
?>