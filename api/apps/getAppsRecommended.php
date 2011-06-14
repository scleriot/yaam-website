<root>
<?php

include_once("../../configuration/configuration.php");

include_once("../../functions/apps.php");
include_once("../../functions/functions.php");

checkYPass();

$sdk=$_GET['sdk'];
$lang=$_GET['lang'];
$terminal=$_GET['terminal'];

$apps=apps_recommended($sdk,$lang,$terminal);
foreach($apps as $app)
{
	echo "<app><name data=\"".$app['name']."\"/><price data=\"".$app['price']."\"/><icon data=\"".$app['icon']."\"/><id data=\"".$app['id']."\"/><rating data=\"".$app['rating']."\"/></app>";
}

?>
</root>