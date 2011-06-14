<root>
<?php

include_once("../../configuration/configuration.php");

include_once("../../functions/categories.php");
include_once("../../functions/functions.php");

checkYPass();

$game=$_GET['game'];
$lang=$_GET['lang'];

$cats=categories_get($game,$lang);

foreach($cats as $cat)
{
	echo "<cat><name data=\"".$cat['name']."\"/><id data=\"".$cat['id']."\"/></cat>";
}

?>
</root>
