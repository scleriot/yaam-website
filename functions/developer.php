<?php

include_once("functions.php");


function checkDev($userid)
{
	global $bdd;
	
	$userid=intval($userid);	
	

	
	$answer = $bdd->query("SELECT COUNT(*) AS nb FROM developers WHERE userid=$userid") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if($data['nb']==0)
			exit;
		else
			return true;
	}
}



function checkDevOfApp($userid,$appid)
{
	global $bdd;
	
	$userid=intval($userid);	
	$appid=intval($appid);

	
	$answer = $bdd->query("SELECT id FROM developers WHERE userid=$userid LIMIT 1") or die(mysql_error());
	while($data = $answer->fetch())
	{
		$devid=$data['id'];
	}
	
	$answer = $bdd->query("SELECT COUNT(*) AS nb FROM applications WHERE developerid=$devid AND id=$appid") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if($data['nb']==0)
			exit;
		else
			return true;
	}
}

function developer_infos($devid)
{
	global $bdd;
	
	$devid=intval($devid);	
	
	$answer = $bdd->query("SELECT * FROM developers WHERE id=$devid") or die(mysql_error());
	while($data = $answer->fetch())
	{
		return $data;
	}
}

function developer_update($devid, $devname,$paypal)
{
	global $bdd;
	
	$devid=intval($devid);	
	$devname=protect($devname);
	$paypal=protect($paypal);
	
	if(strlen($devname)<2)
		message(_("Developer name must be 2 characters or higher!"),"/dev/");
	
	return $bdd->query("UPDATE developers SET name='$devname', paypal='$paypal' WHERE id=$devid") or die(mysql_error());
}


function developer_activate($userid, $devname)
{
	global $bdd;
	
	$userid=intval($userid);	
	$devname=protect($devname);
	
	if(strlen($devname)<2)
		message(_("Developer name must be 2 characters or higher!"),"/dev/");
	
	return $bdd->query("INSERT INTO developers(name,userid) VALUES('$devname',$userid)") or die(mysql_error());
}



function developer_id($userid)
{
	global $bdd;
	
	$userid=intval($userid);	
	
	$answer = $bdd->query("SELECT id FROM developers WHERE userid=$userid") or die(mysql_error());
	while($data = $answer->fetch())
	{
		return $data['id'];
	}
}

function developer_answerEmail($userid,$cid,$title,$content)
{
	global $bdd;
	
	$userid=intval($userid);	
	$cid=intval($cid);	
	
	$answer = $bdd->query("SELECT developers.name,users.email FROM developers LEFT JOIN users ON users.id=$userid WHERE userid=$userid") or die(mysql_error());
	while($data = $answer->fetch())
	{
		$devname=$data['name'];
		$devmail=$data['email'];
		
		$answer = $bdd->query("SELECT email, comments.comment FROM comments LEFT JOIN users ON users.id=comments.userid WHERE comments.id=$cid") or die(mysql_error());
		while($data = $answer->fetch())
		{
			$email=$data['email'];
			$comment=$data['comment'];
			
			$message = "This email is an answer from the developer ".$devname." about comment you post on Android YAAM market\n--------------\n\n".$content."\n\n--------------\nOriginal comment:\n\n".$comment;
			
			$header = "Reply-to: ".$devmail."\nFrom: ".$devmail."\nMIME-Version: 1.0\nContent-type: text/plain; charset=UTF-8\nDate: ".date('r', time());
		
			return mail ($email, $title, $content, $header);
		}
	}
}

function developer_getApps($devid,$lang)
{
	global $bdd;
	
	$devid=intval($devid);	
	
	$lang=protect($lang);
	if($lang=='fr')
		$lang='_fr';
	else
		$lang='';
	
	$apps=array();
	$i=0;
	
	$answer = $bdd->query("SELECT * FROM applications WHERE developerid=$devid") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if(strlen($data['name'.$lang])>0)
			$data['name']=$data['name'.$lang];

		$apps[$i]=$data;
		$i++;
	}
	
	return $apps;
}



?>