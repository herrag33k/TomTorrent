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
		echo '<h1>Ekki skr��(ur) inn!</h1>'."\n";
		echo '<p><b>Villa:</b> S��an sem �� ert a� reyna a� opna er eing�ngu a�gengileg innskr��um notendum.</p>'."\n";
	}
}

?>
<form method="post" action="takelogin.php">
<p>Athugi�: �� �arft a� hafa vafrak�kur virkar til a� geta innskr�� �ig.</p>
<table border="0" cellpadding="5">
<tr><td class="rowhead">Notandanafn:</td><td align="left"><input type="text" size="40" name="username" /></td></tr>
<tr><td class="rowhead">Lykilor�:</td><td align="left"><input type="password" size="40" name="password" /></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="Innskr�!" class="btn" /></td></tr>
</table>
<?

if (isset($returnto))
	echo '<input type="hidden" name="returnto" value="'. htmlspecialchars($returnto).'" />'."\n";

?>
</form>
<p>Hefur�u ekki a�gang? <a href="signup.php">N�skr��u �ig</a> n�na!</p>
<?

stdfoot();

hit_end();

?>
