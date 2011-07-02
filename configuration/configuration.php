<?php

include_once("credentials.php");

$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
$bdd = new PDO('mysql:host='.$HOST.';dbname='.$DB, $USER, $PASSWORD, $pdo_options);

$AAPT_DIR="/usr/bin/aapt";

?>
