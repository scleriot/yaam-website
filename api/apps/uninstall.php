<?php

include_once("../../configuration/configuration.php");

include_once("../../functions/apps.php");
include_once("../../functions/functions.php");

checkYPass();

apps_uninstall($_GET['username'],$_GET['appid']);
?>
