<?php
session_start();

if (!empty($_GET['lang'])) { // Si l'utilisateur a choisi une langue
	switch ($_GET['lang']) { // En fonction de la langue, on crée une variable $langage qui contient le code
		case 'fr':
			$langage = 'fr_FR';
			break;
		case 'en':
			$langage = 'en_US';
			break;
		default:
			$langage = 'en_US';
			break;
	}
	
	putenv("LANG=$langage"); // On modifie la variable d'environnement
	setlocale(LC_ALL, $langage); // On modifie les informations de localisation en fonction de la langue
	
	$nomDesFichiersDeLangue = 'translations'; // Le nom de nos fichiers .mo
	
	bindtextdomain($nomDesFichiersDeLangue, "/locale/"); // On indique le chemin vers les fichiers .mo
	textdomain($nomDesFichiersDeLangue); // Le nom du domaine par défaut
}

$_language=getenv("LANG");


$_connected=$_SESSION['connected'];
$_username=$_SESSION['username'];
$_userid=$_SESSION['userid'];

//$_userid=1;

?>