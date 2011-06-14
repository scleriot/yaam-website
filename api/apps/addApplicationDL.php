<?php 

include_once("../../configuration/configuration.php");

include_once("../../functions/apps.php");
include_once("../../functions/functions.php");

checkYPass();

apps_addDL($_GET['appid'],$_GET['username'],$_GET['phone'],$_GET['lang'],$_GET['country'],$_GET['sdk']);

?>
