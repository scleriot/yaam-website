<?php

function checkYPass()
{	
	global $_GET;
	global $YPASS;

	if($_GET['ypass']!="SdvQ0wRLzzGaSkJ" && $_GET['ypass']!=$YPASS)
		exit;
}


function isAdult($username)
{
	global $bdd;
	
	$username=protect($username);
	
	$answer = $bdd->query("SELECT adult FROM `users` WHERE UPPER(`username`)=UPPER('$username')");
	$row = $answer->fetch();
	return $row['adult'];
}

function string_decode($string)
{
	$str=str_replace("&","&#38;",html_entity_decode($string,ENT_NOQUOTES,"UTF-8"));
	
	$str=str_replace("<","&lt;",$str);
	$str=str_replace(">","&gt;",$str);

	
	return $str;
}

function protect ($str)
{
	return addslashes(htmlentities($str,ENT_COMPAT,"UTF-8"));
}

function size($size)
{
	if($size<1000000)
		return round($size/1000,1)."Ko";
	else
		return round($size/1000000,1)."Mo";
}

function language($lang)
{
	$lang=protect($lang);
	if($lang=='fra' || $lang=='fr')
		$lang='_fr';
	else
		$lang='';
	return $lang;
}

function message($msg,$redirect)
{
	global $_SESSION;
	
	$_SESSION['message']=$msg;
	
	header('Location: '.$redirect);
	
	exit;
}


function checkConnected()
{
	global $_connected;
	
	if(!$_connected)
		message(_("You must be connected to access this page!"),"/");
}

function isConnected()
{
	global $_connected;
	
	if(!$_connected)
		return false;
	else
		return true;
}

function checkEmail($email)
{
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		return false;
	}
	
	return true;
}






function crypte_fichier($chemin_fichier,$chaine_crypt,$chemin1_fichier){
  $lignecripte="";
  $bytes = 65536;//nombre de bytes par ligne de cryptage
  //remplit une ligne de cryptage de longueur 65536 bites
  for ($i = 0; $i <= floor($bytes/strlen($chaine_crypt)); $i++) $lignecripte.= $chaine_crypt;
  //ouvre le fichier a crypter en lecture
  //cree le nouveau fichier
 if (file_exists($chemin_fichier)){//verifie presence du fichier
  chmod($chemin_fichier,0777);//attribue tous droits
  $ancien = fopen($chemin_fichier, "rb");
  $nouveau = fopen($chemin1_fichier, "wb");
  // crypt le fichier et ecrie dans le nouveau fichier par ligne de 65536 bites
  while($line = fread($ancien, $bytes)){
    $line2 = $line ^ $lignecripte;//effectue un OU EXCLUSIF (XOR) sur les bits 10011s^ 10110=00101 
    fputs($nouveau, $line2);}
    // ferme les fichiers
    fclose($ancien);fclose($nouveau);
    unlink($chemin_fichier);//suprimme l'ancien fichier
    //chmod($chemin1_fichier,0666);
  }
}


///HANDLING TAGS//////
function strip_tags_attributes($string,$allowtags=NULL,$allowattributes=NULL)
{ 
    $string = strip_tags($string,$allowtags); 
    if (!is_null($allowattributes)) { 
        if(!is_array($allowattributes)) 
            $allowattributes = explode(",",$allowattributes); 
        if(is_array($allowattributes)) 
            $allowattributes = implode(")(?<!",$allowattributes); 
        if (strlen($allowattributes) > 0) 
            $allowattributes = "(?<!".$allowattributes.")"; 
        $string = preg_replace_callback("/<[^>]*>/i",create_function( 
            '$matches', 
            'return preg_replace("/ [^ =]*'.$allowattributes.'=(\"[^\"]*\"|\'[^\']*\')/i", "", $matches[0]);'    
        ),$string); 
    } 
    return $string; 
} 

function replace_tags($str,$tags)
{
	$return="";
	$table=explode(",",$tags);
	
	for($i=0;$i<sizeof($table);$i++)
	{
		$str=preg_replace("%&lt;a href=&quot;([a-zA-Z0-9:/\.]+)&quot;&gt;%","<a href=\"$1\">",$str);
		
		$str=str_replace("&lt;".$table[$i]."&gt;","<".$table[$i].">",$str);
		$str=str_replace("&lt;/".$table[$i]."&gt;","</".$table[$i].">",$str);
	}
	
	return $str;
}

?>
