<?php
session_start();

include("../configuration/includes.php");
include_once("../functions/functions.php");

$_SESSION['connected']=false;
$_SESSION['username']="";
$_SESSION['userid']=-1;
message(_("You're now logged out, see you soon!"),"/");
		
?>