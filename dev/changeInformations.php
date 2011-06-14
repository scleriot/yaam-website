<?php
include("../configuration/includes.php");


if(isset($_POST['devname']))
{
	include_once("../configuration/configuration.php");
	
	include("../functions/apps.php");
	include("../functions/developer.php");
	include_once("../functions/functions.php");
	
	if(developer_update(developer_id($_userid),$_POST['devname'],$_POST['paypal']))
		message(_("Informations were successfully updated."),"/dev/");
	else
		message(_("Error while updating your informations."),"/dev/");
}
else
{
	
	include_once("../configuration/configuration.php");
	
	include("../functions/developer.php");

$infos=developer_infos(developer_id($_userid));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr"> 
<head>         
    <link rel="stylesheet" href="/css/style.css" type="text/css" /> 
        	
  </head> 
 
<body> 

<form method="post" action="/dev/changeInformations.php">
<div class="form" style="width:600px;"">
    <label for="devname"><?php echo _("Developer Name"); ?></label> <input type="text" id="devname" value="<?php echo $infos['name']; ?>" name="devname" /><br /><br />
    <label for="paypal"><?php echo _("Paypal address (for paid apps)"); ?></label> <input type="text" value="<?php echo $infos['paypal']; ?>" id="paypal" name="paypal" /><br /><br />
</div>

<br />

<p class="button"><input type="submit" value="<?php echo _('Change'); ?>" class="button" /></p>

</form>

</body>
</html>

<?php
}
?>
	
