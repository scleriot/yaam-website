<?php

include_once("../configuration/configuration.php");
include("../configuration/includes.php");

$TITLE=_("Developer Panel");

include("../configuration/header.php");
include_once("../functions/developer.php");
include_once("../functions/apps.php");

checkConnected();



if(isDev($_userid))
{
	
?>

<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
</script>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><?php echo _("Applications"); ?></a></li>
		<li><a href="#tabs-2"><?php echo _("Informations"); ?></a></li>
	</ul>
	
	<div id="tabs-2">
<?php
	
$infos=developer_infos(developer_id($_userid));

echo _("Developer : ").$infos["name"]."<br />";
//echo _("Money To Receive : ").$infos["moneyToReceive"]."€<br />";
echo _("Money Earnt : ").$infos["moneyEarnt"]."€<br />";
echo _("Paypal Adress : ").$infos["paypal"]."<br />";
?>

<br />

<?php

echo '<a class="modalbox" href="/dev/changeInformations.php">'._("Change your informations").'</a>';

?>

	</div>
	<div id="tabs-1">

<h3><?php echo _("Your Uploaded Apps");?></h3><br />

<?php echo '<a class="modalbox" href="/dev/addApp.php">'._("Add New App!").'</a>'; ?>

<br /><br />

<?php
$apps=developer_getApps(developer_id($_userid),$_language);
foreach($apps as $app)
{
?>



    <div class="app">
    	<img src="<?php echo $app['icon'];?>" alt="<?php echo $app['name'];?>" class="icon" />
        <a href="/dev/app.php?id=<?php echo $app['packagename'];?>"><?php echo $app['name'];?></a>
        <br />
        <?php echo apps_rating($app['rating']); ?>
        <br /><br />
        <?php echo $app['dlCount']." "._("downloads")." / ".$app['size']."<br />"; 
        echo _("Available for : "); 
        if($app['phone']==1)
        	echo _("| Phone |");
        if($app['tablet']==1)
        	echo _(" Tablet |");
        if($app['gtv']==1)
        	echo _(" Google TV |");
        ?>
        <br />
        <?php
        echo _("Price : ").$app['price']."€";
        ?>
        
    </div>
<?php
}

?>

	</div>
</div>

<?php

}
else if(isConnected())
{
	echo _("<a class='modalbox' href='/dev/activateDevAccount.php'>Activate your developer account, in order to upload applications you've developed, and join YAAM developers community!</a>");
}
else
	exit;
?>

<?php
include("../configuration/footer.php");
?>