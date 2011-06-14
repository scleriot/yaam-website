<?php
session_start();

include("../configuration/includes.php");

if(isset($_POST['devname']))
{
	include_once("../configuration/configuration.php");
	
	include_once("../functions/developer.php");
	include_once("../functions/comments.php");
	include_once("../functions/functions.php");
	
	$devname=$_POST['devname'];
	
	developer_activate($_userid,$devname);
	
	message(_("Your developer account has been successfully activated !"),"/dev/");
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

<form action="/dev/activateDevAccount.php" method="POST">

<div class="form">
<label for="devname"><?php echo _("Developer name/society");?></label> <input type="text" name="devname" id="devname" /><br /><br />
</div>

<br />

<p class="button"><input type="submit" value="<?php echo _('Activate Dev Account'); ?>" class="button" /></p>

</form>

</body>
</html>
<?php
}
?>