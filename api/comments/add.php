<?php 

include_once("../../configuration/configuration.php");

include_once("../../functions/comments.php");
include_once("../../functions/apps.php");
include_once("../../functions/functions.php");

checkYPass();

if($_GET['rating']!=0 )
	apps_rate($_GET['username'],$_GET['appid'],$_GET['rating']);

if(strlen($_GET['comment'])==0 && $_GET['rating']!=0)
{
	echo "ok";
	exit;
}

comments_post($_GET['comment'],$_GET['appid'],$_GET['username'],$_GET['lang'],$_GET['phone'],$_GET['sdk']);


	
echo "ok";
?>
