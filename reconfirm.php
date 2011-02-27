<?

require "include/bittorrent.php";

dbconn();

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
  $email = trim($_POST["email"]);
  if (!$email)
    stderr("Villa", "Þú verður að slá inn netfang");
  $res = mysql_query("SELECT * FROM users WHERE email=" . sqlesc($email) . " AND editsecret > '0' LIMIT 1") or sqlerr();
  $arr = mysql_fetch_assoc($res) or stderr("Villa", "Annað hvort er netfangið <b>$email</b> ekki á skrá eða þá að aðgangurinn er nú þegar virkur.\n");
$id = $arr['id'];
$editsecret = $arr['editsecret'];
$psecret = md5($editsecret);

$body = <<<EOD
Þetta email er sent vegna þessa að einhver skráði þessa email addressu ($email)
á torrent síðuna $SITENAME 

Ef þú skráðir þig ekki, vinsamlegast hunsaðu þennan póst.
Notandinn sem skráði þig var með ip töluna {$_SERVER["REMOTE_ADDR"]}. Ekki svara þessum pósti.

Til að staðfesta aðganginn þinn vinsamlegast farðu á eftirfarandi hlekk:

$DEFAULTBASEURL/confirm.php?id=$id&secret=$psecret

Ef þú gerir þetta, getur þú byrjað að nota aðganginn þinn, ef ekki
þá verður aðgangi þínum eytt eftir nokkra daga.
EOD;
mail($email, "$SITENAME notandastaðfesting", $body, "From: $SITEEMAIL\r\nReply-To:$SITEEMAIL")
    or stderr("Villa", "Gat ekki sent póst, vinsamlegast láttu stjórnendur vita.");
  stderr("Tókst!", "Póstur hefur verið sendur á <b>$email</b> smelltu á hlekkinn í emailinu til að virkja aðganginn.\n" .
    "Vinsamlegast gefið póstinum nokkrar mínútur til að berast.");
}
else
{
 	stdhead();
	?>
	<h1>Endursenda staðfestingarpóst</h1>
	<p>Notaðu formið hér að neðan til að fá sendan staðfestingarpóst aftur.<br>
  (Verður að lesa póstinn.)</p>
<p><b>Hotmail notendur fá ekki staðfestingarpóst af óskiljanlegum ástæðum.</b><br>
Í þeim tilvikum, sendið annað netfang (sem endar ekki á msn.com eða hotmail.com) á torrent@torrent.is.<br>
Munið að senda póstinn frá netfanginu sem er skráð fyrir aðgangnum.</p>
	<form method=post action=reconfirm.php>
	<table border=1 cellspacing=0 cellpadding=10>
	<tr><td class=rowhead>Skráð netfang</td>
	<td><input type=text size=40 name=email></td></tr>
	<tr><td colspan=2 align=center><input type=submit value='Senda' class=btn></td></tr>
	</table>
	<?
	stdfoot();
}

?>
