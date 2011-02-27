<?

require "include/bittorrent.php";

dbconn(false);

loggedinorreturn();

function puke($text = "w00t")
{
  stderr("w00t", $text);
}

if (get_user_class() < UC_MODERATOR)
  puke();

$action = $_POST["action"];

if($action == "hlutf-ovirkja") {
	if($_POST['vikufr'] != '0') {
		$comment = gmdate("Y-m-d").' - Óvirktur af '.$CURUSER['username'].".\n".'Vikufrestur liðinn ('.$_POST['hlutfall'].')'."\n".addslashes($_POST['comment']);
		mysql_query('UPDATE users SET modcomment = \''.$comment.'\', vikufr = \''.$vikufr.'\', enabled = \'no\' WHERE id = \''.$_POST['id'].'\' LIMIT 1') or sqlerr(__FILE__, __LINE__);
		$skilabod = 'Kæri notandi,

		Notandanafnið þitt á Istorrent (http://torrent.is), '.$_POST['username'].', hefur verið óvirkt til frambúðar vegna vangetu þinnar við að halda nógu góðum hlutföllum. Þú hefur áður fengið vikufrest til að bæta úr þínum málum og verður þú að viðhalda góðu hlutfalli það sem eftir er veru þinnar. Þessi vikufrestur er ekki endurnýjanlegur eða framlengjanlegur.

		Notendur sem fara framhjá þessari óvirkingu og búa til nýjan aðgang verða bannaðir.

		Með kveðju,
		Istorrent (torrent@torrent.is)
		';
	} else {
		$comment = gmdate("Y-m-d").' - Óvirktur af '.$CURUSER['username'].".\n".'Léleg hlutföll ('.$_POST['hlutfall'].')'."\n".addslashes($_POST['comment']);
		mysql_query('UPDATE users SET modcomment = \''.$comment.'\', enabled = \'no\' WHERE id = \''.$_POST['id'].'\' LIMIT 1') or sqlerr(__FILE__, __LINE__);
		$skilabod = 'Kæri notandi,

		Notandanafnið þitt á Istorrent (http://torrent.is), '.$_POST['username'].', hefur verið gert óvirkt vegna lélegra hlutfalla.

		Eins og stendur í reglunum eru notendur sem hafa verið á vefnum í 2 vikur og sótt 2 gígabæti af gögnum gerðir óvirkir ef þeir fara undir 0.2 í hlutföllum og hefur notandinn þinn verið gerður óvirkur af þeirri ástæðu. Þessar tvær vikur í aðlögunartíma eiga að vera nægar til þess að aðlaga sig að þessu skipulagi en þér hefur greinilega ekki tekist það. Hins vegar er möguleiki að þú hafir ekki kynnt þér þessa reglu og því eiga allir notendur kost á vikufresti til að bæta sig í þeim efnum. Þessi frestur er ekki endurnýjanlegur eða framlengjanlegur og skal því nota þessa viku vel til að bæta sig. Ágætt er að vera búinn að leysa vandamálið sem orsakaði þetta lága hlutfall áður en beðið er um frestinn og einnig er hægt að skoða slóðina '.$BASEURL.'/vandamal.php til að fá leiðbeiningar um þau skref sem hægt er að taka til þess að leysa það.

		Hægt er að sækja um frestinn með því að fara á slóðina '.$BASEURL.'/vikufrestur.php og slá þar inn notandanafnið og lykilorðið þitt.

		Notendur sem fara framhjá þessari óvirkingu og búa til nýjan aðgang verða bannaðir.

		Með kveðju,
		Istorrent (torrent@torrent.is)
		';
	}

	mail($_POST['email'], 'Istorrent - óvirking aðgangs', $skilabod, 'From: Istorrent <torrent@torrent.is>');	
	$returnto = 'userdetails.php?id='.$_POST['id'];
	header("Location: $BASEURL/$returnto");
	die();
}

if ($action == "confirmuser")
{
 $userid = $_POST["userid"];
 $confirm = $_POST["confirm"];
  mysql_query('UPDATE `users` SET `status` = \''.$confirm.'\', `info` = NULL WHERE `id` = '.$userid.' LIMIT 1;') or sqlerr(__FILE__, __LINE__);
  header("Location: $BASEURL/unco.php");
  die;
}

if ($action == "edituser")
{
  $userid = $_POST["userid"];
  $title = $_POST["title"];
  $avatar = $_POST["avatar"];
  $avadult = $_POST["avadult"];
  $enabled = $_POST["enabled"];
  $warned = $_POST["warned"];
  $warnlength = 0 + $_POST["warnlength"];
  $donor = $_POST["donor"];
  $support = $_POST["support"];
  $supportfor = $_POST["supportfor"];
  $modcomment = $_POST["modcomment"];
  $class = 0 + $_POST["class"];
  if (!is_valid_id($userid) || !is_valid_user_class($class))
    stderr("Villa", "Notandi með þetta ID fannst ekki.");
  // check target user class
  $res = mysql_query("SELECT warned, enabled, username, class FROM users WHERE id=$userid") or sqlerr(__FILE__, __LINE__);
  $arr = mysql_fetch_assoc($res) or puke();
  $curenabled = $arr["enabled"];
  $curclass = $arr["class"];
  $curwarned = $arr["warned"];
  // User may not edit someone with same or higher class than himself!
  if ($curclass >= get_user_class())
    puke();

  if ($curclass != $class)
  {
    // Notify user
    $what = ($class > $curclass ? "uppfærður" : "niðurfærður");
    $msg = "Þú hefur verið $what í \'". get_user_class_name($class) ."\' af $CURUSER[username].";
    $added = sqlesc(get_date_time());
    mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, '$msg', $added)") or sqlerr(__FILE__, __LINE__);
    $updateset[] = "class = $class";
    $what = ($class > $curclass ? "Uppfærður" : "Niðurfærður");
 		$modcomment = gmdate("Y-m-d") . " - $what í '" . get_user_class_name($class) . "' af $CURUSER[username].\n". $modcomment;
  }

  if ($curwarned != $warned)
  {
		$updateset[] = "warned = " . sqlesc($warned);
    if ($warned == 'no')
    {
   		$updateset[] = "warneduntil = '0000-00-00 00:00:00'";
			$modcomment = gmdate("Y-m-d") . " - Aðvörun fjarlægð af " . $CURUSER['username']. ".\n". $modcomment;
      $msg = sqlesc("Aðvörun þín hefur verið fjarlægð af " . $CURUSER['username'] . ".");
      $added = sqlesc(get_date_time());
      mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
    }
   elseif ($warnlength)
  {
    $updateset[] = "warned = 'yes'";
    $warneduntil = get_date_time(gmtime() + $warnlength * 3600);

    if ($warnlength == 12)
    	$dur = '12 stundir';
    elseif ($warnlength == 24)
    	$dur = '1 dag';
    elseif ($warnlength == 72)
    	$dur = '3 daga';
    elseif ($warnlength == 168)
    	$dur = '1 viku';
		elseif ($warnlength == 672)
			$dur = '4 vikur';

		$msg = sqlesc("Þú hefur fengið aðvörun sem gildir í $dur sett af " . $CURUSER['username'] . ".");

		$modcomment = gmdate("Y-m-d") . " - Aðvörun í $dur sett af " . $CURUSER['username'] .  ".\n" . $modcomment;
		$added = sqlesc(get_date_time());
		mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
		$updateset[] = "warneduntil = '$warneduntil'";
	}
   
    else
    {
   		$updateset[] = "warneduntil = '0000-00-00 00:00:00'";
			$modcomment = gmdate("Y-m-d") . " - Aðvörun sett af " . $CURUSER['username'] . ".\n" . $modcomment;
      $msg = sqlesc("Þú hefur fengið aðvörun frá $CURUSER[username].");
      $added = sqlesc(get_date_time());
      mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
    }
  }

  if ($enabled != $curenabled)
  {
  	if ($enabled == 'yes')
  		$modcomment = gmdate("Y-m-d") . " - Virktur af " . $CURUSER['username']. ".\n". $modcomment;
  	else
  		$modcomment = gmdate("Y-m-d") . " - Óvirktur af " . $CURUSER['username']. ".\n". $modcomment;
  }

  $updateset[] = "enabled = " . sqlesc($enabled);
  $updateset[] = "donor = " . sqlesc($donor);
  $updateset[] = "supportfor = " . sqlesc($supportfor);
  $updateset[] = "support = " . sqlesc($support);
  $updateset[] = "avatar = " . sqlesc($avatar);
  $updateset[] = "avadult = " . sqlesc($avadult);
  $updateset[] = "title = " . sqlesc($title);
  $updateset[] = "modcomment = " . sqlesc($modcomment);
  if ($_POST['resetpasskey']) $updateset[] = "passkey=''"; 
  if ($_POST['24rule'])
	$updateset[] = '24rule=\'1\'';
  else
	$updateset[] = '24rule=\'0\'';
  mysql_query("UPDATE users SET  " . implode(", ", $updateset) . " WHERE id=$userid") or sqlerr(__FILE__, __LINE__);
  $returnto = $_POST["returnto"];

  header("Location: $BASEURL/$returnto");
  die;
}

puke();

?>
