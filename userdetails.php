<?
ob_start("ob_gzhandler");
require "include/bittorrent.php";

dbconn(false);

loggedinorreturn();

function bark($msg)
{
  stdhead();
  stdmsg("Error", $msg);
  stdfoot();
  exit;
}

function maketable($res)
{
	global $cats;
	if (!isset($cats))
	{
		$res2 = mysql_query("SELECT id, image, name FROM categories") or sqlerr(__FILE__, __LINE__);
		while ($arr = mysql_fetch_assoc($res2))
		{
			$catimages[$arr["id"]] = $arr["image"];
			$catnames[$arr["id"]] = $arr["name"];
		}
	}
  $ret = '<table class=main border=1 cellspacing=0 cellpadding=5>' .
    '<tr><td class="colhead" align="center">Tegund</td><td class="colhead">Nafn</td><td class="colhead" align="center">TTL</td><td class="colhead" align="center">Stærð</td><td class="colhead" align="center">Uppl.</td>'."\n" .
    '<td class="colhead" align="center">Downl.</td><td class="colhead" align="center">Hlutfall</td><td class="colhead" align="center">IP-tala</td></tr>'."\n";
  while ($arr = mysql_fetch_assoc($res))
  {
    $res2 = mysql_query("SELECT name,size,category,added FROM torrents WHERE id=$arr[torrent]");
    $arr2 = mysql_fetch_assoc($res2);
    if ($arr["downloaded"] > 0)
    {
      $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
      $ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
    }
    else
      if ($arr["uploaded"] > 0)
        $ratio = "Inf.";
      else
        $ratio = "---";
	$catimage = htmlspecialchars($catimages[$arr2["category"]]);
	$catname = htmlspecialchars($catnames[$arr2["category"]]);
	$ttl = (28*24) - floor((gmtime() - sql_timestamp_to_unix_timestamp($arr2["added"])) / 3600);
	if ($ttl == 1) $ttl .= "<br>klst";
	$size = str_replace(" ", "<br>", mksize($arr2["size"]));
	$uploaded = str_replace(" ", "<br>", mksize($arr["uploaded"]));
	$downloaded = str_replace(" ", "<br>", mksize($arr["downloaded"]));
	$ip = $arr['ip'];
    $ret .= '<tr><td style="padding: 0px"><img src="pic/'.$catimage.'" alt="'.$catname.'" width="42" height="42"></td>'."\n" .
		'<td><a href=details.php?id='.$arr[torrent].'&amp;hit=1><b>';
		if(!empty($arr2['name']))
			$ret .= htmlspecialchars($arr2[name]);
		else
			$ret .= '[Ekkert nafn]';
	$ret .=	'</b></a></td><td align="center">'.$ttl.'</td><td align="center">'.$size.'</td><td align="center">'.$uploaded.'</td>'."\n" .
		'<td align="center">'.$downloaded.'</td><td align="center">'.$ratio.'</td><td 
align="center">'.$ip.'</td></tr>'."\n";
  }
  $ret .= "</table>\n";
  return $ret;
}

$id = $_GET["id"];
$verifystring = verifystring("$id",'num');
if($verifystring !== TRUE)
        die($verifystring);
#if (!is_valid_id($id))
#  bark("Slæmt auðkenni $id.");

$r = @mysql_query("SELECT * FROM users WHERE id=$id") or sqlerr();
$user = mysql_fetch_array($r) or bark("No user with ID $id.");
if ($user["status"] == "pending" && get_user_class() < UC_MODERATOR)
die("Óvirkur notandi, aðeins stjórnendur geta skoðað upplýsingar.");
$r = mysql_query("SELECT id, name, seeders, leechers, category FROM torrents WHERE owner=$id ORDER BY name") or sqlerr();
if (mysql_num_rows($r) > 0)
{
  $torrents = "<table class=main border=1 cellspacing=0 cellpadding=5>\n" .
    "<tr><td class=colhead>Tegund</td><td class=colhead>Nafn</td><td class=colhead>Deilendur</td><td class=colhead>Skráarsugur</td></tr>\n";
  while ($a = mysql_fetch_assoc($r))
  {
		$r2 = mysql_query("SELECT name, image FROM categories WHERE id=$a[category]") or sqlerr(__FILE__, __LINE__);
		$a2 = mysql_fetch_assoc($r2);
		$cat = "<img src=\"/pic/$a2[image]\" alt=\"$a2[name]\">";
      $torrents .= "<tr><td style='padding: 0px'>$cat</td><td><a href=details.php?id=" . $a["id"] . "&hit=1><b>";
		if(!empty($a['name']))
			$torrents .= htmlspecialchars($a["name"]);
		else
			$torrents .= '[Óskýrt torrent]';
		$torrents .= '</b></a></td>' .
        "<td align=right>$a[seeders]</td><td align=right>$a[leechers]</td></tr>\n";
  }
  $torrents .= "</table>";
}

if ($user["ip"] && (get_user_class() >= UC_MODERATOR || $user["id"] == $CURUSER["id"]))
{
  $ip = $user["ip"];
  $dom = @gethostbyaddr($user["ip"]);
  if ($dom == $user["ip"] || @gethostbyname($dom) != $user["ip"])
    $addr = $ip;
  else
  {
    $dom = strtoupper($dom);
    $domparts = explode(".", $dom);
    $domain = $domparts[count($domparts) - 2];
    if ($domain == "COM" || $domain == "CO" || $domain == "NET" || $domain == "NE" || $domain == "ORG" || $domain == "OR" )
      $l = 2;
    else
      $l = 1;
    $addr = "$ip ($dom)";
  }
}
if ($user['added'] == "0000-00-00 00:00:00")
  $joindate = 'N/A';
else
  $joindate = $user['added'].' ('. get_elapsed_time(sql_timestamp_to_unix_timestamp($user['added'])).' síðan)';
$lastseen = $user["last_access"];
if ($lastseen == "0000-00-00 00:00:00")
  $lastseen = "never";
else
{
  $lastseen .= " (" . get_elapsed_time(sql_timestamp_to_unix_timestamp($lastseen)) . " síðan)";
}
  $res = mysql_query('SELECT COUNT(*) FROM comments WHERE user='.$user['id']) or sqlerr();
  $arr3 = mysql_fetch_row($res);
  $torrentcomments = $arr3['0'];
  $res = mysql_query('SELECT COUNT(*) FROM posts WHERE userid='.$user['id']) or sqlerr();
  $arr3 = mysql_fetch_row($res);
  $forumposts = $arr3['0'];

//if ($user['donated'] > 0)
//  $don = "<img src=pic/starbig.gif>";

$res = mysql_query("SELECT name,flagpic FROM countries WHERE id=$user[country] LIMIT 1") or sqlerr();
if (mysql_num_rows($res) == 1)
{
  $arr = mysql_fetch_assoc($res);
  $country = "<td class=embedded><img src=/pic/flag/$arr[flagpic] alt=\"$arr[name]\" style='margin-left: 8pt'></td>";
}

if ($user['donor'] === 'yes')
	$donor = '<td class="embedded"><img src="pic/starbig.gif" alt="Gefandi" style="margin-left: 4pt"></td>';
else
	$donor = '';
if ($user['warned'] === 'yes')
	$warned = '<td class="embedded"><img src="pic/warnedbig.gif" alt="Viðvörun" style="margin-left: 4pt"></td>';
else
	$warned = '';

$res = mysql_query("SELECT torrent,uploaded,downloaded,ip FROM peers WHERE userid=$id AND seeder='no' ORDER BY torrent DESC");
if (mysql_num_rows($res) > 0)
  $leeching = maketable($res);
$res = mysql_query("SELECT torrent,uploaded,downloaded,ip FROM peers WHERE userid=$id AND seeder='yes' ORDER BY torrent DESC");
if (mysql_num_rows($res) > 0)
  $seeding = maketable($res);

stdhead("Nánar um " . $user["username"]);
$enabled = $user["enabled"] == 'yes';
echo '<p><table class="main" border="0" cellspacing="0" cellpadding="0"><tr><td class="embedded"><h1 style="margin:0px">'.$user['username'].'</h1></td>'.$donor.$warned.$country.'</tr></table></p>'."\n";

if ($user['deleted'])
  echo '<p><b>Þessum aðgangi hefur verið eytt</b></p>'."\n";
elseif (!$enabled)
  echo '<p><b>Þessi aðgangur hefur verið gerður óvirkur</b></p>'."\n";
elseif ($user['status'] !== 'confirmed')
  echo '<p><b>Þessi aðgangur hefur ekki verið staðfestur</b></p>'."\n";
elseif ($CURUSER["id"] <> $user["id"])
{
  $r = mysql_query("SELECT id FROM friends WHERE userid=$CURUSER[id] AND friendid=$id") or sqlerr(__FILE__, __LINE__);
  $friend = mysql_num_rows($r);
  $r = mysql_query("SELECT id FROM blocks WHERE userid=$CURUSER[id] AND blockid=$id") or sqlerr(__FILE__, __LINE__);
  $block = mysql_num_rows($r);

  if ($friend)
    print("<p>(<a href=friends.php?action=delete&type=friend&targetid=$id>fjarlægja úr vinalista</a>)</p>\n");
  elseif($block)
    print("<p>(<a href=friends.php?action=delete&type=block&targetid=$id>fjarlægja úr hunsunarlista</a>)</p>\n");
  else
  {
    print("<p>(<a href=friends.php?action=add&type=friend&targetid=$id>bæta við á vinalista</a>)");
    print(" - (<a href=friends.php?action=add&type=block&targetid=$id>bæta við á hunsunarlista</a>)</p>\n");
  }
}

begin_main_frame();
?>
<table width=100% border=1 cellspacing=0 cellpadding=5>
<tr><td class=rowhead width=1%>Skráningardagur</td><td align=left width=99%><?=$joindate?></td></tr>
<tr><td class=rowhead>Sást síðast</td><td align=left><?=$lastseen?></td></tr>
<?
if (get_user_class() >= UC_MODERATOR || $user['id'] == $CURUSER['id'])
  print("<tr><td class=rowhead>Netfang</td><td align=left><a href=mailto:$user[email]>$user[email]</a></td></tr>\n");
if (isset($addr))
  echo '<tr><td class="rowhead">IP fang</td><td align="left">'.$addr.' AS: '.find_AS($user['ip']).'</td></tr>'."\n";

//  if ($user["id"] == $CURUSER["id"] || get_user_class() >= UC_MODERATOR)
//	{
if($user['privacy'] == 'strong' && (get_user_class() < UC_MODERATOR && $CURUSER['id'] != $USER['id'])) {
} else {
	?>
	<tr><td class=rowhead>Deilt</td><td align=left><?=mksize($user["uploaded"])?>
	<?
	if($CURUSER['class'] == UC_SYSOP)
		echo ' <a href="/check.upload.php?id='.$user['id'].'">Deilingarsaga</a>';
	?></td></tr>
	<tr><td class=rowhead>Niðurhalað</td><td align=left><?=mksize($user["downloaded"])?></td></tr>
	<?
	if ($user["downloaded"] > 0)
	{
	  $sr = $user["uploaded"] / $user["downloaded"];
	  if ($sr >= 4)
	    $s = "w00t";
	  else if ($sr >= 2)
	    $s = "grin";
	  else if ($sr >= 1)
	    $s = "smile1";
	  else if ($sr >= 0.5)
	    $s = "noexpression";
	  else if ($sr >= 0.25)
	    $s = "sad";
	  else
	    $s = "cry";
	  $sr = "<table border=0 cellspacing=0 cellpadding=0><tr><td class=embedded><font color=" . get_ratio_color($sr) . ">" . number_format($sr, 2) . "</font></td><td class=embedded>&nbsp;&nbsp;<img src=/pic/smilies/$s.gif></td></tr></table>";
	  echo '<tr><td class="rowhead" style="vertical-align:middle">Hlutfall</td><td align="left" valign="center" style="padding-top:1px;padding-bottom:0px">'.$sr;
	$t_medlimur = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400*14)));
	$t_medlimur2 = str_replace(array(' ',':','-'),'',$user['added']);
	if(get_user_class() >= UC_MODERATOR &&
	$user['uploaded'] / $user['downloaded'] <= '0.2' &&
	$user['downloaded'] >= '2147483648' &&
	$t_medlimur2 < $t_medlimur &&
	$user['enabled'] == 'yes' &&
	(
	$user['vikufr'] < date('Ymd'))
	)
	{
		echo '<form method="POST" action="modtask.php">
		<input type="hidden" name="id" value='.$_GET['id'].'></input>
		<input type="hidden" name="action" value="hlutf-ovirkja"></input>
		<input type="hidden" name="hlutfall" value="'.number_format($user['uploaded'] / $user['downloaded'], 2).'">
		<input type="hidden" name="username" value="'.$user['username'].'">
		<input type="hidden" name="email" value="'.$user['email'].'">
		<input type="hidden" name="comment" value="'.$user['modcomment'].'">
		<input type="hidden" name="vikufr" value="'.$user['vikufr'].'">
		<input type="submit" value="Óvirkja notanda vegna lágra hlutfalla"></input>
		</form>';
	}
	if($user['vikufr'] != '0' && $CURUSER['class'] >= UC_MODERATOR) {
		if($user['vikufr'] >= date('Ymd'))
		echo '<br />ATH: Þessi notandi er á vikufresti';

		else
			echo '<br />Vikufrestur liðinn';
	}
	  echo '</td></tr>'."\n";
	}
}
//}
if ($CURUSER['class'] >= UC_MODERATOR || $CURUSER['support'] == 'yes' || $CURUSER['id'] == $user['id']) {
	echo '<tr valign="top"><td class="rowhead"><b>Hólf</b></td><td align="left">'.slots($user['id'], 'disp').'</td></tr>';
	echo '<tr valign="top"><td class="rowhead"><b>Eftirspurnir</b></td><td align="left">Ónotaðar: '.requests_free($user['id']).' - <a href="/minar_eftirsp.php?uid='.$user['id'].'">Skoða</a></td></tr>';
	echo '<tr valign="top"><td class="rowhead"><b>Boðslyklar eftir</b></td><td align="left">'.inviteleft($user['id'],$user['uploaded'],$user['downloaded'],$user['warned'],$user['added']).'</td></tr>';
}

//if ($user['donated'] > 0 && (get_user_class() >= UC_MODERATOR || $CURUSER["id"] == $user["id"]))
//  print("<tr><td class=rowhead>Gefið</td><td align=left>$$user[donated]</td></tr>\n");
if ($user["avatar"]) {
	if($user["avadult"] == 'yes' && $CURUSER["hideadult"] == 'yes')
		print("<tr><td class=rowhead>Mynd</td><td align=left>Mynd þessa notanda er merkt óviðeigandi.</td></tr>\n");
	else
		print("<tr><td class=rowhead>Mynd</td><td align=left><img src=\"" . $user["avatar"] . "\"></td></tr>\n");
}
$classi = get_user_class_name($user["class"]);
$lengd = strlen($user['title']);
if($lengd > 0) {
  if(get_user_class() >= UC_MODERATOR) { $classi .= ' (' . $user['title'] . ')'; }
  else { $classi = $user['title']; }
}
print("<tr><td class=rowhead>Staða</td><td align=left>$classi</td></tr>\n");
print("<tr><td class=rowhead>Torrent athugasemdir</td>");
if ($torrentcomments && ($user["id"] == $CURUSER["id"] || get_user_class() >= UC_MODERATOR))
	print("<td align=left><a href=userhistory.php?action=viewcomments&id=$id>$torrentcomments</a></td></tr>\n");
else
	print("<td align=left>$torrentcomments</td></tr>\n");
print("<tr><td class=rowhead>Spjallpóstar</td>");
if ($forumposts && (($user["class"] >= UC_POWER_USER && $user["id"] == $CURUSER["id"]) || get_user_class() >= UC_MODERATOR))
	print("<td align=left><a href=userhistory.php?action=viewposts&id=$id>$forumposts</a></td></tr>\n");
else
	print("<td align=left>$forumposts</td></tr>\n");
echo '<tr><td class=rowhead>Undirskrift</td><td align=left>'.signiture($user['id']).'</td></tr>'."\n";
if(get_user_class() >= UC_MODERATOR || $CURUSER['id'] == $user['id']) {
	if (isset($torrents))
	  print("<tr valign=top><td class=rowhead>Innsend torrent</td><td align=left>$torrents</td></tr>\n");
	if (isset($seeding))
	  print("<tr valign=top><td class=rowhead>Er að deila</td><td align=left>$seeding</td></tr>\n");
	if (isset($leeching))
	  print("<tr valign=top><td class=rowhead>Skráarsuga á</td><td align=left>$leeching</td></tr>\n");
	include("who_invited.php");
}
if ($user["info"])
 print("<tr valign=top><td align=left colspan=2 class=text bgcolor=#F4F4F0>" . format_comment($user["info"]) . "</td></tr>\n");

if ($CURUSER["id"] != $user["id"])
	if (get_user_class() >= UC_MODERATOR)
  	$showpmbutton = 1;
	elseif ($user["acceptpms"] == "yes")
	{
		$r = mysql_query("SELECT id FROM blocks WHERE userid=$user[id] AND blockid=$CURUSER[id]") or sqlerr(__FILE__,__LINE__);
		$showpmbutton = (mysql_num_rows($r) == 1 ? 0 : 1);
	}
	elseif ($user["acceptpms"] == "friends")
	{
		$r = mysql_query("SELECT id FROM friends WHERE userid=$user[id] AND friendid=$CURUSER[id]") or sqlerr(__FILE__,__LINE__);
		$showpmbutton = (mysql_num_rows($r) == 1 ? 1 : 0);
	}
if ($showpmbutton)
	print("<tr><td colspan=2 align=center><form method=get action=sendmessage.php><input type=hidden name=receiver value=" .
		$user["id"] . "><input type=submit value=\"Senda skilaboð\" style='height: 23px'></form></td></tr>");
print("<tr><td colspan=2 align=center><a href=report.php?user=$user[id]><b>Tilkynna þennan notanda</b></a> - Það er ekki reglubrot að vera vanvirkur</td></tr>");
print("</table>\n");

if (get_user_class() >= UC_MODERATOR && $user["class"] < get_user_class())
{
  begin_frame("Breyta notanda", true);
  print("<form method=post action=modtask.php>\n");
  print("<input type=hidden name='action' value='edituser'>\n");
  print("<input type=hidden name='userid' value='$id'>\n");
  print("<input type=hidden name='returnto' value='userdetails.php?id=$id'>\n");
  print("<table class=main border=1 cellspacing=0 cellpadding=5>\n");
  echo '<tr><td class="rowhead">Titill</td><td colspan="2" align="left"><input type="text" size="60" name="title" value="'.htmlspecialchars($user['title']).'"></tr>'."\n";
	$avatar = htmlspecialchars($user["avatar"]);
  print("<tr><td class=rowhead>Smámyndaslóð</td><td colspan=2 align=left><input type=text size=60 name=avatar value=\"$avatar\"></tr>\n");
  print("<tr><td class=rowhead>Óviðeigandi smámynd</td><td colspan=2 align=left><input type=radio name=avadult value=yes" .($user["avadult"] == "yes" ? " checked" : "").">Já <input type=radio name=avadult value=no" .($user["avadult"] == "no" ? " checked" : "").">Nei</td></tr>\n");  		
	// we do not want mods to be able to change user classes or amount donated...
	if ($CURUSER["class"] < UC_SYSOP)
	  print("<input type=hidden name=donor value=$user[donor]>\n");
	else
	{
	  print("<tr><td class=rowhead>Gefandi</td><td colspan=2 align=left><input type=radio name=donor value=yes" .($user["donor"] == "yes" ? " checked" : "").">Já <input type=radio name=donor value=no" .($user["donor"] == "no" ? " checked" : "").">Nei</td></tr>\n");
	}

	if (get_user_class() == UC_MODERATOR && $user["class"] >= UC_MODERATOR)
	  printf("<input type=hidden name=class value=$user[class]\n");
	else
	{
	  print("<tr><td class=rowhead>Staða</td><td colspan=2 align=left><select name=class>\n");
//	  if (get_user_class() == UC_MODERATOR)
//	    $maxclass = UC_MODERATOR-1;
//	  else
	    $maxclass = get_user_class() - 1;
	  for ($i = 0; $i <= $maxclass; ++$i)
	    echo '<option value="'.$i.'"'.($user['class'] == $i ? ' selected="selected"' : '').'>'.get_user_class_name($i)."\n";
	  echo '</select></td></tr>'."\n";
	}
	echo '<tr><td class="rowhead">Auðkennislykill</td><td colspan="2" align="left"><input name="resetpasskey" value="1" type="checkbox"> Endursetja auðkennislykil</td></tr>'."\n";
	if($CURUSER['class'] >= UC_MODERATOR)
		echo '<tr><td class="rowhead">Hröð tenging</td><td colspan="2" align="left"><input name="24rule" value="1" type="checkbox"';
	if($user['24rule'] === '1')
		echo ' checked="checked"';
	echo '>Gefa heimild til að fara framhjá 24 klst. deilireglunni<br />Eingöngu 10mbit (í deilihraða) eða hraðari 
tengingar!</td></tr>'."\n";
	$modcomment = htmlspecialchars($user["modcomment"]);
//Support
	$supportfor = htmlspecialchars($user["supportfor"]);
print("<tr><td class=rowhead>Hjálpari</td><td colspan=2 align=left><input type=radio name=support value=yes" .($user["support"] == "yes" ? " checked" : "").">Já <input type=radio name=support value=no" .($user["support"] == "no" ? " checked" : "").">Nei</td></tr>\n");
print("<tr><td class=rowhead>Hjálpar með:</td><td colspan=2 align=left><textarea cols=60 rows=6 name=supportfor>$supportfor</textarea></td></tr>\n");
//Support
	print("<tr><td class=rowhead>Athugasemdir</td><td colspan=2 align=left><textarea cols=60 rows=6 name=modcomment>$modcomment</textarea></td></tr>\n");
	$warned = $user["warned"] == "yes";
 	print("<tr><td class=rowhead>Varaður við</td><td align=left><input name=warned value='yes' type=radio" . ($warned ? " checked" : "") . ">Já <input name=warned value='no' type=radio" . (!$warned ? " checked" : "") . ">Nei</td>");
	if ($warned)
	{
		$warneduntil = $user['warneduntil'];
		if ($warneduntil == '0000-00-00 00:00:00')
    	print("<td align=center>(óákveðinn viðvörunartími)</td></tr>\n");
		else
		{
    	print("<td align=center>Til $warneduntil");
	    print(" (" . mkprettytime(strtotime($warneduntil) - gmtime()) . " eftir)</td></tr>\n");
 	  }
  }
  else
  {
    print("<td>Refsa í <select name=warnlength>\n");
    print("<option value=0>------</option>\n");
    print("<option value=12>12 klst.</option>\n");
    print("<option value=24>1 dag</option>\n");
    print("<option value=72>3 daga</option>\n");
    print("<option value=168>1 viku</option>\n");
    print("<option value=672>4 vikur</option>\n");
    print("</select></td></tr>\n");
  }
  print("<tr><td class=rowhead>Virkur</td><td colspan=2 align=left><input name=enabled value='yes' type=radio" . ($enabled ? " checked" : "") . ">Já <input name=enabled value='no' type=radio" . (!$enabled ? " checked" : "") . ">Nei</td></tr>\n");

  print("</td></tr>");
  print("<tr><td colspan=3 align=center><input type=submit class=btn value='Breyta'></td></tr>\n");
  print("</table>\n");
  print("</form>\n");
  end_frame();
}
end_main_frame();
stdfoot();

?>
