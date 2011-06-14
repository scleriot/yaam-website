<?php

include("../configuration/includes.php");

if(isset($_GET['delete']))
{
	include_once("../configuration/configuration.php");
	
	include_once("../functions/comments.php");
	
	if(comments_delete($_GET['id'],$_userid))
		message(_("Comment is now removed!"),"/app/?id=".$_GET['app']);
	else
		message(_("Error while deleting the comment, are you sure that YOU are the author ?"),"/app/?id=".$_GET['app']);
}

echo _("Are you sure you want to delete this comment ?");

?>
<br /><br />
<?php
echo '<a href="/app/deleteComment.php?delete=1&app='.$_GET['app'].'&id='.$_GET['id'].'">'._("Yes, delete!").'</a>';
?>