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


stdhead($CURUSER["username"] . " Stillingars��a", false);
if ($_GET["edited"]) {
	print("<h1>Stillingar uppf�r�ar!</h1>\n");
	if ($_GET["mailsent"])
		print("<h2>Sta�festingarp�stur hefur veri� sendur!</h2>\n");
}
elseif ($_GET["emailch"])
	print("<h1>P�stfangi breytt!</h1>\n");
else
	print("<h1><a href=userdetails.php?id=$CURUSER[id]>S��an ��n fyrir almenning</a></h1>\n");
	print("<h1><a href=invites.php>Bo�s��a</a></h1>\n");

?>
<table border="1" cellspacing="0" cellpadding="10" align="center" width=500>
<tr>
<td align="center" width="25%"><a href=logout.php><b>�tskr� sig</b></a></td>
<td align="center" width="25%"><a href=mytorrents.php><b>M�n torrent</b></a></td>
<td align="center" width="25%"><a href=mymyndir.php><b>M�nar myndir</b></a></td>
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

$countries = "<option value=0>---- Ekkert vali� ----</option>\n";
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

tr("Sam�ykkja skilabo� fr�",
"<input type=radio name=acceptpms" . ($CURUSER["acceptpms"] == "yes" ? " checked" : "") . " value=yes>�llum
<input type=radio name=acceptpms" .  ($CURUSER["acceptpms"] == "friends" ? " checked" : "") . " value=friends>Vinum
<input type=radio name=acceptpms" .  ($CURUSER["acceptpms"] == "no" ? " checked" : "") . " value=no>Stj�rnendum"
,1);



tr("Ey�a skilabo�um", "<input type=checkbox name=deletepms" . ($CURUSER["deletepms"] == "yes" ? " checked" : "") . "> (�egar �� svarar p�sti)",1);
tr("Vista skilabo�", "<input type=checkbox name=savepms" . ($CURUSER["savepms"] == "yes" ? " checked" : "") . "> (Sem �� sendir)",1);
if($CURUSER['donor'] === 'yes' || $CURUSER['class'] >= UC_POWER_USER)
	tr("Titill", "BB k��i virkar ekki � titlum<br /><input type=\"text\" name=\"titlechange\" size=60 maxlength=60 value=\"" . htmlspecialchars($CURUSER["title"]) . "\" />", 1);

if($CURUSER['class'] >= UC_USER || $CURUSER['donor'] === 'yes')
	include('sign.php');

$r = mysql_query("SELECT id,name FROM categories ORDER BY name") or sqlerr();
$categories = "(Hinir munu samt sj�st ef �a� er vali�)<br>\n";
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
tr("S�na flokka",$categories,1);
tr("�ema", "<select name=stylesheet>\n$stylesheets\n</select>",1);
/* tr("Country", "<select name=country>\n$countries\n</select>",1);*/
tr("Myndasl��", "<input name=avatar size=50 value=\"" . htmlspecialchars($CURUSER["avatar"]) .
  "\"><br><input type=checkbox name=avadult" . ($CURUSER["avadult"] == "yes" ? " checked" : "") . " value=yes> Mynd getur fari� fyrir brj�st � sumum.<br>\nBreidd � ekki a� vera meiri en 150 pixlar.",1);
tr("Torrent � hverri s��u", "<input type=text size=10 name=torrentsperpage value=$CURUSER[torrentsperpage]> (0=Kerfi� stillir fj�lda)",1);
tr("Umr��uefni � hverri s��u", "<input type=text size=10 name=topicsperpage value=$CURUSER[topicsperpage]> (0=Kerfi� stillir fj�lda)",1);
tr("Sv�r � hverri s��u", "<input type=text size=10 name=postsperpage value=$CURUSER[postsperpage]> (0=Kerfi� stillir fj�lda)",1);
tr("Pers�numyndir", "<input type=checkbox name=avatars" . ($CURUSER["avatars"] == "yes" ? " checked" : "") . "> S�na<br>
<input type=checkbox name=hideadult" . ($CURUSER["hideadult"] == "yes" ? " checked" : "") . " value=yes> Fela myndir sem g�tu veri� �vi�eigandi.",1);
tr('S�na n� torrent', "<input type=checkbox name=birta_nytt" . ($CURUSER["birta_nytt"] == '1' ? " checked" : "") . " value=1> Birta litla mynd hj� hverju torrenti sem merkir a� torrenti� er n�tt",1);
tr("Endursetja au�kenni","<input type=checkbox name=resetpasskey value=1 /><br><font class=small>�llum torrentum sem �� ert me� � gangi ver�ur�u a� ni�urhala aftur til a� geta haldi� �fram a� deila/s�kja.</font>", 1);
tr("Uppl�singar", "<textarea name=info cols=80 rows=8>" . $CURUSER["info"] . "</textarea><br>S�nt � uppl�singa s��unni �inni. M� innihalda <a href=tags.php target=_new>BB k��a</a>.", 1);
tr("Netfang", "<input type=\"text\" name=\"email\" size=50 value=\"" . htmlspecialchars($CURUSER["email"]) . "\" />", 1);
print("<tr><td colspan=\"2\" align=left><b>ATH:</b> �� f�r� sendann sta�festingarp�st til a� breyta um netfang.</td></tr>\n");
tr("N�tt lykilor�", "<input type=\"password\" name=\"chpassword\" size=\"50\" />", 1);
tr("Lykilor� aftur", "<input type=\"password\" name=\"passagain\" size=\"50\" />", 1);

function priv($name, $descr) {
	global $CURUSER;
	if ($CURUSER["privacy"] == $name)
		return "<input type=radio name=privacy value=$name checked=checked> $descr";
	return "<input type=radio name=privacy value=$name> $descr";
}
if (get_user_class() >= UC_MODERATOR)
 tr("Fri�helgi",  priv("normal", "Venjulegt") . "<br>" . priv("low", "L�gt (p�stfang s�st)") . "<br>" . priv("strong", "Miki� (Engar uppl�singar gefnar til notenda)"), 1);
if(!$CURUSER['kennitala'])
	tr("Kennitala", "�� getur merkt a�ganginn me� kennit�lu<br /><input type=\"text\" name=\"kennitala\" size=\"10\" maxlength=\"10\" /><br /><b>�ess ber a� geta a� ekki er h�gt a� sko�a, breyta n� ey�a kennit�lunni eftir a� h�n hefur veri� r�tt slegin inn.</b> Stj�rnendur munu ekki framkv�ma sl�kar bei�nir fyrir �ig ��tt �� �skir �ess. Fari� ver�ur me� kennit�lur sem pers�nuuppl�singar skv. <a href=\"/personuvernd.php\" target=\"_new\">pers�nuverndarstefnu Istorrent.</a><br /><br />Me� �v� a� merkja a�ganginn me� kennit�lu f�r�u eftirfarandi s�rkj�r:<br />Ni�urhal afm�lisdagsins ��ns ver�ur dregi� fr� daginn eftir.<br /><br />Eftir a� kennitalan er fest inn, �� ver�ur ekki h�gt a� nota hana � ��rum a�gangi.", 1); else
	tr("Kennitala","�� hefur gefi� upp kennit�luna ��na. �� getur ekki s�� hana, breytt henni n� eytt henni �r kerfinu. Stj�rnendur munu ekki framkv�ma sl�kt fyrir �ig.<br /><br />Me� �v� a� hafa merkt kennit�luna hefur�u hloti� eftirfarandi s�rkj�r:<br />Ni�urhal afm�lisdagsins ��ns ver�ur dreginn fr� daginn eftir.", 1);
tr('Birta afm�li', "<input type=checkbox name=birta_afm" . ($CURUSER["birta_afm"] == '1' ? " checked" : "") . " value=1> Leyfa birtingu � listanum yfir afm�lisb�rn � afm�lisdaginn minn<br />(eing�ngu birt ef �� hefur gefi� upp kennit�luna ��na h�r a� ofan)",1);

tr('Topplistar', "<input type=checkbox name=notoplist" . ($CURUSER["notoplist"] == '1' ? " checked" : "") . " value=1> Ekki l�ta mig birtast � topplista",1);
if($CURUSER['menuhide'] == '1')
	$menuhide1 = '1';
elseif($CURUSER['menuhide'] == '2')
	$menuhide2 = '1';
elseif($CURUSER['menuhide'] == '3') {
	$menuhide1 = '1';
	$menuhide2 = '1';
}
tr('Efnisyfirlitsra�ir', "Ekki birta � efnisyfirliti:<br /><input type=checkbox name=menuhide1" . ($menuhide1 
== '1' ? " checked" : "") . " value=1> Uppl�singar��<br / >
<input type=checkbox name=menuhide2" . ($menuhide2 == '1' ? " checked" : "") . " value=2> Hj�lparr��<br / >",1);

?>
<tr><td colspan="2" align="center"><input type="submit" value="Breyta stillingum!" style='height: 25px'> <input type="reset" value="H�tta vi� stillingar!" style='height: 25px'></td></tr>
</table>
</form>
</td>
</tr>
</table>

<?
print("<p><a href=users.php><b>Sko�a notanda listann</b></a></p>");
stdfoot();

hit_end();
?>
