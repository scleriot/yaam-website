<?php

include_once("functions.php");

function categories_get($game,$lang,$bypass=0)
{
	global $bdd;

	$lang=language($lang);
	$game=intval($game);	
	

	$cats=array();
	$i=0;
	$answer = $bdd->query("SELECT name,id,appcount FROM categories$lang WHERE game=$game ORDER BY name") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if($data['appcount']>0 || $bypass==1)
		{
			$cats[$i]=$data;
			$i++;
		}
	}
	
	return $cats;
}

function categories_apps($catid,$lang,$sdk,$order,$page,$paid,$terminal,$sex=1)
{
	global $bdd;

	$lang=language($lang);
	$catid=intval($catid);
	$page=intval($page);
	
	if($catid==-1)
		$where="(SELECT game FROM categories WHERE id=applications.catid)=0 AND";
	else if($catid==-2)
		$where="(SELECT game FROM categories WHERE id=applications.catid)=1 AND";
	else
		$where="catid=$catid AND";
	
	$sdk=intval($sdk);
	if($sdk==0)
		$sdk=3;
	
	$paid=intval($paid);
	
	$sortPaid="";
	if($paid==0)
		$sortPaid="AND price=0";
	else if ($paid==1)
		$sortPaid="AND price>0";
	
	$sex=intval($sex);
	if($sex==1)
		$SEX="";
	else if($sex==0)
		$SEX="AND porn=0";
	
	$order=protect($order);
	$orderBy="lastUpdate DESC";
	if($order=='last')
		$orderBy="lastUpdate DESC";
	elseif($order=='top')
		$orderBy="dlCount DESC, rating DESC";
	
	$nbByPage=10;
	$n1=$page*$nbByPage;
	
	$terminal=protect($terminal);
	
	$answer = $bdd->query("SELECT name$lang as appname, name as appnamedefault,price, rating,id,icon FROM applications WHERE $where $terminal=1 AND enabled=1 AND minSdk<=$sdk $SEX $sortPaid ORDER BY $orderBy LIMIT $n1,$nbByPage") or die(mysql_error());
		
	$apps=array();
	$i=0;
	while($data = $answer->fetch())
	{
		if($data['appname']!="")
			$data['appname']=$data['appname'];
		else
			$data['appname']=$data['appnamedefault'];
			
		$apps[$i]=$data;
		$i++;
	}
	return $apps;
}


?>