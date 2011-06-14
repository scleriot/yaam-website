<?php
include("../configuration/includes.php");


if(isset($_POST['name']))
{
	include_once("../configuration/configuration.php");
	
	include("../functions/apps.php");
	include("../functions/developer.php");
	include_once("../functions/functions.php");
	
	
	apps_add($_POST['name'],$_POST['namefr'],$_POST['cat'],$_FILES['icon'],$_POST['price'],$_POST['tags'],$_POST['description'],$_POST['descriptionfr'],$_FILES['application'],developer_id($_userid),$_POST['version']);
}
else
{
	
include_once("../configuration/configuration.php");
	
include("../functions/categories.php");

$cats1=categories_get(0,$_language,1);
$cats2=categories_get(1,$_language,1);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
<head>         
    <link rel="stylesheet" href="/css/style.css" type="text/css" /> 
        	
  </head> 
 
<body> 

<form method="post" action="/dev/addApp.php" enctype="multipart/form-data">
<div class="form" style="width:600px;"">
    <label for="name"><?php echo _("Default name (english)"); ?></label> <input type="text" id="name" name="name" /><br /><br />
    <label for="namefr"><?php echo _("French name"); ?></label> <input type="text" id="namefr" name="namefr" /><br /><br />
    
    <label for="icon"><?php echo _("Icon file"); ?></label> <input type="file" id="icon" name="icon" /><br /><br />
    
    <label for="price"><?php echo _("Price (let empty if free)"); ?></label> <input type="text" id="price" name="price" size="5" /><br /><br />
    
    <label for="tags"><?php echo _("Tags");?> : </label> <input type="text" id="tags" name="tags" /><br /><br />
    
    <label for="cat"><?php echo _("Category"); ?></label><br />
    <?php
    echo '<select name="cat" id="cat">';
  		foreach ($cats1 as $cat)
  		{
  			echo '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
  		}
  		foreach ($cats2 as $cat)
  		{
  			echo '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
  		}
    	
    echo '</select>';
    
    ?>

    <br /><br />
    <label for="file">APK : </label><input type="file" id="file" name="application" /><br /><br />
    <input type="hidden" name="MAX_FILE_SIZE" value="104857600">
    
    <label for="version"><?php echo _("Application Version"); ?> : </label> <input type="text" id="version" name="version" /><br />
    	<i><?php echo _("Only needed if you use a string from resource inside your Android Manifest."); ?></i><br /><br />
    
    <?php //echo _("Authorized HTML tags : ");  &lt;b&gt; &lt;i&gt; &lt;u&gt; &lt;s&gt; &lt;a&gt; &lt;em&gt; &lt;strong&gt; &lt;ul&gt; &lt;li&gt;<br /><br />
    ?>
    
    <label for="description">Description (en) : </label><br /><textarea name="description" id="description" rows="5" cols="80"></textarea><br /><br />
        
    <label for="descriptionfr">Description (fr) : </label><br /><textarea name="descriptionfr" id="descriptionfr" rows="5" cols="80"></textarea><br /><br />

	</div>

<br />

<p class="button"><input type="submit" value="<?php echo _('Add Application'); ?>" class="button" /></p>

</form>

</body>
</html>

<?php
}
?>
	
