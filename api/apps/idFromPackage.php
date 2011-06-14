<?php
include_once("../../configuration/configuration.php");

include_once("../../functions/apps.php");
include_once("../../functions/functions.php");

checkYPass();

$package=$_GET['package'];

echo apps_idfrompackage($package);

?>
