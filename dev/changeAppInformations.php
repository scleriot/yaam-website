<?php
include("../configuration/includes.php");


if(isset($_POST['name']))
{
	include_once("../configuration/configuration.php");
	
	include("../functions/apps.php");
	include("../functions/developer.php");
	include_once("../functions/functions.php");
	
	
	apps_update(apps_idfrompackage($_GET['id']),$_POST['name'],$_POST['namefr'],$_POST['cat'],$_FILES['icon'],$_POST['price'],$_POST['tags'],$_POST['description'],$_POST['descriptionfr']);
}
else
{
	
	include_once("../configuration/configuration.php");
	
	include("../functions/apps.php");
	include("../functions/categories.php");
	
$package=$_GET['id'];
$infos=apps_infos($package,$_language);

$cats1=categories_get(0,$_language,1);
$cats2=categories_get(1,$_language,1);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
<head>         
    <link rel="stylesheet" href="/css/style.css" type="text/css" /> 
        	
  </head> 
 
<body> 

<form method="post" action="/dev/changeAppInformations.php?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data">
<div class="form" style="width:600px;"">
    <label for="name"><?php echo _("Default name (english)"); ?></label> <input type="text" value="<?php echo $infos['name']; ?>" id="name" name="name" /><br /><br />
    <label for="namefr"><?php echo _("French name"); ?></label> <input type="text" value="<?php echo $infos['name_fr']; ?>" id="namefr" name="namefr" /><br /><br />
    
    <label for="icon"><?php echo _("Icon file"); ?></label> <input type="file" id="icon" name="icon" /><br /><br />
    
    <label for="price"><?php echo _("Price (let empty if free)"); ?></label> <input type="text" value="<?php echo $infos['price']; ?>" id="price" name="price" size="5" /><br /><br />
    
    <label for="tags"><?php echo _("Tags");?> : </label> <input type="text" value="<?php echo $infos['tags']; ?>" id="tags" name="tags" /><br /><br />
    
    <label for="cat"><?php echo _("Category"); ?></label><br />
    <?php
    echo '<select name="cat" id="cat">';
  		foreach ($cats1 as $cat)
  		{
  			if($infos['catid']==$cat['id'])
  				echo '<option value="'.$cat['id'].'" selected="selected">'.$cat['name'].'</option>';
  			else
  				echo '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
  		}
  		foreach ($cats2 as $cat)
  		{
  			if($infos['catid']==$cat['id'])
  				echo '<option value="'.$cat['id'].'" selected="selected">'.$cat['name'].'</option>';
  			else
  				echo '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
  		}
    	
    echo '</select>';
    
    ?>
    <br /><br />
       
    <label for="description">Description (en) : </label><br /><textarea name="description" id="description" rows="5" cols="80"><?php echo stripslashes($infos['descriptionOriginal']); ?></textarea><br /><br />
        
    <label for="descriptionfr">Description (fr) : </label><br /><textarea name="descriptionfr" id="descriptionfr" rows="5" cols="80"><?php echo stripslashes($infos['description_fr']); ?></textarea><br /><br />

	</div>

<br />

<p class="button"><input type="submit" value="<?php echo _('Update application informations'); ?>" class="button" /></p>

</form>

</body>
</html>

<?php
}
?>
	
