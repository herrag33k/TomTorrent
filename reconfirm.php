<?

require "include/bittorrent.php";

dbconn();

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
  $email = trim($_POST["email"]);
  if (!$email)
    stderr("Villa", "�� ver�ur a� sl� inn netfang");
  $res = mysql_query("SELECT * FROM users WHERE email=" . sqlesc($email) . " AND editsecret > '0' LIMIT 1") or sqlerr();
  $arr = mysql_fetch_assoc($res) or stderr("Villa", "Anna� hvort er netfangi� <b>$email</b> ekki � skr� e�a �� a� a�gangurinn er n� �egar virkur.\n");
$id = $arr['id'];
$editsecret = $arr['editsecret'];
$psecret = md5($editsecret);

$body = <<<EOD
�etta email er sent vegna �essa a� einhver skr��i �essa email addressu ($email)
� torrent s��una $SITENAME 

Ef �� skr��ir �ig ekki, vinsamlegast hunsa�u �ennan p�st.
Notandinn sem skr��i �ig var me� ip t�luna {$_SERVER["REMOTE_ADDR"]}. Ekki svara �essum p�sti.

Til a� sta�festa a�ganginn �inn vinsamlegast far�u � eftirfarandi hlekk:

$DEFAULTBASEURL/confirm.php?id=$id&secret=$psecret

Ef �� gerir �etta, getur �� byrja� a� nota a�ganginn �inn, ef ekki
�� ver�ur a�gangi ��num eytt eftir nokkra daga.
EOD;
mail($email, "$SITENAME notandasta�festing", $body, "From: $SITEEMAIL\r\nReply-To:$SITEEMAIL")
    or stderr("Villa", "Gat ekki sent p�st, vinsamlegast l�ttu stj�rnendur vita.");
  stderr("T�kst!", "P�stur hefur veri� sendur � <b>$email</b> smelltu � hlekkinn � emailinu til a� virkja a�ganginn.\n" .
    "Vinsamlegast gefi� p�stinum nokkrar m�n�tur til a� berast.");
}
else
{
 	stdhead();
	?>
	<h1>Endursenda sta�festingarp�st</h1>
	<p>Nota�u formi� h�r a� ne�an til a� f� sendan sta�festingarp�st aftur.<br>
  (Ver�ur a� lesa p�stinn.)</p>
<p><b>Hotmail notendur f� ekki sta�festingarp�st af �skiljanlegum �st��um.</b><br>
� �eim tilvikum, sendi� anna� netfang (sem endar ekki � msn.com e�a hotmail.com) � torrent@torrent.is.<br>
Muni� a� senda p�stinn fr� netfanginu sem er skr�� fyrir a�gangnum.</p>
	<form method=post action=reconfirm.php>
	<table border=1 cellspacing=0 cellpadding=10>
	<tr><td class=rowhead>Skr�� netfang</td>
	<td><input type=text size=40 name=email></td></tr>
	<tr><td colspan=2 align=center><input type=submit value='Senda' class=btn></td></tr>
	</table>
	<?
	stdfoot();
}

?>
