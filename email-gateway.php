<?

require "include/bittorrent.php";
dbconn();

$id = 0 + $HTTP_GET_VARS["id"];
if (!$id)
	stderr("Villa", "ID vantar eða ekki til.");

$res = mysql_query("SELECT username, class, email FROM users WHERE id=$id");
$arr = mysql_fetch_assoc($res) or stderr("Villa", "Notandi fannst ekki.");
$username = $arr["username"];
if ($arr["class"] < UC_MODERATOR)
	stderr("Villa", "Þetta kerfi er aðeins notað til að senda stjórnendum email.");

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
	$to = $arr["email"];

	$from = substr(trim($HTTP_POST_VARS["from"]), 0, 80);
	if ($from == "") $from = "Anonymous";

	$from_email = substr(trim($HTTP_POST_VARS["from_email"]), 0, 80);
	if ($from_email == "") $from_email = "noreply@torrent.is";
	if (!strpos($from_email, "@")) stderr("Villa", "Innslegið email virðist ekki vera rétt.");

	$from = "$from <$from_email>";

	$subject = substr(trim($HTTP_POST_VARS["subject"]), 0, 80);
	if ($subject == "") $subject = "(Ekkert efni)";
	$subject = "Fw: $subject";

	$message = trim($HTTP_POST_VARS["message"]);
	if ($message == "") stderr("Villa", "Enginn texti var í skilaboðunum!");

	$message = "Skilaboð send frá $HTTP_SERVER_VARS[REMOTE_ADDR] þann " . gmdate("Y-m-d H:i:s") . " GMT.\n" .
		"Athugaðu: með því að svara þessum pósti sér viðtakandi póstfangið þitt.\n" .
		"---------------------------------------------------------------------\n\n" .
		$message . "\n\n" .
		"---------------------------------------------------------------------\n$SITENAME E-Mail þjónn\n";

	$success = mail($to, $subject, $message, "Frá: $from", "-f$SITEEMAIL");

	if ($success)
		stderr("Aðgerð tókst", "Pósturinn hefur verið sendur.");
	else
		stderr("Aðgerð mistókst", "Ekki tókst að senda póstinn, vinsamlegast reyndu síðar.");
}

stdhead("Hafa samband");
?>
<p><table border=0 class=main cellspacing=0 cellpadding=0><tr>
<td class=embedded><img src=/pic/email.gif></td>
<td class=embedded style='padding-left: 10px'><font size=3><b>Senda póst til <?=$username;?></b></font></td>
</tr></table></p><h2>Ekki nota þetta til að biðja um Invite</h2>
Þetta er hugsað til þess að eigendur höfundarréttar geti haft samband við stjórnendur ef höfundarréttar varið efni er á síðunni.<p>
<table border=1 cellspacing=0 cellpadding=5>
<form method=post action=email-gateway.php?id=<?=$id?>>
<tr><td class=rowhead>Þitt nafn</td><td><input type=text name=from size=80></td></tr>
<tr><td class=rowhead>Þitt e-mail</td><td><input type=text name=from_email size=80></td></tr>
<tr><td class=rowhead>Efni</td><td><input type=text name=subject size=80></td></tr>
<tr><td class=rowhead>Skilaboð</td><td><textarea name=message cols=80 rows=20></textarea></td></tr>
<tr><td colspan=2 align=center><input type=submit value="Senda" class=btn></td></tr>
</form>
</table>
<p>
<font class=small><b>Athugið:</b> IP tölur eru skráðar og sjást í póstinum til að koma í veg fyrir misnotkun.<br>
Vertu viss um að hafa rétt email ef þið viljið fá svar við póstinum.</font>
</p>
<? stdfoot(); ?>
