<?php

include_once("configuration/configuration.php");
include("configuration/includes.php");

$TITLE=_("Home");
$index=true;

include("configuration/header.php");

include_once("functions/apps.php");
?>

<div id="latest">

<h3><?php echo _("Latest");?></h3><br />
<?php
$apps=apps_latest($_language);
foreach($apps as $app)
{
?>



    <div class="app">
    	<img src="<?php echo $app['icon'];?>" alt="<?php echo $app['name'];?>" class="icon" />
        <a href="/app/?id=<?php echo $app['packagename'];?>"><?php echo $app['name'];?></a>
        <br />
        <?php echo apps_rating($app['rating']); ?>
    </div>
<?php
}
?>
</div>


<div id="popular">
<h3><?php echo _("Popular");?></h3><br />
<?php
$apps=apps_popular($_language);
foreach($apps as $app)
{
?>
    <div class="app">
    	<img src="<?php echo $app['icon'];?>" alt="<?php echo $app['name'];?>" class="icon" />
        <a href="/app/?id=<?php echo $app['packagename'];?>"><?php echo $app['name'];?></a>
        <br />
        <?php echo apps_rating($app['rating']); ?>
    </div>
<?php
}
?>
</div>


<?php
include("configuration/footer.php");
?>