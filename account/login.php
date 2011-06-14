<?php
session_start();

include("../configuration/includes.php");

if(isset($_POST['username']))
{
	include_once("../configuration/configuration.php");
	
	include_once("../functions/user.php");
	include_once("../functions/functions.php");
	
	$username=$_POST['username'];
	$password=sha1("yaamprotection".$_POST['password']."echoyaamemee");
	
	if(user_connect($username,$password))
	{
		$_SESSION['connected']=true;
		$_SESSION['username']=$username;
		$_SESSION['userid']=user_id($username);
		message(_("You're now logged in!"),"/");
	}
	else
	{
		message(_("Error while connecting. Check your username and password!"),"/");
	}
}
else if(isset($_POST['email']))
{
	include_once("../configuration/configuration.php");
	
	include_once("../functions/user.php");
	include_once("../functions/functions.php");
	
	$mail=$_POST['email'];
	
	if(user_resetpassword($mail))
		message(_("You're password is now reset, you're going to receive a mail with new password! Try not to lose it again ;) "),"/");
	else
		message(_("Error while reseting your password, it seems that user doesn't exist in our database!"),"/");
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

<form action="/account/login.php" method="POST">

<div class="form">
<label for="username"><?php echo _("Username");?></label> <input type="text" name="username" id="username" /><br /><br />
<label for="password"><?php echo _("Password");?></label> <input type="password" name="password" id="password" /><br /><br />
</div>

<br />

<p class="button"><input type="submit" value="<?php echo _('Login'); ?>" class="button" /></p>

</form>



<br />
<a href="#" onclick='$("#resetpass").show("slide")'>I forgot my password</a>


<div id="resetpass" style="display:none;">

<br /><br />In order to reset your password, you need to give your e-mail :<br /><br />

<form action="/account/login.php" method="POST">
<div class="form">
<label for="email"><?php echo _("Your e-mail");?></label> <input type="text" name="email" id="email" /><br /><br />
</div>

<br />

<p class="button"><input type="submit" value="<?php echo _('Reset password'); ?>" class="button" /></p>

</form>

</div>


</body>
</html>
<?php
}
?>