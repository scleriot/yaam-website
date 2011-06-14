<?php
session_start();

include_once("../configuration/configuration.php");
include("../configuration/includes.php");

include_once("../functions/user.php");
include_once("../functions/functions.php");


checkConnected();


if(isset($_POST['action']))
{
	if($_POST['action']=="informations")
	{
		if(user_update($_userid, $_POST['email'],$_POST['newsletter'],$_POST['adult']))
			message(_("Your profile has been successfully updated!"),"/account/profile.php");
		else
			message(_("Error while updating your profile..."),"/account/profile.php");
	}
	
	else if($_POST['action']=="password")
	{
		if(user_changepassword($_userid, $_POST['pass1'],$_POST['pass2']))
			message(_("Your password has been successfully modified!"),"/account/profile.php");
		else
			message(_("Error while modifing your password..."),"/account/profile.php");
	}
}

$TITLE=_("Profile");

include("../configuration/header.php");

$infos=user_infos($_username);

if($infos['newsletter']==1)
	$newsletter="checked='checked'";

if($infos['adult']==1)
	$adult="checked='checked'";


echo "<h2>"._("Your profile")."</h2>";

?>

<br /><br />

<b><?php echo _("Enter your informations :"); ?></b><br /><br />

<form action="" method="POST" >

<input type="hidden" value="informations" name="action" />

<div class="form">
	<label for="email"><?php echo _("E-Mail"); ?></label><br /><input type="text" size="40" value="<?php echo $infos['email']; ?>" id="email" name="email" /><br /><br />
	
	<input type="checkbox" <?php echo $newsletter; ?> id="newsletter" name="newsletter" /> <label for="newsletter"><?php echo _("Receive newsletter"); ?></label><br /><br />
	
	<input type="checkbox" <?php echo $adult; ?> id="adult" name="adult" /> <label for="adult"><?php echo _("Show Adult applications (on website and application)"); ?></label><br /><br />

</div>

<br />
<p class="button"><input type="submit" value="<?php echo _('Change informations'); ?>" class="button" /></p>

</form>

<br /><br />

<b><?php echo _("Change your password :"); ?></b><br /><br />

<form action="" method="POST" >

<input type="hidden" value="password" name="action" />

<div class="form">
	<label for="pass1"><?php echo _("Password (more than 3 characters)"); ?></label><br /><input type="password" size="40" id="pass1" name="pass1" /><br /><br />
	
	<label for="pass2"><?php echo _("Re-type your password"); ?></label><br /><input type="password" size="40" id="pass2" name="pass2" /><br /><br /></div>

<br />
<p class="button"><input type="submit" value="<?php echo _('Change password'); ?>" class="button" /></p>

</form>


<?php
include("../configuration/footer.php");
?>