<?php
session_start();

include("../configuration/includes.php");

if(isset($_POST['title']))
{
	include_once("../configuration/configuration.php");
	
	include_once("../functions/developer.php");
	include_once("../functions/functions.php");
	
	$title=$_POST['title'];
	$comment=$_POST['comment'];

	$cid=$_GET['id'];
	
	developer_answerEmail($_userid,$cid,$title,$comment);
	
	message(_("Your comment has been sent by e-mail !"),"/dev/");
}
else
{
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="fr" />
    <title></title>
    </head>
<body>

<form method="post" action="/dev/answerByEmail.php?id=<?php echo $_GET['id']; ?>" >  

<div class="form">  
    <strong><?php echo _("Title");?></strong><br /><input name="title" size="20" class="input" /><br />
    <strong><?php echo _("Comment");?></strong><br /><textarea rows="3" cols="20" name="comment"></textarea>
</div>

<br />
	<p class="button"><input type="submit" value="<?php echo _('Answer by e-mail'); ?>" class="button" /></p>
	
</form>
	
</body>
</html>
<?php
}
?>