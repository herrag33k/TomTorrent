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
		$comment = gmdate("Y-m-d").' - �virktur af '.$CURUSER['username'].".\n".'Vikufrestur li�inn ('.$_POST['hlutfall'].')'."\n".addslashes($_POST['comment']);
		mysql_query('UPDATE users SET modcomment = \''.$comment.'\', vikufr = \''.$vikufr.'\', enabled = \'no\' WHERE id = \''.$_POST['id'].'\' LIMIT 1') or sqlerr(__FILE__, __LINE__);
		$skilabod = 'K�ri notandi,

		Notandanafni� �itt � Istorrent (http://torrent.is), '.$_POST['username'].', hefur veri� �virkt til framb��ar vegna vangetu �innar vi� a� halda n�gu g��um hlutf�llum. �� hefur ��ur fengi� vikufrest til a� b�ta �r ��num m�lum og ver�ur �� a� vi�halda g��u hlutfalli �a� sem eftir er veru �innar. �essi vikufrestur er ekki endurn�janlegur e�a framlengjanlegur.

		Notendur sem fara framhj� �essari �virkingu og b�a til n�jan a�gang ver�a banna�ir.

		Me� kve�ju,
		Istorrent (torrent@torrent.is)
		';
	} else {
		$comment = gmdate("Y-m-d").' - �virktur af '.$CURUSER['username'].".\n".'L�leg hlutf�ll ('.$_POST['hlutfall'].')'."\n".addslashes($_POST['comment']);
		mysql_query('UPDATE users SET modcomment = \''.$comment.'\', enabled = \'no\' WHERE id = \''.$_POST['id'].'\' LIMIT 1') or sqlerr(__FILE__, __LINE__);
		$skilabod = 'K�ri notandi,

		Notandanafni� �itt � Istorrent (http://torrent.is), '.$_POST['username'].', hefur veri� gert �virkt vegna l�legra hlutfalla.

		Eins og stendur � reglunum eru notendur sem hafa veri� � vefnum � 2 vikur og s�tt 2 g�gab�ti af g�gnum ger�ir �virkir ef �eir fara undir 0.2 � hlutf�llum og hefur notandinn �inn veri� ger�ur �virkur af �eirri �st��u. �essar tv�r vikur � a�l�gunart�ma eiga a� vera n�gar til �ess a� a�laga sig a� �essu skipulagi en ��r hefur greinilega ekki tekist �a�. Hins vegar er m�guleiki a� �� hafir ekki kynnt ��r �essa reglu og �v� eiga allir notendur kost � vikufresti til a� b�ta sig � �eim efnum. �essi frestur er ekki endurn�janlegur e�a framlengjanlegur og skal �v� nota �essa viku vel til a� b�ta sig. �g�tt er a� vera b�inn a� leysa vandam�li� sem orsaka�i �etta l�ga hlutfall ��ur en be�i� er um frestinn og einnig er h�gt a� sko�a sl��ina '.$BASEURL.'/vandamal.php til a� f� lei�beiningar um �au skref sem h�gt er a� taka til �ess a� leysa �a�.

		H�gt er a� s�kja um frestinn me� �v� a� fara � sl��ina '.$BASEURL.'/vikufrestur.php og sl� �ar inn notandanafni� og lykilor�i� �itt.

		Notendur sem fara framhj� �essari �virkingu og b�a til n�jan a�gang ver�a banna�ir.

		Me� kve�ju,
		Istorrent (torrent@torrent.is)
		';
	}

	mail($_POST['email'], 'Istorrent - �virking a�gangs', $skilabod, 'From: Istorrent <torrent@torrent.is>');	
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
    stderr("Villa", "Notandi me� �etta ID fannst ekki.");
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
    $what = ($class > $curclass ? "uppf�r�ur" : "ni�urf�r�ur");
    $msg = "�� hefur veri� $what � \'". get_user_class_name($class) ."\' af $CURUSER[username].";
    $added = sqlesc(get_date_time());
    mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, '$msg', $added)") or sqlerr(__FILE__, __LINE__);
    $updateset[] = "class = $class";
    $what = ($class > $curclass ? "Uppf�r�ur" : "Ni�urf�r�ur");
 		$modcomment = gmdate("Y-m-d") . " - $what � '" . get_user_class_name($class) . "' af $CURUSER[username].\n". $modcomment;
  }

  if ($curwarned != $warned)
  {
		$updateset[] = "warned = " . sqlesc($warned);
    if ($warned == 'no')
    {
   		$updateset[] = "warneduntil = '0000-00-00 00:00:00'";
			$modcomment = gmdate("Y-m-d") . " - A�v�run fjarl�g� af " . $CURUSER['username']. ".\n". $modcomment;
      $msg = sqlesc("A�v�run ��n hefur veri� fjarl�g� af " . $CURUSER['username'] . ".");
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

		$msg = sqlesc("�� hefur fengi� a�v�run sem gildir � $dur sett af " . $CURUSER['username'] . ".");

		$modcomment = gmdate("Y-m-d") . " - A�v�run � $dur sett af " . $CURUSER['username'] .  ".\n" . $modcomment;
		$added = sqlesc(get_date_time());
		mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
		$updateset[] = "warneduntil = '$warneduntil'";
	}
   
    else
    {
   		$updateset[] = "warneduntil = '0000-00-00 00:00:00'";
			$modcomment = gmdate("Y-m-d") . " - A�v�run sett af " . $CURUSER['username'] . ".\n" . $modcomment;
      $msg = sqlesc("�� hefur fengi� a�v�run fr� $CURUSER[username].");
      $added = sqlesc(get_date_time());
      mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);
    }
  }

  if ($enabled != $curenabled)
  {
  	if ($enabled == 'yes')
  		$modcomment = gmdate("Y-m-d") . " - Virktur af " . $CURUSER['username']. ".\n". $modcomment;
  	else
  		$modcomment = gmdate("Y-m-d") . " - �virktur af " . $CURUSER['username']. ".\n". $modcomment;
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
