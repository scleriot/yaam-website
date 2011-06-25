<?php session_start();

function isDev($userid)
{
	global $bdd;
	
	$userid=intval($userid);	
	

	
	$answer = $bdd->query("SELECT COUNT(*) AS nb FROM developers WHERE userid=$userid") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if($data['nb']==0)
			return false;
		else
			return true;
	}
}

 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
    <meta name="ROBOTS" content="INDEX, FOLLOW" /> 
    
    <title>YAAM | <?php echo $TITLE; ?></title> 
    <meta name="description" content="<?php echo _('Android Alternative market, get games and apps for your smartphone'); ?>" /> 
    <meta name="google-site-verification" content="0X7eNg0evpIpj9S028d5IsS1HeDgapId3PimvbseIyw" />
    <meta name="copyright" content="" /> 
    
    <link rel="alternate" type="application/rss+xml" title="Latest applications" href="http://yaam.mobi/rss/latest.php" />
    <link rel="alternate" type="application/rss+xml" title="Latest comments" href="http://yaam.mobi/rss/comments.php" />

        
    <?php 
	if($index)
	{
	?>
    	<link rel="stylesheet" href="/css/style_index.css" type="text/css" />
    <?php
	}
    else
    {
    ?>
    	<link rel="stylesheet" href="/css/style.css" type="text/css" />
    <?php
    }
    ?>
    
    
    <script type="text/javascript" src="/js/jquery.js"></script>
	<link rel="stylesheet" href="/css/fancybox.css" type="text/css" media="screen" />
	
	<script type="text/javascript" src="/js/jquery-ui.js"></script>
	<link rel="stylesheet" href="/css/humanity/jquery-ui.css" type="text/css" />

	<script>
	$(document).ready(function() {
		
		$("a.modalbox").click(function(event){
			var url=$(this).attr("href");
			event.preventDefault();
			$('#modal').dialog({
	            modal: true,
	            open: function ()
	            {
	                $("#modal").load(url);
	            },         
	            title: '',
	            width: 800,
	            position: 'top'
        	});
		});
		
		<?php
		if(strlen($_SESSION['message'])>0)
		{
		?>
			$("#notif").dialog({
				modal: true,
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
					}
				}
			});
		<?php
		}
		?>
	});
	</script>
	
  </head> 
 
<body>

<?php
/////NOTIFICATION !!!////
if(strlen($_SESSION['message'])>0)
{
	//echo '<a id="inline" href="#notif"></a> <div style="display:none"><div id="notif">'.$_SESSION['message'].'</div></div>';

	echo '<div id="notif" title="'._("Notification").'"><p>'.$_SESSION['message'].'</p></div>';

	$_SESSION['message']="";
}
?>

<div id="modal"></div>

<!--=== Wrapper ===--> 
 <div id="wrapper"> 
 
<!--=== Header, logo  ===--> 
<div id="header"> 

	  <ul id="speedbar"> 
             <li><form method="get" action="/search.php"> 
		<input id="q" name="q" size="15" type="text" onfocus="if(this.value=='<?php echo _("Search"); ?>...')this.value='';" onblur="this.value='<?php echo _("Search"); ?>...';" value="<?php echo _("Search"); ?>..."/>	


	</form> </li>
		     <!--<li><a href="/category/"><?php echo _("Categories"); ?></a></li>-->
		     <li><a href="/"><?php echo _("Home"); ?></a></li>
		     <li><a href="http://blog.yaam.mobi/"><?php echo _("Blog"); ?></a></li>
		     <li><a href="/about.php"><?php echo _("About"); ?></a></li>
		     <li><a href="/contact.php"><?php echo _("Contact"); ?></a></li> 
		     
		     <?php
		     if($_connected)
		     {
		     	echo '<li><a href="/dev/">'._("Dev Panel").'</a></li>';
		     	echo '<li><a href="/account/profile.php">'._("Profile").'</a></li>';
		     	echo '<li><a href="/account/logout.php">'._("Logout").'</a></li>';
		     }
		     else
		     {
		     	echo '<li><a class="modalbox" href="/account/login.php">'._("Login").'</a></li>';
		     	echo '<li><a class="modalbox" href="/account/register.php">'._("Register").'</a></li>';
		     }
		     ?>
		     
             <!--<li><a href="?lang=fr"><img src="/img/lang/fr.png" alt="fr" /></a></li> 
		     <li><a href="?lang=en"><img src="/img/lang/en.png" alt="en" /></a></li>-->
		     
   	</ul><!-- end #speedbar--> 
	
	<div id="header-logo">  <h1><a href="/">YAAM.mobi</a></h1></div> 

<?php 
if($index)
{
?>
	<div id="bandeau"> 
		<div id="portable"> 
		<div id="imgs"> 
			<ul>
				<!-- IMAGES QUI CHANGENT DANS LE NEXUS ONE taille : 97x161 --> 
				<li><img src="/img/screen1.png" alt="Screen 1" /></li>
				<!--<li><img src="/img/screen2.png" alt="Screen 2" /></li>
				<li><img src="/img/screen3.png" alt="Screen 3" /></li>
				<li><img src="/img/screen4.png" alt="Screen 4" /></li> -->
			</ul> 
		</div>
		</div>
		
		<div id="bouton_dll"> 
			<a href="/api/yaam.apk" title="<?php echo _("Download"); ?>"><?php echo _("Download"); ?></a><br /><p><?php echo _("version"); ?> 2.1.3.1</p>
		</div>  
	
		<img src="/img/texte_bandeau.png" alt="YAAM, more than a conception" /><br /> 
		<span><?php echo _("YAAM helps you rediscover the power of Android.");?></span><br /> 
		<br /> 
		
	    <span style="float:right;padding-left:2px;">
	        <script type="text/javascript"><!--
	        google_ad_client = "pub-3126570380534852";
	        /* 234x60, date de création 09/05/10 */
	        google_ad_slot = "3251244970";
	        google_ad_width = 234;
	        google_ad_height = 60;
	        //-->
	        </script>
	        <script type="text/javascript"
	        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	        </script>
	        
	        <br />
	    </span>
	    <span><?php echo _("It's an alternative Android Market ! YAAM will allow you to download applications on your \"androphone\"");?></span> 
	    <br />
	    <img style="margin-left:190px;margin-top:5px;" src="/images/qrcode.png" />
    </div>
<?php
}
else
{
?>
<span style="float:right;margin-top: 80px">
   	<script type="text/javascript"><!--
	google_ad_client = "pub-3126570380534852";
	/* 234x60, date de création 09/05/10 */
	google_ad_slot = "3251244970";
	google_ad_width = 234;
	google_ad_height = 60;
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
</span>
<?php
}
?>


<!--=== Horizontal menu ===-->  

</div> 
  
<!--=== Content, sidebar, footer ===-->    
  <div id="main"> 

 
<!-- Content -->  
   <div id="content">

	<div id="topcontent"></div>


<?php
if($index)
{
?>
	<div id="appsToSee" class="boxgrid"> 
		<a href="http://yaam.mobi/app/?id=com.androgone.simwatchdog&promo=1"><img src="banners/banner_watchdroid.png"/></a> <a href="http://yaam.mobi/app/?id=com.tellmewhere&promo=1"><img src="banners/banner_tellmewhere.png"/></a> <a href="http://yaam.mobi/app/?id=com.ellismarkov.mobloxads&promo=1"><img src="banners/banner_moblox.png"/></a>
	</div>
<?php
}
?>

     <div class="post"> 