<?php

include_once("configuration/configuration.php");
include("configuration/includes.php");

$TITLE=_("Search : ".$_GET['q']);

include("configuration/header.php");

include_once("functions/apps.php");
?>

<h3><?php echo _("Results for ").'"'.$_GET['q'];?>"</h3><br />
<?php
$apps=apps_search($_GET['q'],$_language,0,'top',$_GET['page'],-1,"");
foreach($apps as $app)
{
?>
    <div class="app">
    	<img src="<?php echo $app['icon'];?>" alt="<?php echo $app['appname'];?>" class="icon" />
        <a href="/app/?id=<?php echo $app['packagename'];?>"><?php echo $app['appname'];?></a>
        <br />
        <?php echo apps_rating($app['rating']); ?>
        <br /><br />
        <i><?php echo substr($app['description'],0,150); ?>[...]</i>
    </div>
<?php
}
?>


<?php
include("configuration/footer.php");
?>