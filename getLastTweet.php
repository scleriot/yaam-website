<?php 
// Last tweet (footer)
$sUrl = "http://twitter.com/statuses/user_timeline/144516001.xml?count=1";

if( $oXML = simplexml_load_file( $sUrl ) )
{
    foreach( $oXML->status as $oStatus )
    {
		$file = fopen('/home/www/new.yaam.mobi/lasttweet.txt', 
'w');
		fputs($file, $oStatus->text."\n");
		fputs($file, $oStatus->id);
		fclose ($file);
    }
}
?>
