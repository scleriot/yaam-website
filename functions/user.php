<?php

include_once("functions.php");

function user_connect($username,$password)
{
	global $bdd;
	
	$username=trim(protect ($username));
	$password=trim(protect ($password));
	
	$query = $bdd->query("SELECT COUNT(id) AS nb FROM `users` WHERE activated=1 AND UPPER(username) = UPPER('".$username."') AND password = '".$password."'") or die(mysql_error());
	
	$data=$query->fetch();
	if ($data['nb'] == 1)
	{
		$bdd->query("UPDATE users SET last_connect=".time()." WHERE UPPER(username) = UPPER('".$username."')");
		return true;
	}
	else
		return false;
}

function user_id($username)
{
	global $bdd;
	
	$username=trim(protect ($username));
	
	$query = $bdd->query("SELECT id FROM `users` WHERE UPPER(username) = UPPER('".$username."')") or die(mysql_error());
	
	$data=$query->fetch();
	return $data['id'];
}

function user_exists($username,$email)
{
	global $bdd;
	
	$username=trim(strtoupper (protect ($username)));
	$email=trim(strtoupper (protect ($email)));
		
	$query = $bdd->query("SELECT COUNT(id) AS nb FROM `users` WHERE UPPER(username) = '".$username."' OR UPPER(email) = '".$email."'") or die(mysql_error());
	
	$data=$query->fetch();
	if ($data['nb'] > 0)
	{
		return true;
	}
	else
		return false;
}

function user_resetpassword($email)
{
	global $bdd;
	
	$email=trim(protect ($email));
	
	
	$chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 

    while ($i <= 7) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 
    
    $password=sha1("yaamprotection".$pass."echoyaamemee");
	
	
	
	if(strlen($email)==0)
		return false;

	$query = $bdd->query("SELECT username FROM users WHERE UPPER(email)=UPPER('$email') AND activated=1 LIMIT 1") or die(mysql_error());
	$data=$query->fetch();
	
	$username=$data['username'];
	
	if(strlen($username)>0) //USER EXISTS
	{
		$query = $bdd->query("UPDATE users SET password='".$password."' WHERE UPPER(email)=UPPER('$email') LIMIT 1") or die(mysql_error());
		
		$headers ='From: "YAAM"<contact@yaam.mobi>'."\n"; 
	     $headers .='Reply-To: contact@yaam.mobi'."\n"; 
	     $headers .='Content-Type: text/html; charset="utf-8"'."\n"; 
	     $headers .='Content-Transfer-Encoding: 8bit'; 
	
	     $message ='<html><head><title>YAAM Password reset</title></head><body>Hi '.$username.',<br /><br />'.
	     'Your <a href="http://yaam.mobi">YAAM</a> password has been reset, it is now : '.$pass.'.<br /><br />'.
	     
	     'Please change it in your profile once you connect!<br /><br />'.
	     '<u><i>The YAAM team</i></u><br />'.
	     '<i>contact@yaam.mobi</i></body></html>'; 
	
	     mail($email, 'YAAM Password reset', $message, $headers);
	
		return true;
	}
	else
		return false;
}

function user_register($username,$password,$email)
{
	global $bdd;
	
	$username=trim(protect ($username));
	$email=trim(protect ($email));
	$password=trim(protect ($password));

	if(strlen($username)==0 || strlen($email)==0 || strlen($password)==0)
		return false;

	$query = $bdd->query("INSERT INTO users(username,password,email,date) VALUES('".$username."','".$password."','".$email."',".time().")") or die(mysql_error());
	
	$headers ='From: "YAAM"<contact@yaam.mobi>'."\n"; 
     $headers .='Reply-To: contact@yaam.mobi'."\n"; 
     $headers .='Content-Type: text/html; charset="utf-8"'."\n"; 
     $headers .='Content-Transfer-Encoding: 8bit'; 

          $message ='<html><head><title>YAAM Register</title></head><body>Welcome to YAAM, an alternative Android Market!<br /><br />'.

'Please keep this e-mail for your records. Your account information is as follows:<br /><br />'.

'----------------------------<br />'.
'Username: '.$username.'<br />'.
'E-mail : '.$email.'<br />'. 
'----------------------------'.
'<br /><br />'.
'Please visit the following link in order to activate your account:<br />'.
'<a href="http://yaam.mobi/account/activate.php?username='.$username.'&id='.$password.'">http://yaam.mobi/account/activate.php?username='.$username.'&id='.$password.'</a><br /><br />'.

'Your password has been securely stored in our database and cannot be retrieved. In the event that it is forgotten, you will be able to reset it using the email address associated with your account.<br /><br />'.

'Thank you for registering.<br /><br />'.

'Best regards,<br />'.
'<u><i>The YAAM team</i></u><br />'.
'<i>contact@yaam.mobi</i></body></html>';

     mail($email, 'YAAM register', $message, $headers);
	
	return true;
}

function user_activate($username,$id)
{
	global $bdd;
	
	$username=trim(protect ($username));
	$id=trim(protect ($id));
	
	$query = $bdd->query("SELECT COUNT(id) AS nb FROM `users` WHERE activated=0 AND UPPER(username) = UPPER('".$username."') AND password = '".$id."'") or die(mysql_error());
	
	$data=$query->fetch();
	if ($data['nb'] > 0)
	{
		$bdd->query("UPDATE users SET activated=1 WHERE UPPER(username) = UPPER('".$username."')");
		return true;
	}
	else
		return false;
}

function user_infos($username)
{
	global $bdd;
	
	$username=trim(protect($username));
	
	$query = $bdd->query("SELECT * FROM `users` WHERE UPPER(username) = UPPER('".$username."')") or die(mysql_error());
	
	$data=$query->fetch();
	
	return $data;
}


function user_update($userid, $email,$newsletter,$adult)
{
	global $bdd;
	
	$userid=intval($userid);
	
	if($newsletter=="on")
		$newsletter=1;
	else
		$newsletter=0;
		
	if($adult=="on")
		$adult=1;
	else
		$adult=0;

	$email=trim(protect ($email));
	
	if(!checkEmail($email))
		return false;
	
	$bdd->query('UPDATE users SET email="'.$email.'", newsletter='.$newsletter.', adult='.$adult.' WHERE id='.$userid.' LIMIT 1') or die(mysql_error());
		
	return true;
}

function user_changepassword($userid, $pass1,$pass2)
{
	global $bdd;
	
	$userid=intval($userid);
	
	$pass1=trim(protect ($pass1));
	$pass2=trim(protect ($pass2));
		
	if($pass1==$pass2 && strlen($pass1)>3)
	{
		$pass=sha1("yaamprotection".$pass1."echoyaamemee");
		
		$bdd->query('UPDATE users SET password="'.$pass.'" WHERE id='.$userid.' LIMIT 1') or die(mysql_error());
		return true;
	}
	else
		return false;
		
	return true;
}


?>