<?php

// on détermine le type de document, ici du xml
header ( "Content-type: text/xml" ) ;

include("../functions/functions.php");

mysql_connect("localhost", "yaamsql", "viyk14");
mysql_select_db("yaam");

$package=protect($_GET['app']);

if($package!="")
{
	$answer = mysql_query("SELECT users.username as name, comment,app.name as appname, comments.date FROM comments LEFT JOIN users ON users.id=userid LEFT JOIN applications as app ON app.packagename='$package' WHERE appid=(SELECT id FROM applications WHERE packagename='$package') WHERE comments.deleted=0 ORDER BY comments.id DESC LIMIT 20") or die(mysql_error());
	 
	$first=true;   
	
	$nb=0;
	

	while ( $data = mysql_fetch_array ( $answer ) ) { 
	    if($first)
	    {
	    	$rss = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>" ;
		$rss .= "<rss version=\"2.0\">" ;
		$rss .= "<channel>" ;
		$rss .= "<title>YAAM last comments for ".$data['appname']."</title>" ;
		$rss .= "<link>http://yaam.mobi</link>" ;
		$rss .= "<description>Alternative Android Market</description>" ;
		$first=false;
	    }
	    
	    // Récupère la date de publication de la news
	    $date= date ( "D, d M Y H:i:s" , strtotime( $data['date'] ) );
	    
	    // On crée l'item avec ces données
	    $rss .= "<item>" ;
	    $rss .= "<title><![CDATA[".$data['name']."]]></title>"; 
	    $rss .= "<link>http://yaam.mobi/app/?id=$package#comments</link>" ; 
	    $rss .= "<description><![CDATA[".$data['comment']."]]></description>" ; 
	    $rss .= "<pubDate>".$date." GMT</pubDate>" ; 
	    $rss .= "</item>" ;
	    
	    $nb++;
	}

	if($nb>0)
	{
		$rss .= "</channel>" ;
		$rss .= "</rss>" ;
	}

	// On affiche le contenu XML
	echo $rss;
}
else
{
	$answer = mysql_query("SELECT users.username as name, comment,comments.date,app.packagename, app.name as appname FROM comments LEFT JOIN users ON users.id=userid LEFT JOIN applications as app ON app.id=comments.appid WHERE comments.deleted=0 ORDER BY comments.date DESC LIMIT 30") or die(mysql_error());
	    
	$rss = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>" ;
	$rss .= "<rss version=\"2.0\">" ;
	$rss .= "<channel>" ;
	$rss .= "<title>YAAM last comments</title>" ;
	$rss .= "<link>http://yaam.mobi</link>" ;
	$rss .= "<description>Alternative Android Market</description>" ;

	while ( $data = mysql_fetch_array ( $answer ) ) { 
	    
	    // Récupère la date de publication de la news
	    $date= date ( "D, d M Y H:i:s" , strtotime( $data['date'] ) );
	    
	    // On crée l'item avec ces données
	    $rss .= "<item>" ;
	    $rss .= "<title><![CDATA[".$data['name']." - ".$data['appname']."]]></title>"; 
	    $rss .= "<link>http://yaam.mobi/app/?id=".$data['packagename']."#comments</link>" ; 
	    $rss .= "<description><![CDATA[".$data['comment']."]]></description>" ; 
	    $rss .= "<pubDate>".$date." GMT</pubDate>" ; 
	    $rss .= "</item>" ;
	}

	$rss .= "</channel>" ;
	$rss .= "</rss>" ;

	// On affiche le contenu XML
	echo $rss;
}

?>
