<root>
<?php

include_once("../../configuration/configuration.php");

include_once("../../functions/categories.php");
include_once("../../functions/functions.php");

checkYPass();

$game=$_GET['game'];
$lang=$_GET['lang'];
$sdk=$_GET['sdk'];
$order=$_GET['order'];
$page=$_GET['page'];
$paid=$_GET['paid'];
$terminal=$_GET['terminal'];

$sex=1;

$apps=categories_apps($_GET['catid'],$lang,$sdk,$order,$page,$paid,$terminal,$sex);

foreach($apps as $app)
{
	echo "<app><name data=\"".string_decode($app['appname'])."\"/><price data=\"".$app['price']."\"/><icon data=\"".$app['icon']."\"/><id data=\"".$app['id']."\"/><rating data=\"".$app['rating']."\"/></app>";
}

?>
</root>
