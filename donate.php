<?php

include_once("configuration/configuration.php");
include("configuration/includes.php");

$TITLE=_("Donate");

include("configuration/header.php");
?>

<h3>Make a donation</h3>
<br /><br />

You want to help us rule the world, or just thanks us for our work ? Make a donation ;) <br /><br />

<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="E7GVAVVV3HKP4">
<input type="image" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>

<br/><br/>

<?php
include("configuration/footer.php");

?>