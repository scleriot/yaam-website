</div>

</div><!-- end #content--> 
   
  <hr class="clear" /> 
  
<!-- Footer -->  
<div id="footer"> 
 
	<ul id="speedbar_footer"> 
     <li id="home"><a href="/"><?php echo _("Home");?></a></li>
     <li><a href="/contact.php"><?php echo _("Contact");?></a></li>  
     <li><a href="/donate.php"><?php echo _("Donation");?></a></li>  
     <li><a href="/about.php"><?php echo _("About");?></a></li> 
     <li><a href="http://twitter.com/yaammarket" target="_blank"><?php echo _("Twitter");?></a></li> 
   	</ul><!-- end #speedbar--> 
   	
   	<?php
   	$fichier = $_SERVER['DOCUMENT_ROOT']."/lasttweet.txt";
	$file = fopen($fichier, "r");
	$lasttweet = @fgets($file);
	$idtweet = @fgets($file);
   	echo '<div id="twitter_footer"><p><a href="http://twitter.com/yaammarket/status/'.$idtweet.'" target="_blank">'.$lasttweet.'</a></p></div>';
   	?>
   	
   	
   <p id="copyright"> 
    Copyright: <a href="http://www.pixellostudio.com">Pixello Studio</a> &copy; - <small>2009 | 2011</small> &nbsp;-&nbsp; <a href="https://cms.paypal.com/us/cgi-bin/marketingweb?cmd=_render-content&content_ID=ua/AcceptableUse_full&locale.x=en_US">Usage Policy</a> &nbsp;-&nbsp; <?php echo _("All rights reserved"); ?> &nbsp; - &nbsp; <?php echo _("Integration & Design");?>: <a href="http://www.design-your-life.fr">Waaaou</a><br /><br />
    
    	<!-- FERank.fr / Analyse statistique -->
		<script type="text/javascript">
		var couleur = "noir";
		</script>
		<script src="http://www.ferank.fr/logo.js" type="text/javascript">
		</script>
		<noscript>
		<p>
		<a title="Affichage du FERank" href="http://www.ferank.fr/">
		<img style="border:none;" src="http://www.ferank.fr/hit.php" alt="Outil de mesure d'audience et de statistique professionnel" />
		</a>
		</p>
		</noscript>
		<!-- Fin de FERank.fr -->

    </p> 
    </div> 
  </div><!-- end #main --> 
 </div><!-- end #wrapper --> 

</body> 
</html>
