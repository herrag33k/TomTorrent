<?

require "include/bittorrent.php";
dbconn();

$id = 0 + $HTTP_GET_VARS["id"];
if (!$id)
	stderr("Villa", "ID vantar e�a ekki til.");

$res = mysql_query("SELECT username, class, email FROM users WHERE id=$id");
$arr = mysql_fetch_assoc($res) or stderr("Villa", "Notandi fannst ekki.");
$username = $arr["username"];
if ($arr["class"] < UC_MODERATOR)
	stderr("Villa", "�etta kerfi er a�eins nota� til a� senda stj�rnendum email.");

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
	$to = $arr["email"];

	$from = substr(trim($HTTP_POST_VARS["from"]), 0, 80);
	if ($from == "") $from = "Anonymous";

	$from_email = substr(trim($HTTP_POST_VARS["from_email"]), 0, 80);
	if ($from_email == "") $from_email = "noreply@torrent.is";
	if (!strpos($from_email, "@")) stderr("Villa", "Innslegi� email vir�ist ekki vera r�tt.");

	$from = "$from <$from_email>";

	$subject = substr(trim($HTTP_POST_VARS["subject"]), 0, 80);
	if ($subject == "") $subject = "(Ekkert efni)";
	$subject = "Fw: $subject";

	$message = trim($HTTP_POST_VARS["message"]);
	if ($message == "") stderr("Villa", "Enginn texti var � skilabo�unum!");

	$message = "Skilabo� send fr� $HTTP_SERVER_VARS[REMOTE_ADDR] �ann " . gmdate("Y-m-d H:i:s") . " GMT.\n" .
		"Athuga�u: me� �v� a� svara �essum p�sti s�r vi�takandi p�stfangi� �itt.\n" .
		"---------------------------------------------------------------------\n\n" .
		$message . "\n\n" .
		"---------------------------------------------------------------------\n$SITENAME E-Mail �j�nn\n";

	$success = mail($to, $subject, $message, "Fr�: $from", "-f$SITEEMAIL");

	if ($success)
		stderr("A�ger� t�kst", "P�sturinn hefur veri� sendur.");
	else
		stderr("A�ger� mist�kst", "Ekki t�kst a� senda p�stinn, vinsamlegast reyndu s��ar.");
}

stdhead("Hafa samband");
?>
<p><table border=0 class=main cellspacing=0 cellpadding=0><tr>
<td class=embedded><img src=/pic/email.gif></td>
<td class=embedded style='padding-left: 10px'><font size=3><b>Senda p�st til <?=$username;?></b></font></td>
</tr></table></p><h2>Ekki nota �etta til a� bi�ja um Invite</h2>
�etta er hugsa� til �ess a� eigendur h�fundarr�ttar geti haft samband vi� stj�rnendur ef h�fundarr�ttar vari� efni er � s��unni.<p>
<table border=1 cellspacing=0 cellpadding=5>
<form method=post action=email-gateway.php?id=<?=$id?>>
<tr><td class=rowhead>�itt nafn</td><td><input type=text name=from size=80></td></tr>
<tr><td class=rowhead>�itt e-mail</td><td><input type=text name=from_email size=80></td></tr>
<tr><td class=rowhead>Efni</td><td><input type=text name=subject size=80></td></tr>
<tr><td class=rowhead>Skilabo�</td><td><textarea name=message cols=80 rows=20></textarea></td></tr>
<tr><td colspan=2 align=center><input type=submit value="Senda" class=btn></td></tr>
</form>
</table>
<p>
<font class=small><b>Athugi�:</b> IP t�lur eru skr��ar og sj�st � p�stinum til a� koma � veg fyrir misnotkun.<br>
Vertu viss um a� hafa r�tt email ef �i� vilji� f� svar vi� p�stinum.</font>
</p>
<? stdfoot(); ?>
