<?php
include_once("../configuration/includes.php");
include_once("../configuration/configuration.php");

include_once("../functions/functions.php");

$package=protect($_GET['package']);
$name=protect($_GET['username']);
$password=protect($_GET['password']);


$answer = $bdd->query("SELECT COUNT(*) AS nb,id FROM applications WHERE UPPER(packagename)=UPPER('$package') GROUP BY id LIMIT 1");
$data = $answer->fetch();

if($data['nb']==0)
{
	echo '0';
	exit;
}

/*$answer = $bdd->query("SELECT COUNT(*) AS nb FROM appsByUsers WHERE userid=(SELECT id FROM users WHERE MD5(UPPER(CONCAT(username,'yaamisthebest')))='$name') AND appid=".$data['id']." AND uninstalled=0 LIMIT 1") or die(mysql_error());*/

$answer = $bdd->query("SELECT COUNT(*) AS nb FROM appsByUsers WHERE userid=(SELECT id FROM users WHERE SHA1(CONCAT(UPPER(username),'YAAMISTHEBEST'))='$name' AND password='$password')  AND appid=".$data['id']." AND uninstalled=0 LIMIT 1") or die(mysql_error());

while($data = $answer->fetch())
{
	echo $data['name'];
	if($data['nb']==0)
		echo "0";
	else
		echo "1";
}
?>
