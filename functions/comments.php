<?php

include_once("functions.php");

function comments_get($appid,$lang)
{
	global $bdd;

	$lang=language($lang);
	$appid=intval($appid);	
	

	$comments=array();
	$i=0;
	$answer = $bdd->query("SELECT comments.deleted, comments.id, userid,comments.date,phone, comment,users.username FROM comments LEFT JOIN users ON users.id=userid WHERE appid=$appid ORDER BY date desc") or die(mysql_error());
	while($data = $answer->fetch())
	{
		$comments[$i]=$data;
		$i++;
	}
	
	return $comments;
}

function comments_post($comment,$appid,$user,$lang,$phone,$sdk=0)
{
	global $bdd;
	
	$lang=language($lang);
	$comment=protect($comment);
	$phone=protect($phone);
	$appid=intval($appid);
	$time=time();
	
	$sdk=intval($sdk);
	
	if(strlen($comment)>0 && $appid>0)
	{
		if(is_numeric($user))
		{
			$userid=intval($user);
			$bdd->query("INSERT INTO comments(userid,appid,comment,phone,lang,date,sdk) VALUES($userid,$appid,'$comment','$phone','$lang',$time,$sdk)") or die(mysql_error());
		}
		else
		{
			$username=protect($user);
			$bdd->query("INSERT INTO comments(userid,appid,comment,phone,lang,date,sdk) VALUES((SELECT id FROM users WHERE UPPER(username)=UPPER('$username')),$appid,'$comment','$phone','$lang',$time,$sdk)") or die(mysql_error());
		}
	}
}



function comments_delete($cid,$userid)
{
	global $bdd;
	
	$cid=intval($cid);
	$userid=intval($userid);
	
	$answer = $bdd->query("SELECT userid FROM comments WHERE id=$cid") or die(mysql_error());
	$data = $answer->fetch();
	
	if($data['userid']==$userid)
	{
		$bdd->query("UPDATE comments SET deleted=1 WHERE id=$cid") or die(mysql_error());
		
		return true;
	}
	else
	{
		return false;
	}
}

?>