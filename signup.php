<?
session_start();
require_once("include/bittorrent.php");
dbconn();

//$res = mysql_query("SELECT COUNT(*) FROM users") or sqlerr(__FILE__, __LINE__);
//$arr = mysql_fetch_row($res);
//if ($arr['0'] >= $maxusers)
//	stderr("Fyrirgef�u", "N�verandi h�marks notendafj�lda, (" . number_format($maxusers) . "), hefur veri� n��. �virkum a�g�ngum er eytt reglulega, gj�r�u svo vel a� reyna aftur s��ar...");


stdhead("N�skr�ning");
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
ATH: H�stafir og l�gstafir skipta m�li, �annig a� NoTanDi er ekki �a� sama og notandi.<br />
Spurningar var�andi �etta form? Haf�u samband vi� <a href="mailto:torrent@torrent.is">torrent@torrent.is</a><br />
<br />
<a href="/personuvernd.php" target="_new">Pers�nuverndarstefna Istorrent</a> (Opnast � n�jum glugga)<br />
<br />
<form method="post" action="takesignup.php">
<table border="1" cellspacing=0 cellpadding="10">
<tr><td align="right" class="heading">Notandanafn:</td><td align=left><input type="text" size="40" name="wantusername" /><br />H�r er h�gt a� nota 
alla b�kstafi � �slenska stafr�finu �samt t�lust�fum.</td></tr>
<tr><td align="right" class="heading">Lykilor�:</td><td align=left><input type="password" size="40" name="wantpassword" /><br />�a� er ��n �byrg� 
a� velja gott lykilor� �v� a� allt sem gerist � honum er � ��na �byrg�.</td></tr>
<tr><td align="right" class="heading">Lykilor� (aftur):</td><td align=left><input type="password" size="40" name="passagain" /></td></tr>
<tr valign=top><td align="right" class="heading">Bo�slykill?:</td><td align=left><input type="text" size="40" name="invite" value="<? echo $invitekey; ?>"/>
<br /><a href="bodslykill.php">Spurt og svara� var�andi bo�slykla</a>.</td></tr>
<tr valign=top><td align="right" class="heading">Netfang:</td><td align=left><input type="text" 
size="40" name="email" />
<br /><a href="http://hotmail.com">Hotmail</a> netf�ng f� aldrei sta�festingarp�stinn svo vinsamlegast ekki nota �au.
<table width=250 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded><font class=small>Fari� er yfir netf�ng anna� slagi� og ef �etta eru ekki alv�ru netfang ver�ur a�gangnum eytt.</td></tr>
</font></td></tr></table>
</td></tr>
<tr><td align="right" class="heading"></td><td align=left><input type=checkbox name=rulesverify value=yes> �g hef lesi� reglurnar og skilm�lana.<br>
<input type=checkbox name=faqverify value=yes> �g mun lesa SOS og spjallbor� ��ur en �g spyr spurninga.<br>
<input type=checkbox name=ageverify value=yes> �g er a� minnsta kosti 15 �ra a� aldri.</td></tr>
<tr><td colspan="2" align="center"><input type=submit value="Skr�! (�ti� a�eins einusinni!)" style='height: 25px'></td></tr>
</table>
</form>
<?

stdfoot();

?>
