<?php

$title = "YAAM comptes";
$tpl = 'infos_comptes.tpl';
$read = true;
$write = false;

$paypal=334.39;
$dons=12.16;

include("../header.php");

$answer = mysql_query("SELECT money FROM users WHERE money>0",$page->getConnect()) or die(mysql_error());

$paypalUsers=0;
while ( $data = mysql_fetch_array ( $answer ) ) { 
    $paypalUsers+=$data['money'];
}
$template->MxText('paypalUsers', $paypalUsers."€");



$answer = mysql_query("SELECT price FROM ads WHERE price>0",$page->getConnect()) or die(mysql_error());

$ads=0;
while ( $data = mysql_fetch_array ( $answer ) ) { 
    $ads+=$data['price'];
}
$template->MxText('ads', $ads."€");



$answer = mysql_query("SELECT moneyToReceive FROM developers WHERE moneyToReceive>0",$page->getConnect()) or die(mysql_error());

$paypalDev=0;
while ( $data = mysql_fetch_array ( $answer ) ) { 
    $paypalDev+=$data['moneyToReceive'];
}
$template->MxText('paypalDev', $paypalDev."€");


$answer = mysql_query("SELECT moneyWon FROM developers WHERE moneyWon>0",$page->getConnect()) or die(mysql_error());

$wonDev=0;
while ( $data = mysql_fetch_array ( $answer ) ) { 
    $wonDev+=$data['moneyWon'];
}
$template->MxText('wonDev', $wonDev."€");


$paypalReserved=$paypalDev+$paypalUsers;
$template->MxText('paypalReserved', $paypalReserved."€");








$template->MxText('dons', $dons."€");
$template->MxText('paypal', $paypal."€");



$earnings=$paypal-$paypalReserved;

$template->MxText('earnings', $earnings."€");


include("../footer.php");

?>
