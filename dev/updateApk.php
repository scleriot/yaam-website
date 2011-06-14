<?php
include("../configuration/includes.php");


if(isset($_FILES['application']))
{
	include_once("../configuration/configuration.php");
	
	include("../functions/apps.php");
	include_once("../functions/functions.php");
	
	
	apps_updateApk($_FILES['application'],apps_idfrompackage($_GET['id']),$_GET['id'],$_POST['version']);
}
else
{

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
<head>         
    <link rel="stylesheet" href="/css/style.css" type="text/css" /> 
        	
  </head> 
 
<body> 

<form method="post" action="/dev/updateApk.php?id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data">
<div class="form" style="width:600px;"">
    <label for="file">APK : </label><input type="file" id="file" name="application" /><br /><br />
    <input type="hidden" name="MAX_FILE_SIZE" value="104857600">
    <label for="version"><?php echo _("Application Version"); ?> : </label> <input type="text" id="version" name="version" /><br />
    	<i><?php echo _("Only needed if you use a string from resource inside your Android Manifest."); ?></i><br /><br />
</div>

<br />

<p class="button"><input type="submit" value="<?php echo _('Update Application'); ?>" class="button" /></p>

</form>

</body>
</html>

<?php
}
?>
	
