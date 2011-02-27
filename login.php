<?

require_once("include/bittorrent.php");

hit_start();

dbconn();

hit_count();

stdhead("Login");

unset($returnto);
if (!empty($_GET["returnto"])) {
	$returnto = $_GET["returnto"];
	if (!isset($_GET["nowarn"])) {
		echo '<h1>Ekki skráð(ur) inn!</h1>'."\n";
		echo '<p><b>Villa:</b> Síðan sem þú ert að reyna að opna er eingöngu aðgengileg innskráðum notendum.</p>'."\n";
	}
}

?>
<form method="post" action="takelogin.php">
<p>Athugið: Þú þarft að hafa vafrakökur virkar til að geta innskráð þig.</p>
<table border="0" cellpadding="5">
<tr><td class="rowhead">Notandanafn:</td><td align="left"><input type="text" size="40" name="username" /></td></tr>
<tr><td class="rowhead">Lykilorð:</td><td align="left"><input type="password" size="40" name="password" /></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="Innskrá!" class="btn" /></td></tr>
</table>
<?

if (isset($returnto))
	echo '<input type="hidden" name="returnto" value="'. htmlspecialchars($returnto).'" />'."\n";

?>
</form>
<p>Hefurðu ekki aðgang? <a href="signup.php">Nýskráðu þig</a> núna!</p>
<?

stdfoot();

hit_end();

?>
