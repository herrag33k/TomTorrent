<?
session_start();
require_once("include/bittorrent.php");
dbconn();

//$res = mysql_query("SELECT COUNT(*) FROM users") or sqlerr(__FILE__, __LINE__);
//$arr = mysql_fetch_row($res);
//if ($arr['0'] >= $maxusers)
//	stderr("Fyrirgefðu", "Núverandi hámarks notendafjölda, (" . number_format($maxusers) . "), hefur verið náð. Óvirkum aðgöngum er eytt reglulega, gjörðu svo vel að reyna aftur síðar...");


stdhead("Nýskráning");
if(isset($_GET['invite']))
	$invitekey = $_GET['invite'];
else
	$invitekey = '';
if (!empty($_POST['samtyk'])) {
	$_SESSION['accept'] = "ja";
	echo '<meta http-equiv="refresh" content="0;url=/signup.php?invite=' . $invitekey . '">';
}
if (!isset($_SESSION['accept'])) {
	include("skilmalar2.php");
	exit();
}
?>
ATH: Hástafir og lágstafir skipta máli, þannig að NoTanDi er ekki það sama og notandi.<br />
Spurningar varðandi þetta form? Hafðu samband við <a href="mailto:torrent@torrent.is">torrent@torrent.is</a><br />
<br />
<a href="/personuvernd.php" target="_new">Persónuverndarstefna Istorrent</a> (Opnast í nýjum glugga)<br />
<br />
<form method="post" action="takesignup.php">
<table border="1" cellspacing=0 cellpadding="10">
<tr><td align="right" class="heading">Notandanafn:</td><td align=left><input type="text" size="40" name="wantusername" /><br />Hér er hægt að nota 
alla bókstafi í íslenska stafrófinu ásamt tölustöfum.</td></tr>
<tr><td align="right" class="heading">Lykilorð:</td><td align=left><input type="password" size="40" name="wantpassword" /><br />Það er þín ábyrgð 
að velja gott lykilorð því að allt sem gerist á honum er á þína ábyrgð.</td></tr>
<tr><td align="right" class="heading">Lykilorð (aftur):</td><td align=left><input type="password" size="40" name="passagain" /></td></tr>
<tr valign=top><td align="right" class="heading">Boðslykill?:</td><td align=left><input type="text" size="40" name="invite" value="<? echo $invitekey; ?>"/>
<br /><a href="bodslykill.php">Spurt og svarað varðandi boðslykla</a>.</td></tr>
<tr valign=top><td align="right" class="heading">Netfang:</td><td align=left><input type="text" 
size="40" name="email" />
<br /><a href="http://hotmail.com">Hotmail</a> netföng fá aldrei staðfestingarpóstinn svo vinsamlegast ekki nota þau.
<table width=250 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded><font class=small>Farið er yfir netföng annað slagið og ef þetta eru ekki alvöru netfang verður aðgangnum eytt.</td></tr>
</font></td></tr></table>
</td></tr>
<tr><td align="right" class="heading"></td><td align=left><input type=checkbox name=rulesverify value=yes> Ég hef lesið reglurnar og skilmálana.<br>
<input type=checkbox name=faqverify value=yes> Ég mun lesa SOS og spjallborð áður en ég spyr spurninga.<br>
<input type=checkbox name=ageverify value=yes> Ég er að minnsta kosti 15 ára að aldri.</td></tr>
<tr><td colspan="2" align="center"><input type=submit value="Skrá! (Ýtið aðeins einusinni!)" style='height: 25px'></td></tr>
</table>
</form>
<?

stdfoot();

?>
