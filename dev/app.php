<?php
session_start();

include_once("../configuration/configuration.php");
include("../configuration/includes.php");

include_once("../functions/apps.php");
include_once("../functions/comments.php");
include_once("../functions/developer.php");


checkConnected();
checkDev($_userid);

$package=$_GET['id'];
$infos=apps_infos($package,$_language);

checkDevOfApp($_userid,$infos['id']);

if(isset($_FILES['screen']))
{	
	apps_addScreen($_FILES['screen'],$_GET['appid'],$_GET['id']);
}
else if(isset($_GET['screen']))
{
	apps_deleteScreen($_GET['screen'],$_GET['appid'],$_GET['id']);
}
else
{

$TITLE="Developer panel | ".$infos['appname'];

include("../configuration/header.php");

switch ($infos['minSdk'])
{
	case 3:
		$minSDK="Android 1.5 Cupcake";
		break;
	case 4:
		$minSDK="Android 1.6 Donut";
		break;
	case 5:
		$minSDK="Android 2.0 Eclair";
		break;	
	case 6:
		$minSDK="Android 2.0.1 Eclair";
		break;	
	case 7:
		$minSDK="Android 2.1 Eclair";
		break;	
	case 8:
		$minSDK="Android 2.2 Froyo";
		break;	
	case 9:
		$minSDK="Android 2.3 Gingerbread";
		break;
	default:
		$minSDK="Unknow";
		break;
}

if($infos['widget']==1)
	$widget=_("This app got a widget, long press on your home to get it!");

?>

<h2>Developer panel : <?php echo $infos['appname'];?></h2>

<br /><br />

<ul>
<?php echo '<li><a class="modalbox" href="/dev/changeAppInformations.php?id='.$package.'">'._("Modify application informations").'</a></li>'; ?>
<?php echo '<li><a class="modalbox" href="/dev/updateApk.php?id='.$package.'">'._("Update application (APK)").'</a></li>'; ?>
</ul>

<br /><br />

<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
</script>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><?php echo _("Informations"); ?></a></li>
		<li><a href="#tabs-2"><?php echo _("Screens"); ?></a></li>
		<li><a href="#tabs-3"><?php echo _("Analytics"); ?></a></li>
		<li><a href="#tabs-4"><?php echo _("Comments"); ?></a></li>
	</ul>
	
	<div id="tabs-1">

<div id="tapp">
	<div class="appv">
	<span style="float:right; margin: 5px"><a style="border: none" href="market://details?id=<?php echo $infos['packagename'];?>"><img src="http://qrcode.kaywa.com/img.php?s=3&d=yaam://details?id=<?php echo $infos['packagename'];?>" alt="qrcode"  /></a><br /><br />
	<!--<a href="/windows/installFromWeb.php?id=<?php echo $infos['packagename'];?>">Install on my phone</a>--></span>
        <img src="<?php echo $infos['icon'];?>" alt="<?php echo $infos['appname'];?>" class="icon" />
        <b><?php echo $infos['appname'];?></b> <i>v<?php echo $infos['version'];?> <?php echo _("by");?> <?php echo $infos['devname'];?></i> <!-- - <a href="/category/?id=<mx:text id="category"/>"><mx:text id="categoryName"/></a>-->
        <br />
        <?php echo $infos['dlCount'];?> <?php echo _("downloads");?>
        <br />
        <?php if($infos['price']==0) echo _("Free!");
        	  else echo $infos['price'].'&euro;';?>
        <br />
        <mx:text id="rating" />
        <?php echo apps_rating($infos['rating']);?>
        <!--<mx:text id="howto" />-->
	
	<br /><br />
	
        <a href="http://twitter.com/share?via=yaammarket&text=<?php echo _('Check this YAAM app').' : '.$infos['appname'].' !';?>" target="_blank"><img src="/img/social/twitter.png" alt="Tweet this !" /></a><!--<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>--> <!--<a href="https://twitter.com/?status=<mx:text id="lang_check"/> <mx:text id="name"/> <mx:text id="lang_onyaam"/> http://yaam.mobi/app/?id=<mx:text id="package"/>" target="_blank"><img src="/img/social/twitter.png" alt="Tweet this !" /></a>-->
        <a href="http://www.facebook.com/share.php?u=http://yaam.mobi/app/?id=<?php echo $infos['packagename'];?>&t=<?php echo $infos['appname'];?>" target="_blank"><img src="/img/social/facebook.png" alt="Share on facebook !" target="_blank" /></a>
        <a href="http://reddit.com/submit?url=http://yaam.mobi/app/?id=<?php echo $infos['packagename'];?>&title=<?php echo $infos['appname'];?>" target="_blank"><img src="/img/social/reddit.png" alt="Share on Reddit !" target="_blank" /></a>
        <a href="http://digg.com/submit?phase=2&url=http://yaam.mobi/app/?id=<?php echo $infos['packagename'];?>&title=<?php echo $infos['appname'];?>" target="_blank"><img src="/img/social/digg.png" alt="Share on Reddit !" target="_blank" /></a>
        
     </div> 
       
    <br />
    
    <div>  
        <i><?php echo _("Minimal Android version needed :");?> <?php echo $minSDK;?></i>  
        
        <br /><br />
           
        <?php echo apps_decode($infos['description']);?>
        
        <br />
        
        <b><?php echo $widget;?></b>
    </div>
</div>

	</div>
	<div id="tabs-3">

<h2>Analytics</h2>
    
    <br />
    
    <div id="chartdownloads" style="width: 100%; height: 400px">You must activate Javascript</div>
    
    <br />

	<div id="chartlang" style="width: 100%; height: 400px">You must activate Javascript</div>

	<br />
	
	<div id="chartphones" style="width: 100%; height: 400px">You must activate Javascript</div>

	<br /><br /><br /><br /><br />
	
	
<?php

//Analytics


/////////////DOWNLOADS/////////////
$values="";
$days="";

$startTime = mktime(0, 0, 0, date('m'), date('d')-1, date('Y'));
$endTime = mktime(23, 59, 59, date('m'), date('d')-1, date('Y'));
for($i=15;$i>=0;$i--)
{
	$startTime = mktime(0, 0, 0, date('m'), date('d')-$i, date('Y'));
	$endTime = mktime(23, 59, 59, date('m'), date('d')-$i, date('Y'));
	
	$days.="'".date("d/m",$startTime)."'";
	
	$values.=apps_getDlCount($package,$startTime,$endTime).",";
	
	if($i>0)
		$days.=',';
}

$js="chart1 = new Highcharts.Chart({
 chart: {
    renderTo: 'chartdownloads',
    defaultSeriesType: 'line'
 },
 title: {
    text: 'Downloads of last days'
 },
 tooltip: {
        formatter: function() {
            return '<b>'+this.y+' download(s)</b> on '+ this.x;
        }
    },
 xAxis: {
    categories: [";

$js.=$days;

$js.="]
 },
 yAxis: {
    title: {
       text: 'Downloads'
    }
 },
 series: [{
    name: 'Downloads',
    data: [";
    

$js.=$values;


$js.="]
 },]
});";

/////////LANG////////
$answer = $bdd->query("SELECT COUNT(*) AS nb FROM stats WHERE `appid`=(SELECT id FROM applications WHERE packagename='$package' LIMIT 1)") or die(mysql_error());
$data = $answer->fetch();
$count=$data['nb'];


$values="";
$answer = $bdd->query("SELECT lang FROM stats WHERE `appid`=(SELECT id FROM applications WHERE packagename='$package' LIMIT 1) GROUP BY lang ORDER BY COUNT(lang)/$count DESC LIMIT 25") or die(mysql_error());

while ($data =  $answer->fetch() ) { 
   if($data['lang']!="")
   {
    $answer2 = $bdd->query("SELECT COUNT(*) AS nb FROM stats WHERE `appid`=(SELECT id FROM applications WHERE packagename='$package' LIMIT 1) AND lang='".$data['lang']."'") or die(mysql_error());
    $data2 = $answer2->fetch();
    
    if(round($data2['nb']/$count*100,2)>0.5)
    {
	    $values.="{
	    name: '".$data['lang']."',
	    data: [".round($data2['nb']/$count*100,2)."]
		 },";
    }
   }
}
$js.="chart4 = new Highcharts.Chart({
 chart: {
    renderTo: 'chartlang',
    defaultSeriesType: 'column'
 },
 title: {
    text: 'Languages'
 },
 tooltip: {
        formatter: function() {
            return this.series.name+' : <b>'+this.y+'%</b>';
        }
    },
 xAxis: {
    categories: ['Value']
 },
 yAxis: {
    title: {
       text: 'Percentage'
    }
 },
 series: [";
    
$js.=$values;

$js.="]
});";


//////////DEVICES////////
$values="";
$answer = $bdd->query("SELECT phone FROM stats WHERE `appid`=(SELECT id FROM applications WHERE packagename='$package' LIMIT 1) GROUP BY phone ORDER BY COUNT(phone)/$count DESC LIMIT 25") or die(mysql_error());

while ( $data =  $answer->fetch()  ) { 
    $answer2 = $bdd->query("SELECT COUNT(*) AS nb FROM stats WHERE `appid`=(SELECT id FROM applications WHERE packagename='$package' LIMIT 1)AND phone='".$data['phone']."'") or die(mysql_error());
    $data2 = $answer2->fetch();
    
    if($data2['nb']/$count*100 > 1)
    {
	    $values.="{
    name: '".$data['phone']."',
    data: [".round($data2['nb']/$count*100,2)."]
 },";
    }
}

$js.="chart2 = new Highcharts.Chart({
 chart: {
    renderTo: 'chartphones',
    defaultSeriesType: 'column'
 },
 title: {
    text: 'Devices used'
 },
 tooltip: {
        formatter: function() {
            return this.series.name+' : <b>'+this.y+'%</b>';
        }
    },
 xAxis: {
    categories: ['Value']
 },
 yAxis: {
    title: {
       text: 'Percentage'
    }
 },
 series: [";
    
$js.=$values;

$js.="]
});";


?>

<script type="text/javascript" src="/js/highcharts.js"></script>
<script>
<?php echo $js; ?>
</script>


	</div>
	<div id="tabs-2">

<div>
    <h2><?php echo _("Screenshots");?></h2>
        
        <br />
        
<form action ="?id=<?php echo $infos['packagename']; ?>&appid=<?php echo $infos['id']; ?>" method="post" enctype="multipart/form-data">
<div class="form" style="width:600px;"">
	<label for="screen">New Screen : </label><input type="file" id="screen" name="screen" />
</div><br />
	<p class="button" style="width:600px;" ><input type="submit" style="" value="<?php echo _('Add new screen'); ?>" class="button" /></p>
</form>
        
<br /><br />
        <?php
        
        $screens=split(";",$infos['screens']);
        
        $i=0;
        foreach($screens as $screen) { 
		  if($screen == "") { 
		    unset($screens[$i]); 
		  } 
		  $i++;
		} 
		$screens = array_values($screens); 
        
        if(sizeof($screens)>0)
        {
	        foreach($screens as $screen)
	        {
	        	echo '<div class="screens" style="float:left">
	                <a class="modalbox" href="'.$screen.'"><img src="'.$screen.'" alt="" /></a><br />
	                <a href="?id='.$infos['packagename'].'&appid='.$infos['id'].'&screen='.$screen.'">'._("Delete").'</a>
	            </div>';
	        }
        }
        else
        	echo _("This application got no screens!");
        ?>
</div>

	</div>
	<div id="tabs-4">

<div id="comments">

</div>

<br />

<div>
    <h2><a style="border: none" href="/rss/comments.php?app=<?php echo $infos['packagename'];?>"><img src="/img/rss.png" alt="RSS" /></a> <?php echo _("Comments");?></h2>
    <br />
<?php
$comments=comments_get($infos['id'],$_language);

foreach($comments as $comment)
{
?>
    <div class="app">	
    	<div class="comment"><u><?php echo $comment['username'];?></u> <i>(<?php echo $comment['phone'];?>)</i> <?php 
    	if($_userid==$_userid)
    		echo '<a class="modalbox" href="/dev/answerByEmail.php?id='.$comment['id'].'">'._("(Answer by e-mail)").'</a>';
    	?><br />
    	<?php echo date("d/m/Y H:i",$comment['date']);?></div>
        <?php 
        if($comment['deleted'])
        	echo _("<i>Comment removed by it author.</i><br />Original : \"".$comment['comment']."\"");
        else
        	echo $comment['comment'];
        ?>
	</div>
<?php
}
?>
</div>

	</div>
</div>

<?php
include("../configuration/footer.php");

}
?>