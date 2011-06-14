<root>
<?php

include_once("../../configuration/configuration.php");

include_once("../../functions/apps.php");
include_once("../../functions/comments.php");
include_once("../../functions/functions.php");

checkYPass();

$appid=$_GET['appid'];
$lang=$_GET['lang'];
$username=$_GET['username'];

$infos=apps_infos($appid,$lang,$username);

$fees=0; // 0% for YAAM!!

echo "<app><fees data=\"$fees\"/><paypal data=\"".$infos['paypal']."\"/><name data=\"".string_decode($infos['appname'])."\"/><version data=\"".$infos['version']."\"/><packagename data=\"".$infos['packagename']."\"/><youtube data=\"".$infos['youtube']."\"/><size data=\"".size($infos['size'])."\"/><widget data=\"".$infos['widget']."\"/><price data=\"".$infos['price']."\"/><screens data=\"".$infos['screens']."\"/><dlCount data=\"".$infos['dlCount']."\"/><password data=\"".$infos['password']."\"/><description data=\"".string_decode ($infos['description'])."\"/><icon data=\"".$infos['icon']."\"/><id data=\"".$infos['appid']."\"/><rating data=\"".$infos['rating']."\"/><devname data=\"".$infos['devname']."\"/><devpaypal data=\"".$infos['paypal']."\"/><update data=\"".$infos['isUpdate']."\"/></app>";


$comments=comments_get($appid,$lang);

foreach($comments as $comment)
{
	echo "<comments><comment data=\"".string_decode($comment['comment'])."\"/><username data=\"".utf8_encode($comment['username'])."\"/><phone data=\"".utf8_encode($comment['phone'])."\"/></comments>";
}

?>
</root>
