<root>
<?php

include_once("../../configuration/configuration.php");

include_once("../../functions/apps.php");
include_once("../../functions/functions.php");

checkYPass();

$game=$_GET['game'];
$lang=$_GET['lang'];
$sdk=$_GET['sdk'];
$order=$_GET['order'];
$page=$_GET['page'];
$paid=$_GET['paid'];
$terminal=$_GET['terminal'];

$apps=apps_list($_GET['username'],$lang,$sdk,$order,$page,$paid,$terminal);

foreach($apps as $app)
{
	echo "<app><name data=\"".string_decode($app['appname'])."\"/><package data=\"".$app['packagename']."\"/><version data=\"".$app['version']."\"/><price data=\"".$app['price']."\"/><icon data=\"".$app['icon']."\"/><id data=\"".$app['id']."\"/><rating data=\"".$app['rating']."\"/></app>";
}

?>
</root>
