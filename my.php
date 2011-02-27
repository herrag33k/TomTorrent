<?

require_once("include/bittorrent.php");

hit_start();

dbconn(false);

hit_count();

loggedinorreturn();
$res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " AND location IN ('in', 'both')") or print(mysql_error());
$arr = mysql_fetch_row($res);
$messages = $arr[0];
$res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " AND location IN ('in', 'both') AND unread='yes'") or print(mysql_error());
$arr = mysql_fetch_row($res);
$unread = $arr[0];
$res = mysql_query("SELECT COUNT(*) FROM messages WHERE sender=" . $CURUSER["id"] . " AND location IN ('out', 'both')") or print(mysql_error());
$arr = mysql_fetch_row($res);
$outmessages = $arr[0];


stdhead($CURUSER["username"] . " Stillingarsíða", false);
if ($_GET["edited"]) {
	print("<h1>Stillingar uppfærðar!</h1>\n");
	if ($_GET["mailsent"])
		print("<h2>Staðfestingarpóstur hefur verið sendur!</h2>\n");
}
elseif ($_GET["emailch"])
	print("<h1>Póstfangi breytt!</h1>\n");
else
	print("<h1><a href=userdetails.php?id=$CURUSER[id]>Síðan þín fyrir almenning</a></h1>\n");
	print("<h1><a href=invites.php>Boðsíða</a></h1>\n");

?>
<table border="1" cellspacing="0" cellpadding="10" align="center" width=500>
<tr>
<td align="center" width="25%"><a href=logout.php><b>Útskrá sig</b></a></td>
<td align="center" width="25%"><a href=mytorrents.php><b>Mín torrent</b></a></td>
<td align="center" width="25%"><a href=mymyndir.php><b>Mínar myndir</b></a></td>
<td align="center" width="25%"><a href=friends.php><b>Notendalistinn minn</b></a></td>
</tr>
<tr>
<td colspan="4">
<form method="post" action="takeprofedit.php">
<table border="1" cellspacing=0 cellpadding="5" width="100%">
<?

/***********************

$res = mysql_query("SELECT COUNT(*) FROM ratings WHERE user=" . $CURUSER["id"]);
$row = mysql_fetch_array($res);
tr("Ratings submitted", $row[0]);

$res = mysql_query("SELECT COUNT(*) FROM comments WHERE user=" . $CURUSER["id"]);
$row = mysql_fetch_array($res);
tr("Written comments", $row[0]);

****************/

$ss_r = mysql_query("SELECT * from stylesheets") or die;
$ss_sa = array();
while ($ss_a = mysql_fetch_array($ss_r))
{
  $ss_id = $ss_a["id"];
  $ss_name = $ss_a["name"];
  $ss_sa[$ss_name] = $ss_id;
}
ksort($ss_sa);
reset($ss_sa);
while (list($ss_name, $ss_id) = each($ss_sa))
{
  if ($ss_id == $CURUSER["stylesheet"]) $ss = " selected"; else $ss = "";
  $stylesheets .= "<option value=$ss_id$ss>$ss_name</option>\n";
}

$countries = "<option value=0>---- Ekkert valið ----</option>\n";
$ct_r = mysql_query("SELECT id,name FROM countries ORDER BY name") or die;
while ($ct_a = mysql_fetch_array($ct_r))
  $countries .= "<option value=$ct_a[id]" . ($CURUSER["country"] == $ct_a['id'] ? " selected" : "") . ">$ct_a[name]</option>\n";

function format_tz($a)
{
	$h = floor($a);
	$m = ($a - floor($a)) * 60;
	return ($a >= 0?"+":"-") . (strlen(abs($h)) > 1?"":"0") . abs($h) .
		":" . ($m==0?"00":$m);
}

tr("Samþykkja skilaboð frá",
"<input type=radio name=acceptpms" . ($CURUSER["acceptpms"] == "yes" ? " checked" : "") . " value=yes>Öllum
<input type=radio name=acceptpms" .  ($CURUSER["acceptpms"] == "friends" ? " checked" : "") . " value=friends>Vinum
<input type=radio name=acceptpms" .  ($CURUSER["acceptpms"] == "no" ? " checked" : "") . " value=no>Stjórnendum"
,1);



tr("Eyða skilaboðum", "<input type=checkbox name=deletepms" . ($CURUSER["deletepms"] == "yes" ? " checked" : "") . "> (Þegar þú svarar pósti)",1);
tr("Vista skilaboð", "<input type=checkbox name=savepms" . ($CURUSER["savepms"] == "yes" ? " checked" : "") . "> (Sem þú sendir)",1);
if($CURUSER['donor'] === 'yes' || $CURUSER['class'] >= UC_POWER_USER)
	tr("Titill", "BB kóði virkar ekki í titlum<br /><input type=\"text\" name=\"titlechange\" size=60 maxlength=60 value=\"" . htmlspecialchars($CURUSER["title"]) . "\" />", 1);

if($CURUSER['class'] >= UC_USER || $CURUSER['donor'] === 'yes')
	include('sign.php');

$r = mysql_query("SELECT id,name FROM categories ORDER BY name") or sqlerr();
$categories = "(Hinir munu samt sjást ef það er valið)<br>\n";
if (mysql_num_rows($r) > 0)
{
	$categories .= "<table><tr>\n";
	$i = 0;
	while ($a = mysql_fetch_assoc($r))
	{
	  $categories .=  ($i && $i % 2 == 0) ? "</tr><tr>" : "";
	  $categories .= "<td class=bottom><div align=right>&nbsp;&nbsp;" . htmlspecialchars($a["name"]) . "<input name=cat$a[id] type=\"checkbox\" " . (strpos($CURUSER['notifs'], "[cat$a[id]]") !== false ? " checked" : "") . " value='yes'></div></td>\n";
	  ++$i;
	}
	$categories .= "</tr></table>\n";
}

/*tr("Email notification", "<input type=checkbox name=pmnotif" . (strpos($CURUSER['notifs'], "[pm]") !== false ? " checked" : "") . " value=yes> Notify me when I have received a PM<br>\n" .
	 "<input type=checkbox name=emailnotif" . (strpos($CURUSER['notifs'], "[email]") !== false ? " checked" : "") . " value=yes> Notify me when a torrent is uploaded in one of <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; my default browsing categories.\n"
   , 1);*/
tr("Sýna flokka",$categories,1);
tr("Þema", "<select name=stylesheet>\n$stylesheets\n</select>",1);
/* tr("Country", "<select name=country>\n$countries\n</select>",1);*/
tr("Myndaslóð", "<input name=avatar size=50 value=\"" . htmlspecialchars($CURUSER["avatar"]) .
  "\"><br><input type=checkbox name=avadult" . ($CURUSER["avadult"] == "yes" ? " checked" : "") . " value=yes> Mynd getur farið fyrir brjóst á sumum.<br>\nBreidd á ekki að vera meiri en 150 pixlar.",1);
tr("Torrent á hverri síðu", "<input type=text size=10 name=torrentsperpage value=$CURUSER[torrentsperpage]> (0=Kerfið stillir fjölda)",1);
tr("Umræðuefni á hverri síðu", "<input type=text size=10 name=topicsperpage value=$CURUSER[topicsperpage]> (0=Kerfið stillir fjölda)",1);
tr("Svör á hverri síðu", "<input type=text size=10 name=postsperpage value=$CURUSER[postsperpage]> (0=Kerfið stillir fjölda)",1);
tr("Persónumyndir", "<input type=checkbox name=avatars" . ($CURUSER["avatars"] == "yes" ? " checked" : "") . "> Sýna<br>
<input type=checkbox name=hideadult" . ($CURUSER["hideadult"] == "yes" ? " checked" : "") . " value=yes> Fela myndir sem gætu verið óviðeigandi.",1);
tr('Sýna ný torrent', "<input type=checkbox name=birta_nytt" . ($CURUSER["birta_nytt"] == '1' ? " checked" : "") . " value=1> Birta litla mynd hjá hverju torrenti sem merkir að torrentið er nýtt",1);
tr("Endursetja auðkenni","<input type=checkbox name=resetpasskey value=1 /><br><font class=small>Öllum torrentum sem þú ert með í gangi verðurðu að niðurhala aftur til að geta haldið áfram að deila/sækja.</font>", 1);
tr("Upplýsingar", "<textarea name=info cols=80 rows=8>" . $CURUSER["info"] . "</textarea><br>Sýnt á upplýsinga síðunni þinni. Má innihalda <a href=tags.php target=_new>BB kóða</a>.", 1);
tr("Netfang", "<input type=\"text\" name=\"email\" size=50 value=\"" . htmlspecialchars($CURUSER["email"]) . "\" />", 1);
print("<tr><td colspan=\"2\" align=left><b>ATH:</b> Þú færð sendann staðfestingarpóst til að breyta um netfang.</td></tr>\n");
tr("Nýtt lykilorð", "<input type=\"password\" name=\"chpassword\" size=\"50\" />", 1);
tr("Lykilorð aftur", "<input type=\"password\" name=\"passagain\" size=\"50\" />", 1);

function priv($name, $descr) {
	global $CURUSER;
	if ($CURUSER["privacy"] == $name)
		return "<input type=radio name=privacy value=$name checked=checked> $descr";
	return "<input type=radio name=privacy value=$name> $descr";
}
if (get_user_class() >= UC_MODERATOR)
 tr("Friðhelgi",  priv("normal", "Venjulegt") . "<br>" . priv("low", "Lágt (póstfang sést)") . "<br>" . priv("strong", "Mikið (Engar upplýsingar gefnar til notenda)"), 1);
if(!$CURUSER['kennitala'])
	tr("Kennitala", "Þú getur merkt aðganginn með kennitölu<br /><input type=\"text\" name=\"kennitala\" size=\"10\" maxlength=\"10\" /><br /><b>Þess ber að geta að ekki er hægt að skoða, breyta né eyða kennitölunni eftir að hún hefur verið rétt slegin inn.</b> Stjórnendur munu ekki framkvæma slíkar beiðnir fyrir þig þótt þú óskir þess. Farið verður með kennitölur sem persónuupplýsingar skv. <a href=\"/personuvernd.php\" target=\"_new\">persónuverndarstefnu Istorrent.</a><br /><br />Með því að merkja aðganginn með kennitölu færðu eftirfarandi sérkjör:<br />Niðurhal afmælisdagsins þíns verður dregið frá daginn eftir.<br /><br />Eftir að kennitalan er fest inn, þá verður ekki hægt að nota hana á öðrum aðgangi.", 1); else
	tr("Kennitala","Þú hefur gefið upp kennitöluna þína. Þú getur ekki séð hana, breytt henni né eytt henni úr kerfinu. Stjórnendur munu ekki framkvæma slíkt fyrir þig.<br /><br />Með því að hafa merkt kennitöluna hefurðu hlotið eftirfarandi sérkjör:<br />Niðurhal afmælisdagsins þíns verður dreginn frá daginn eftir.", 1);
tr('Birta afmæli', "<input type=checkbox name=birta_afm" . ($CURUSER["birta_afm"] == '1' ? " checked" : "") . " value=1> Leyfa birtingu á listanum yfir afmælisbörn á afmælisdaginn minn<br />(eingöngu birt ef þú hefur gefið upp kennitöluna þína hér að ofan)",1);

tr('Topplistar', "<input type=checkbox name=notoplist" . ($CURUSER["notoplist"] == '1' ? " checked" : "") . " value=1> Ekki láta mig birtast á topplista",1);
if($CURUSER['menuhide'] == '1')
	$menuhide1 = '1';
elseif($CURUSER['menuhide'] == '2')
	$menuhide2 = '1';
elseif($CURUSER['menuhide'] == '3') {
	$menuhide1 = '1';
	$menuhide2 = '1';
}
tr('Efnisyfirlitsraðir', "Ekki birta á efnisyfirliti:<br /><input type=checkbox name=menuhide1" . ($menuhide1 
== '1' ? " checked" : "") . " value=1> Upplýsingaröð<br / >
<input type=checkbox name=menuhide2" . ($menuhide2 == '1' ? " checked" : "") . " value=2> Hjálparröð<br / >",1);

?>
<tr><td colspan="2" align="center"><input type="submit" value="Breyta stillingum!" style='height: 25px'> <input type="reset" value="Hætta við stillingar!" style='height: 25px'></td></tr>
</table>
</form>
</td>
</tr>
</table>

<?
print("<p><a href=users.php><b>Skoða notanda listann</b></a></p>");
stdfoot();

hit_end();
?>
