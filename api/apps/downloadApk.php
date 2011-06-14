<?php

$APK_DIR_BASE = '../../apk/';
$APK_CRYPTED_NAME = '/application_crypt.apk';
$DOWNLOAD_NAME = 'application.apk';
$DECRYPT_PWD = 'yaamroxxandisthebest';

function headersDownload($nomfichier,$chemin1_fichier){
	//entete de header precise au navigateur l'envoi d'un fichier
	header("Content-disposition: attachment; filename=$nomfichier");
	header("Content-Type: application/force-download");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($chemin1_fichier));
	header("Pragma: no-cache");
	header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
	header("Expires: 0");
}

function decrypteFile($chemin1_fichier,$chaine_crypt){
	$bytes = 65536; //bite par ligne
	$lignecripte="";
	$chainecrypte=$chaine_crypt;
	//remplit une ligne de cryptage de longueur 65536 bites
	for ($i = 0; $i <= floor($bytes/strlen($chainecrypte)); $i++) $lignecripte.= $chainecrypte;
  	// ouvre le fichier
  	$file = fopen($chemin1_fichier, "rb");
  	while($line = fread($file, $bytes)){
    	$line2 = $line ^ $lignecripte;//effectue un OU EXCLUSIF (XOR) sur les bits 10011s^ 10110=00101 
    	// affichage du fichier
    	echo $line2;
  	}
}





include_once("../../configuration/configuration.php");

include_once("../../functions/apps.php");
include_once("../../functions/functions.php");

checkYPass();

$appid=$_GET['id'];
$username=$_GET['username'];


$password=apps_password($appid,$username);

headersDownload($DOWNLOAD_NAME,$APK_DIR_BASE.$password.$APK_CRYPTED_NAME);
decrypteFile($APK_DIR_BASE.$password.$APK_CRYPTED_NAME,$DECRYPT_PWD);
?>
