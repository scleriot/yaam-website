<?php

include_once("functions.php");

function apps_rating($rating)
{
	$rating=floatval($rating);
	
	if($rating==0)
		return "";
		
	$stars = "";
	for ($i = 0; $i < floor($rating); ++$i)
		$stars .= '<img src="/img/star.png" alt="" style="float:left" />';
	for ($i = 0; $i < (5 - floor($rating)); ++$i)
		$stars .= '<img src="/img/star_empty.png" alt="" style="float:left" />';
	return $stars;
}


function apps_dlcount ($appid,$start,$end)
{
	global $bdd;
	
	$appid=intval($appid);
	
	$answer = $bdd->query("SELECT COUNT(id) AS nb FROM `appsByUsers` WHERE `time`>=$start AND `time`<=$end AND `appid`=$id");
	$row = $answer->fetch();
	return $row['nb'];
}


/*function apps_idFromPackage ($package)
{
	global $bdd;
	
	$package=protect($package);
	
	$answer = $bdd->query("SELECT id FROM `applications` WHERE packagename='$package'");
	$row = $answer->fetch();
	return $row['id'];
}*/



function apps_uninstall ($username,$appid)
{
	global $bdd;
	
	$appid=intval($appid);
	$username=protect($username);
	
	$answer = $bdd->query("UPDATE appsByUsers SET uninstalled=1 WHERE appid=$appid AND userid=(SELECT id FROM users WHERE UPPER(username)=UPPER('$username'))");
}


function apps_add($name,$namefr,$cat,$icon,$price,$tags,$description,$descriptionfr,$apk,$devid,$userVersion)
{
	global $bdd;
	
	$name=protect($name);
	$namefr=protect($namefr);
	$tags=protect($tags);
	$cat=intval($cat);
	$price=floatval($price);
	$description=protect(strip_tags_attributes ($description, "<b><a><i><u><s><li><ul><strong><em>", "href,title"));
	$descriptionfr=protect(strip_tags_attributes ($descriptionfr, "<b><a><i><u><s><li><ul><strong><em>", "href,title"));
	$devid=intval($devid);
			
	$userVersion=protect($userVersion);
			
	if($price!=0 && $price<0.5)
		message(_("Because of paypal restriction, your application's price can't be lower than 0.5€"),"/dev/");
			
	if(strlen($name)==0)
		message(_("You must give a name to your application!"),"/dev/");
	
	
	if(isset($apk))
	{
		$APK_DIR = '../apk/';


		///GENERATE PASSWORD
		// start with a blank password
		$password = "";
		
		// define possible characters
		$possible = "0123456789bcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
		// set up a counter
		$i = 0; 
		
		$length = 20;
		
		// add random characters to $password until $length is reached
		while ($i < $length)
		{ 
			// pick a random character from the possible ones
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
			
			// we don't want this character if it's already in the password
			if (!strstr($password, $char))
			{ 
				$password .= $char;
				$i++;
			}
		}
		
		$dossier = $APK_DIR.$password.'/';
		
		mkdir($dossier);

		$taille_maxi = 104857600;
		$taille = filesize($apk['tmp_name']);
		$extensions = array('.apk');
		$extension = strrchr($apk['name'], '.'); 
		
		if (!in_array($extension, $extensions))
			$error = _("Please, upload an apk file!");
		if( $taille > $taille_maxi)
			$error = _("Your apk size is too big. Please contact us if you really need a big apk.");
			
		if (!isset($error))
		{
			if (move_uploaded_file ($apk['tmp_name'], $dossier . 'application.apk'))
			{
				chmod($dossier . 'application.apk',0666);
				
				$size=filesize($dossier . 'application.apk');
				
				if(isset($icon))
				{
					$dossier = '../screens/'.$password.'/';
					mkdir ($dossier);
					$taille_maxi = 100000;
					$taille = filesize ($icon['tmp_name']);
					$extensions = array ('.png','gif','.jpg','.jpeg');
					$extension = strrchr ($icon['name'], '.'); 
					
					//Début des vérifications de sécurité...
					if (!in_array ($extension, $extensions))
						$error = _("Please, check your icon extension!");
					if ($taille > $taille_maxi)
						$error = _("Your icon is too big!");
						
					if (!isset($error)) //S'il n'y a pas d'erreur, on upload
					{
						if (move_uploaded_file($icon['tmp_name'], $dossier . 'icon.png'))
						{
							chmod($dossier . 'icon.png', 0666);
							
							$infos=apps_infosfromapk($dossier.'application.apk');
							
							$version=protect($infos[0]);	
							$package=protect($infos[1]);
							$minSdk=intval($infos[2]);
							$permissions=protect($infos[3]);
							
							if(strlen($package)>0 && !apps_exists($package))
							{
								if(strlen($version)==0 && strlen($userVersion)==0)
								{
									message(_("Sorry, but we can't find your application version, so please, add it in previous upload form."),"/dev/app.php?id=$package");
								}
								else if(strlen($version)==0)
									$version=$userVersion;

								crypte_fichier($APK_DIR.$password.'/application.apk','yaamroxxandisthebest',$APK_DIR.$password.'/application_crypt.apk');
								//chmod($APK_DIR.$password . '/application_crypt.apk',0666);
								
								$time=time();
	
								$bdd->query("UPDATE categories SET appcount = appcount+1 WHERE id = $cat");
									
								if($cat==23)
									$sex=1;
								else
									$sex=0;
									
								$bdd->query("INSERT INTO `applications`(name,name_fr,icon,size,password,version,price,tags,catid,packagename,description,description_fr,developerid,lastUpdate,minSdk,permissions,adult) VALUES('$name','$namefr','http://yaam.mobi/screens/$password/icon.png',$size,'$password','$version',$price,'$tags',$cat,'$package','$description','$descriptionfr',$devid,$time,$minSdk,'$permissions',$sex)") or die(mysql_error());
								
								
								message(_("Application has been successfully added!"),"/dev/");
							}
							else
								message(_("This package already exists. Contact us if you've got a problem!"),"/dev/");
						}
					}
					else
						message($error,"/dev/");
				}
				else
					message(_("You must upload an icon!"),"/dev/");
			}
		}
		else
			message($error,"/dev/");
	}
	else
		message(_("You must upload an apk!"),"/dev/");
}



//////////UPDATE INFORMATIONS//////////////////////

function apps_update($appid,$name,$namefr,$cat,$icon,$price,$tags,$description,$descriptionfr)
{
	global $bdd;
	
	$name=protect($name);
	$namefr=protect($namefr);
	$tags=protect($tags);
	$price=floatval($price);
	$cat=intval($cat);
	$description=protect(strip_tags_attributes ($description, "<b><a><i><u><s><li><ul><strong><em>", "href,title"));
	$descriptionfr=protect(strip_tags_attributes ($descriptionfr, "<b><a><i><u><s><li><ul><strong><em>", "href,title"));
	$devid=intval($devid);
	$appid=intval($appid);
		
		
	if($price!=0 && $price<0.5)
		message(_("Because of paypal restriction, your application's price can't be lower than 0.5€"),"/dev/");
		
	if(strlen($name)==0)
		message(_("You must give a name to your application!"),"/dev/");
	
	
	if($cat==23)
		$sex=1;
	else
		$sex=0;
	
	
	$password=apps_password($appid,-1,1);
	$taille = filesize ($icon['tmp_name']);
	if(isset($icon) && $taille>0)
	{
		$dossier = '../screens/'.$password.'/';

		$taille_maxi = 100000;
		
		$extensions = array ('.png','gif','.jpg','.jpeg');
		$extension = strrchr ($icon['name'], '.'); 
		
		//Début des vérifications de sécurité...
		if (!in_array ($extension, $extensions))
			$error = _("Please, check your icon extension!");
		if ($taille > $taille_maxi)
			$error = _("Your icon is too big!");
			
		if (!isset($error)) //S'il n'y a pas d'erreur, on upload
		{
			if (move_uploaded_file($icon['tmp_name'], $dossier . 'icon.png'))
			{
				chmod($dossier . 'icon.png', 0666);					
			}
		}
		else
			message($error,"/dev/");
	}
	
	$bdd->query("UPDATE `applications` SET adult=$sex, name='$name', name_fr='$namefr' , price=$price ,tags='$tags',catid=$cat,description='$description',description_fr='$descriptionfr' WHERE id=$appid LIMIT 1") or die(mysql_error());
							
	message(_("Application's informations have been successfully updated!"),"/dev/");

}


function apps_addScreen($screen,$appid,$package)
{
	global $bdd;
	
	$appid=intval($appid);
	$package=protect($package);
	
	$password=apps_password($appid,-1,1);
	$taille = filesize ($screen['tmp_name']);
	if(isset($screen) && $taille>0)
	{
		$dossier = '../screens/'.$password.'/';

		$taille_maxi = 500000;
		
		$time=time();
		
		$extensions = array ('.png','gif','.jpg','.jpeg');
		$extension = strrchr ($screen['name'], '.'); 
		
		//Début des vérifications de sécurité...
		if (!in_array ($extension, $extensions))
			$error = _("Please, check your icon extension!");
		if ($taille > $taille_maxi)
			$error = _("Your icon is too big!");
			
		if (!isset($error)) //S'il n'y a pas d'erreur, on upload
		{
			if (move_uploaded_file($screen['tmp_name'], $dossier . 'screen'.$time.'.png'))
			{
				chmod($dossier . 'screen'.$time.'.png', 0666);					
			}
		}
		else
			message($error,"/dev/");
	}
	
	$bdd->query("UPDATE `applications` SET screens=CONCAT(screens,';http://yaam.mobi/screens/".$password."/screen".$time.".png') WHERE id=$appid LIMIT 1") or die(mysql_error());
							
	message(_("Screen has been successfully uploaded!"),"/dev/app.php?id=$package");

}

function apps_deleteScreen($screen,$appid,$package)
{
	global $bdd;
	
	$appid=intval($appid);
	$package=protect($package);
			
	$answer = $bdd->query("SELECT screens FROM `applications` WHERE id=$appid");
	$row = $answer->fetch();
	$screens=$row['screens'];
	
	$screens=explode(";",$screens);
	
	$i=0;
	$inside=0;
	foreach($screens as $screen1) { 
		if($screen1 == "" || $screen1==$screen) { 
			unset($screens[$i]); 
			if($screen1==$screen)
				$inside=1;
		} 
		$i++;
	} 
	$screens = array_values($screens); 

	$str_screens=implode(";",$screens);
	
	$bdd->query("UPDATE `applications` SET screens='$str_screens' WHERE id=$appid LIMIT 1") or die(mysql_error());
							
	if($inside==1)
	{
		$screen=split("yaam.mobi/screens/",$screen);
		$s=$screen[1];
		unlink("../screens/".$s);
	}
							
	message(_("Screen has been successfully removed!"),"/dev/app.php?id=$package");
}




/////////UPDATE APPLICATION APK/////////////
function apps_updateApk($apk,$appid,$oldpackage,$userVersion)
{
	global $bdd;
	
	$appid=intval($appid);
	$userVersion=protect($userVersion);
		
	if(isset($apk))
	{
		$APK_DIR = '../apk/';


		$password=apps_password($appid,-1,1);
		
				
		$dossier = $APK_DIR.$password.'/';
		
		$taille_maxi = 104857600;
		$taille = filesize($apk['tmp_name']);
		$extensions = array('.apk');
		$extension = strrchr($apk['name'], '.'); 
		
		if (!in_array($extension, $extensions))
			$error = _("Please, upload an apk file!");
		if( $taille > $taille_maxi)
			$error = _("Your apk size is too big. Please contact us if you really need a big apk.");
			
		if (!isset($error))
		{
			$infos=apps_infosfromapk($apk['tmp_name']);
							
			$version=protect($infos[0]);	
			$package=protect($infos[1]);
			$minSdk=intval($infos[2]);
			$permissions=protect($infos[3]);
						
			if(strlen($package)>0 && ($package==$oldpackage || ($package!=$oldpackage && !apps_exists($package))))
			{
				if(strlen($version)==0 && strlen($userVersion)==0)
				{
					message(_("Sorry, but we can't find your application version, so please, add it in previous upload form."),"/dev/app.php?id=$package");
				}
				else if(strlen($version)==0)
					$version=$userVersion;
					
				if(strlen($minSdk)==0)
				{
					message(_("Error : Your Android Manifest doesn't have minimun SDK."),"/dev/app.php?id=$package");

				}
									
				if (move_uploaded_file ($apk['tmp_name'], $dossier . 'application.apk'))
				{
					//chmod($dossier . 'application.apk',0666);
					$size=filesize($dossier . 'application.apk');
					
						crypte_fichier($APK_DIR.$password.'/application.apk','yaamroxxandisthebest',$APK_DIR.$password.'/application_crypt.apk');
						$time=time();
								
						$bdd->query("UPDATE applications SET size=$size, lastUpdate=$time, version='$version', packagename='$package', minSdk=$minSdk, permissions='$permissions' WHERE id=$appid") or die(mysql_error());
						
						$bdd->query("UPDATE appsByUsers SET `update`=1 WHERE appid=$appid") or die(mysql_error());
						
						
						message(_("Application has been successfully updated!"),"/dev/app.php?id=$package");
				}
			}
			else
				message(_("This package already exists. Contact us if you've got a problem!"),"/dev/app.php?id=$oldpackage");	
		}
		else
			message($error,"/dev/app.php?id=$oldpackage");
	}
	else
		message(_("You must upload an apk!"),"/dev/app.php?id=$oldpackage");
}

















function apps_infosfromapk($file)
{
	global $AAPT_DIR;

	$infos=array();
	$retour = array();
	exec ($AAPT_DIR." l -a ".realpath($file),$retour);

	$txt = "";
	for($i = 0; $i < sizeof ($retour); $i++)
		$txt .= $retour[$i];
	
	$t1 = explode('android:versionName(0x0101021c)="',$txt);
	$t3 = explode('"',$t1[1]);
	
	$version = $t3[0];
	
	$t2 = explode ('package="', $txt);
	$t4 = explode ('"', $t2[1]);
	
	$package = $t4[0];
	
	$t5 = explode('A: android:minSdkVersion(0x0101020c)=(type 0x10)0x',$txt);
	$t6 = explode(' ',$t5[1]);
	
	$minSdk = intval($t6[0]);
	
	
	$txt2=explode('android:name(0x01010003)="android.permission.',$txt);

	$it=0;
	$permissions="";
	for($i=1;$i<sizeof($txt2);$i++)
	{
		$tmp=explode('"',$txt2[$i]);
		if($it==0)
			$permissions.=$tmp[0];
		else
			$permissions.=";".$tmp[0];

		$it++;
	}



	$infos[0]=$version;	
	$infos[1]=$package;
	$infos[2]=$minSdk;
	$infos[3]=$permissions;
	
	return $infos;
}


function apps_exists($package)
{
	global $bdd;
	
	$package=protect($package);	
	

	
	$answer = $bdd->query("SELECT COUNT(*) AS nb FROM applications WHERE packagename='$package' AND enabled=1") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if($data['nb']==0)
			return false;
		else
			return true;
	}
}


function apps_recommended($sdk,$lang,$terminal)
{
	global $bdd;

	$sdk=intval($sdk);
	if($sdk==0)
		$sdk==3;
	
	$lang=language($lang);
	
		
	$terminal=protect($terminal);

	$apps=array();
	$i=0;
	$answer = $bdd->query("SELECT name$lang,name,price,rating,id,icon FROM applications WHERE $terminal=1 AND enabled=1 AND recommended=1 AND minSdk <= $sdk ORDER BY rand() LIMIT 10") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if(strlen($data['name'.$lang])>0)
			$data['name']=$data['name'.$lang];
			
		$apps[$i]=$data;
		$i++;
	}
	
	return $apps;
}





function apps_latest($lang)
{
	global $bdd;
	global $_username;
	
	$lang=language($lang);
	
	$adult=intval(isAdult($_username));
	if($adult==1)
		$ADULT="";
	else if($adult==0)
		$ADULT="AND adult=0";
		
	$apps=array();
	$i=0;
	$answer = $bdd->query("SELECT name$lang,name,price,packagename, rating,icon FROM applications WHERE enabled=1 $ADULT ORDER BY lastUpdate DESC LIMIT 6") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if(strlen($data['name'.$lang])>0)
			$data['name']=$data['name'.$lang];
			
		$apps[$i]=$data;
		$i++;
	}
	
	return $apps;
}
function apps_popular($lang)
{
	global $bdd;
	global $_username;

	$lang=protect($lang);
	if($lang=='fr')
		$lang='_fr';
	else
		$lang='';
		
	$adult=intval(isAdult($_username));
	if($adult==1)
		$ADULT="";
	else if($adult==0)
		$ADULT="AND adult=0";

		
	$apps=array();
	$i=0;
	$answer = $bdd->query("SELECT name$lang,name,price,packagename, rating,icon FROM applications WHERE enabled=1 $ADULT ORDER BY dlCount DESC,rating DESC LIMIT 6") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if(strlen($data['name'.$lang])>0)
			$data['name']=$data['name'.$lang];
			
		$apps[$i]=$data;
		$i++;
	}
	
	return $apps;
}






function apps_isbought($username,$appid)
{
	global $bdd;

	$appid=intval($appid);

	$username=strtoupper(trim(protect($username)));

	$answer = $bdd->query("SELECT COUNT(*) AS nb FROM appsByUsers WHERE userid=(SELECT id FROM users WHERE UPPER(username)='$username') AND appid=$appid GROUP BY id LIMIT 1") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if($data['nb']==0 || $appid==-1)
			return false;
		else
			return true;
	}

}

function apps_idfrompackage($package)
{
	global $bdd;

	$package=protect($package);
	
	$answer = $bdd->query("SELECT id FROM applications WHERE packagename='".$package."'") or die(mysql_error());
	$data=$answer->fetch();
	return $data['id'];
}


function apps_password($appid,$username,$bypass=0)
{
	global $bdd;
	
	$appid=intval($appid);
	
	$answer = $bdd->query("SELECT COUNT(*) AS nb, price, password FROM applications WHERE id=$appid GROUP BY id LIMIT 1") or die(mysql_error());
	$data = $answer->fetch();

	if($data['nb']>0)
	{
		if($bypass==0)
		{
			if($data['price']>0)
			{
				if(apps_isbought($username,$appid))
					return $data['password'];
				else
					return "";
			}
			else
				return $data['password'];
		}
		else
			return $data['password'];
	}
	else
		return "";
}



function apps_infos($app,$lang,$username="")
{
	global $bdd;
	
	$username=protect($username);
	
	$lang=protect($lang);
	
	if($lang=='fra')
		$lang='_fr';
	else
		$lang='';
	
	if(is_numeric($app))
	{
		$appid=intval($app);
		$answer = $bdd->query("SELECT dev.paypal, dev.name AS devname, dev.paypal, app.catid,app.id, app.minSdk, app.permissions,app.name_fr as name_fr, app.name as name, app.name$lang as appname, app.name as appnamedefault, app.price as price, app.screens as screens, dlCount, size, youtube, app.rating as rating,app.id as appid,icon, app.packagename as packagename, description as descriptionOriginal, description_fr as description_fr, description$lang as description,description as descriptiondefault, app.password as password, app.version as version, app.widget as widget, appby2.update as isUpdate
		FROM applications AS app 
		LEFT JOIN developers AS dev ON dev.id=app.developerid
		LEFT JOIN appsByUsers AS appby ON appby.appid=$appid
		LEFT JOIN appsByUsers AS appby2 ON appby2.userid=(SELECT id FROM users WHERE UPPER(username)=UPPER('$username')) AND appby2.appid=$appid
		WHERE app.enabled=1 AND app.id=$appid LIMIT 1") or die(mysql_error());
	}
	else
	{
		$package=protect($app);
		$answer = $bdd->query("SELECT dev.paypal, dev.name AS devname, dev.paypal, app.catid, app.id, app.minSdk, app.permissions,app.name_fr as name_fr, app.name as name, app.name$lang as appname, app.name as appnamedefault, app.price as price, app.screens as screens, dlCount, size, youtube, app.rating as rating,app.id as appid,icon, app.packagename as packagename, description as descriptionOriginal, description_fr as description_fr, description$lang as description,description as descriptiondefault, app.password as password, app.version as version, app.widget as widget, appby2.update as isUpdate
		FROM applications AS app 
		LEFT JOIN developers AS dev ON dev.id=app.developerid
		LEFT JOIN appsByUsers AS appby ON appby.appid=app.id
		LEFT JOIN appsByUsers AS appby2 ON appby2.userid=(SELECT id FROM users WHERE UPPER(username)=UPPER('$username'))
		WHERE app.enabled=1 AND app.packagename='$package' LIMIT 1") or die(mysql_error());
	}


	$data=$answer->fetch();
	
	if($data['description'.$lang]!="")
		$data['description']=$data['description'.$lang];
	else
		$data['description']=$data['descriptiondefault'];
		
	if($data['appname']!="")
		$data['appname']=$data['appname'];
	else
		$data['appname']=$data['appnamedefault'];
	
	$description=nl2br($data['description']);	
	$permissions=explode(";",$data['permissions']);
	
	$description.="<br /><br /><b>Permissions :</b><br />";
	if(count($permissions)>0 && $permissions[0]!="")
	{
		$description.="<ul>";
		
		for($i=0;$i<sizeof($permissions);$i++)
		{
			$description.="<li>".$permissions[$i]."</li>";
		}
		$description.="</ul>";
	}
	else
		$description.=_("<i>This application uses no permissions !</i><br />");


	//$description=str_replace("&","&amp;",$description);
	$description=str_replace("\"","&quot;",stripslashes($description));
	$description=str_replace("<","&lt;",$description);
	$description=str_replace(">","&gt;",$description);

	
	$data['description']=$description;

	return $data;
}

function apps_decode($description)
{
	$description=str_replace("&lt;","<",$description);
	$description=str_replace("&gt;",">",$description);
	$description=str_replace("&quot;","\"",$description);
	return $description;
}




function apps_buy($appid,$username,$price)
{
	global $bdd;
	
	$appid=intval($appid);
	$username = strtoupper (protect ($username));
	$price=intval($price);
	$time=time();
	
	$answer = $bdd->query("SELECT developerid FROM applications WHERE applications.id=$appid LIMIT 1") or die(mysql_error());
	while($data = $answer->fetch())
	{
		$devid=$data['developerid'];
	}
	
	$price=round($price,2);
	$bdd->query("UPDATE developers SET moneyEarnt=(moneyEarnt+$price) WHERE id=$devid LIMIT 1") or die(mysql_error());
	
	$bdd->query("INSERT INTO appsByUsers(userid,appid,rating,time) VALUES((SELECT id FROM users WHERE UPPER(username)='$username'), $appid,0,".$time.")") or die(mysql_error());
}



function apps_rate($username,$appid,$rating)
{
	global $bdd;
	
	$username=protect($username);
	$appid=intval($appid);
	$rating=floatval($rating);
	
	$bdd->query("UPDATE appsByUsers SET rating=$rating WHERE appid=$appid AND userid=(SELECT id FROM users WHERE UPPER(username)=UPPER('$username')) LIMIT 1") or die(mysql_error());
}


function apps_addDL($appid,$username,$phone,$lang,$country,$sdk)
{
	global $bdd;
	
	$appid=intval($appid);
	$name=protect(strtoupper($username));
	
	
	$phone=protect($phone);
	$time=time();
	$update=1;
	$lang=protect($lang);
	$country=protect($_GET['country']);
	$sdk=intval($sdk);
	
	
	$answer = $bdd->query("SELECT COUNT(*) AS nb FROM appsByUsers WHERE userid=(SELECT id FROM users WHERE UPPER(username)='$name') AND appid=$appid") or die(mysql_error());
	while($data = $answer->fetch())
	{
		if($data['nb']==0)
		{
			$bdd->query("UPDATE applications SET dlCount=dlCount+1 WHERE id=$appid LIMIT 1") or die(mysql_error());
			$update=0;
			$bdd->query("INSERT INTO appsByUsers(userid,appid,rating,time) VALUES((SELECT id FROM users WHERE UPPER(username)='$name'), $appid,0,".$time.")") or die(mysql_error());
		}
		else
		{
			$update=1;
			$bdd->query("UPDATE appsByUsers SET `update`=0, time=$time, `uninstalled`=0 WHERE appid=$appid AND userid=(SELECT id FROM users WHERE UPPER(username)='$name') LIMIT 1") or die(mysql_error());
		}
	}
	
	$answer = $bdd->query("SELECT price FROM applications WHERE id=$appid") or die(mysql_error());
	$data = $answer->fetch();
	
	$bdd->query("INSERT INTO stats(userid,appid,phone,time,lang,country,`update`,price,sdk) VALUES((SELECT id FROM users WHERE UPPER(username)='$name' LIMIT 1), $appid, '$phone',$time,'$lang','$country',$update,".$data['price'].",$sdk)") or die(mysql_error());
}



function apps_search($query,$lang,$sdk,$order,$page,$paid,$terminal,$adult=1)
{
	global $bdd;
	global $_username;

	$lang=language($lang);
	$catid=intval($catid);
	$page=intval($page);
	$sdk=intval($sdk);
	if($sdk==0)
		$sdk=50;
	
	$paid=intval($paid);
	
	$query=protect($query);
	
	$sortPaid="";
	if($paid==0)
		$sortPaid="AND price=0";
	else if ($paid==1)
		$sortPaid="AND price>0";
	else if($paid==-1)
		$sortPaid="";
	
	$adult=intval(isAdult($_username));
	if($adult==1)
		$ADULT="";
	else if($adult==0)
		$ADULT="AND adult=0";
	
	$order=protect($order);
	$orderBy="lastUpdate DESC";
	if($order=='last')
		$orderBy="lastUpdate DESC";
	elseif($order=='top')
		$orderBy="dlCount DESC, rating DESC";
	
	$nbByPage=10;
	$n1=$page*$nbByPage;
	
	$terminal=protect($terminal);
	
	if(strlen($terminal)>0)
		$answer = $bdd->query("SELECT name$lang as appname, description$lang, description, name as appnamedefault,price, packagename, rating,id,icon FROM applications WHERE enabled=1 AND $terminal=1 AND minSdk<=$sdk $ADULT $sortPaid AND (UPPER(name) LIKE UPPER('%$query%') OR UPPER(name$lang) LIKE UPPER('%$query%') OR UPPER(description) LIKE UPPER('%$query%') OR UPPER(tags) LIKE UPPER('%$query%')) ORDER BY $orderBy LIMIT $n1,$nbByPage") or die(mysql_error());
	else
		$answer = $bdd->query("SELECT name$lang as appname, name as appnamedefault,price, description$lang, description, packagename, rating,id,icon FROM applications WHERE enabled=1 AND minSdk<=$sdk $ADULT $sortPaid AND (UPPER(name) LIKE UPPER('%$query%') OR UPPER(name$lang) LIKE UPPER('%$query%') OR UPPER(description) LIKE UPPER('%$query%') OR UPPER(tags) LIKE UPPER('%$query%')) ORDER BY $orderBy LIMIT $n1,$nbByPage") or die(mysql_error());
		
	$apps=array();
	$i=0;
	while($data = $answer->fetch())
	{
		if(strlen($data['appname'])>0)
			$data['appname']=$data['appname'];
		else
			$data['appname']=$data['appnamedefault'];
			
		if(strlen($data['description'.$lang])>0)
			$data['description']=$data['description'.$lang];
			
		$apps[$i]=$data;
		$i++;
	}
	return $apps;
}



function apps_list($username,$lang,$sdk,$order,$page,$paid,$terminal,$adult=1)
{
	global $bdd;
	global $_username;

	$lang=language($lang);
	$catid=intval($catid);
	$page=intval($page);
	$sdk=intval($sdk);
	if($sdk==0)
		$sdk=3;
		
	$username=protect($username);
		
	$adult=intval(isAdult($_username));
	if($adult==1)
		$ADULT="";
	else if($adult==0)
		$ADULT="AND adult=0";
	
	$order=protect($order);
	$orderBy="lastUpdate DESC";
	if($order=='last')
		$orderBy="lastUpdate DESC";
	elseif($order=='top')
		$orderBy="dlCount DESC, rating DESC";
	
	$nbByPage=10;
	$n1=$page*$nbByPage;
	
	$terminal=protect($terminal);
	
	$answer = $bdd->query("SELECT version, packagename, name$lang as appname, name as appnamedefault,price, applications.rating,applications.id as id,icon  FROM appsByUsers LEFT JOIN applications ON applications.id=appsByUsers.appid WHERE enabled=1 AND $terminal=1 AND minSdk<=$sdk $ADULT AND appsByUsers.userid=(SELECT id FROM users WHERE UPPER(username)=UPPER('$username')) AND appsByUsers.uninstalled=0 ORDER BY $orderBy") or die(mysql_error());
		
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






function apps_updates($username,$lang,$sdk,$order,$page,$paid,$terminal,$adult=1)
{
	global $bdd;
	global $_username;

	$lang=language($lang);
	$catid=intval($catid);
	$page=intval($page);
	$sdk=intval($sdk);
	if($sdk==0)
		$sdk=3;
				
	$username=protect($username);
		
	$adult=intval(isAdult($_username));
	if($adult==1)
		$ADULT="";
	else if($adult==0)
		$ADULT="AND adult=0";
	
	$order=protect($order);
	$orderBy="lastUpdate DESC";
	if($order=='last')
		$orderBy="lastUpdate DESC";
	elseif($order=='top')
		$orderBy="dlCount DESC, rating DESC";
	
	$nbByPage=10;
	$n1=$page*$nbByPage;
	
	$terminal=protect($terminal);
	
	$answer = $bdd->query("SELECT version, packagename, name$lang as appname, name as appnamedefault,price, applications.rating,applications.id as id,icon  FROM appsByUsers LEFT JOIN applications ON applications.id=appsByUsers.appid WHERE enabled=1 AND $terminal=1 AND minSdk<=$sdk $ADULT AND appsByUsers.userid=(SELECT id FROM users WHERE UPPER(username)=UPPER('$username')) AND appsByUsers.uninstalled=0 AND appsByUsers.update=1 ORDER BY $orderBy") or die(mysql_error());
		
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








function apps_getDlCount ($package,$start,$end)
{
	global $bdd;
	
	$package=protect($package);
	
	if($month==0)
		$answer = $bdd->query("SELECT COUNT(id) AS nb FROM `appsByUsers` WHERE `time`>=$start AND `time`<=$end AND `appid`=(SELECT id FROM applications WHERE packagename='$package' LIMIT 1)");
	$data = $answer->fetch();
	return $data['nb'];
}

























function apps_updateInfos()
{
	global $bdd;
	
	$answer = $bdd->query("SELECT id FROM applications") or die(mysql_error());
	while($data = $answer->fetch())
	{
		$appid=intval($data['id']);
		
		$ratings=0;
		$nb=0;
		$nbDL=0;
		$nbInstall=0;
		
		$answer2 = $bdd->query("SELECT rating,uninstalled FROM appsByUsers WHERE appid=$appid") or die(mysql_error());
		while($data2 = $answer2->fetch())
		{
			if($data2['rating']!=-1 && $data2['rating']!=0)
			{
				$ratings+=$data2['rating'];
				$nb++;
			}
			
			if($data['uninstalled']==0)
				$nbInstall++;
				
			$nbDL++;
		}
		
		if($nb==0) ///AVOID NULL VALUE BY DIVIDING BY 0
		{
			$ratings=0;
			$nb=1;
		}
		
		$bdd->query("UPDATE applications SET activeInstall=$nbInstall, dlCount=$nbDL ,rating=($ratings/$nb) WHERE id=$appid");
		
		$bdd->query("INSERT INTO evolutions(appid, rating, dlCount, activeInstall) VALUES($appid, ($ratings/$nb), $nbDL, $nbInstall)");
	}
}


?>