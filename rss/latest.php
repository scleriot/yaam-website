<?php

// on détermine le type de document, ici du xml
header ( "Content-type: text/xml" ) ;

//include("../configuration/functions.php");

mysql_connect("localhost", "yaamsql", "viyk14");
mysql_select_db("yaam");

$answer = mysql_query("SELECT id,name,packagename,icon,description FROM applications ORDER BY id DESC LIMIT 10") or die(mysql_error());
    
$rss = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>" ;
$rss .= "<rss version=\"2.0\">" ;
$rss .= "<channel>" ;
$rss .= "<title>YAAM latest applications</title>" ;
$rss .= "<link>http://yaam.mobi</link>" ;
$rss .= "<description>Alternative Android Market</description>" ;

while ( $data = mysql_fetch_array ( $answer ) ) { 
    
    // Récupère la date de publication de la news
    $date= date ( "D, d M Y H:i:s" , strtotime( $data['date'] ) );
    
    // On crée l'item avec ces données
    $rss .= "<item>" ;
    $rss .= "<title><![CDATA[".$data['name']."]]></title>"; 
    $rss .= "<link>http://yaam.mobi/app/?id=".$data['packagename']."</link>" ; 
    $rss .= "<description><![CDATA[".$data['description']."]]></description>" ; 
    $rss .= "<enclosure url='".$data['icon']."' type='image/png'></enclosure>";
    $rss .= "<guid>".$data['id']."</guid>";
    $rss .= "<date>".$date."</date>";
    $rss .= "</item>" ;
}

$rss .= "</channel>" ;
$rss .= "</rss>" ;

// On affiche le contenu XML
echo $rss;

?>
