<?php
include_once("../../configuration/configuration.php");

include_once("../../functions/apps.php");
include_once("../../functions/functions.php");

checkYPass();

$appid=$_GET['appid'];

$username=$_GET['username'];

if(apps_isbought($username,$appid))
	echo "bought";
else
	echo "not bought";

?>
