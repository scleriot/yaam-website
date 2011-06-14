<?php

include_once("../configuration/configuration.php");

include_once("../functions/user.php");
include_once("../functions/functions.php");

$id=$_GET['id'];
$username=$_GET['username'];

if(user_activate($username,$id))
{
	echo "Your account is now activated !<br />You can connect on application or website :) ";
}
else
{
	echo "An error has occured...";
}

?>