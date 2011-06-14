<?php
include_once("../../configuration/configuration.php");

include_once("../../functions/apps.php");
include_once("../../functions/functions.php");

checkYPass();

$appid=$_GET['id'];
$username=$_GET['username'];
$price=$_GET['price'];

apps_buy($appid,$username,$price);

?>
