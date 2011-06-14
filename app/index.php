<?php
session_start();

include_once("../configuration/configuration.php");
include("../configuration/includes.php");

include_once("../functions/apps.php");
include_once("../functions/comments.php");


$package=$_GET['id'];
$infos=apps_infos($package,$_language);

if(isset($_POST['comment']))
{
	comments_post($_POST['comment'],$infos['id'],$_userid,$_language,"Web");
	
	message(_("Your comment has been successfully added!"),"/app/?id=".$package);
}

$TITLE=$infos['appname'];

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
        <!-- Place this tag in your head or just before your close body tag -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

<!-- Place this tag where you want the +1 button to render -->
<g:plusone size="tall"></g:plusone>
        <a href="http://reddit.com/submit?url=http://yaam.mobi/app/?id=<?php echo $infos['packagename'];?>&title=<?php echo $infos['appname'];?>" target="_blank"><img src="/img/social/reddit.png" alt="Share on Reddit !" target="_blank" /></a>
        <a href="http://digg.com/submit?phase=2&url=http://yaam.mobi/app/?id=<?php echo $infos['packagename'];?>&title=<?php echo $infos['appname'];?>" target="_blank"><img src="/img/social/digg.png" alt="Share on Reddit !" target="_blank" /></a>
        
     </div> 
       
    <br />
    
    <div>  
    		<div style="float:right;padding-left:5px;">
    			<script type="text/javascript"><!--
			google_ad_client = "pub-3126570380534852";
			/* 120x240, YAAM apps */
			google_ad_slot = "1240912959";
			google_ad_width = 120;
			google_ad_height = 240;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
		</div>
        <i><?php echo _("Minimal Android version needed :");?> <?php echo $minSDK;?></i>  
        
        <br /><br />
           
        <?php echo apps_decode(utf8_encode($infos['description']));?>
        
        <br />
        
        <b><?php echo $widget;?></b>
    </div>
</div>

<br />

<div>
    <h2><?php echo _("Screenshots");?></h2>
        
        <br />
        
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
	        	if(strlen($screen)>0)
	        	{
		        	echo '<div class="screens">
		                <a class="modalbox" href="'.$screen.'"><img src="'.$screen.'" alt="" /></a>
		            </div>';
	        	}
	        }
        }
        else
        	echo _("This application got no screens!");
        ?>
</div>

<br />



<div id="comments">

<?php
if($_connected)
{
?>
    <h2><?php echo _("Write your own comment");?></h2>
    <br />
    <form method="post" name="form" action="">
        <input name="comment" type="hidden" value="ok" />
        <div class="form">  
    	<strong><?php echo _("Comment");?></strong><br /><textarea rows="3" cols="50" name="comment"></textarea>
		</div><br />
		<p class="button"><input type="submit" name="submit" class="button" value="<?php echo _("Submit");?>" /></p>        
    </form>
<?php
}
else
	echo '<i>'._("You must log in before adding a comment!").'</i>';
?>
</div>

<br />

<div>
    <h2><a style="border: none" href="/rss/comments.php?app=<?php echo $infos['packagename'];?>"><img src="/img/rss.png" alt="RSS" /></a> <?php echo _("Comments");?></h2>
    <br />
<?php
$comments=comments_get($infos['id'],$_language);

foreach($comments as $comment)
{
if(strlen($comment)>0)
{
?>
    <div class="app">	
    	<div class="comment"><u><?php echo $comment['username'];?></u> <i>(<?php echo $comment['phone'];?>)</i> <?php 
    	if($_userid==$comment['userid'])
    		echo '<a class="modalbox" href="/app/deleteComment.php?app='.$infos['packagename'].'&id='.$comment['id'].'">'._("(Delete your comment!)").'</a>';
    	?><br />
    	<?php echo date("d/m/Y H:i",$comment['date']);?></div>
        <?php 
        if($comment['deleted'])
        	echo _("<i>Comment removed by it author.</i>");
        else
        	echo $comment['comment'];
        ?>
	</div>
<?php
}
}
?>
</div>


<?php
include("../configuration/footer.php");
?>