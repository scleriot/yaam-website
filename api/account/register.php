<?php

include_once("../../configuration/configuration.php");

include_once("../../functions/user.php");
include_once("../../functions/functions.php");

checkYPass();

$password=$_GET['password'];
$username=$_GET['username'];
$email=$_GET['email'];

if(user_exists($username,$email))
{
	echo "error";
}
else if(user_register($username,$password,$email))
{
	echo "ok";
}
else
	echo "error";

?>