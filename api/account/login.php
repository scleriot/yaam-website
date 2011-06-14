<?php

include_once("../../configuration/includes.php");
include_once("../../configuration/configuration.php");

include_once("../../functions/user.php");
include_once("../../functions/functions.php");

checkYPass();

$password=$_GET['password'];
$username=$_GET['username'];

if(user_connect($username,$password))
{
	echo "ok";
}
else
{
	echo "error";
}

?>