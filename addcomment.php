<?

require_once("include/bittorrent.php");

hit_start();

if (!mkglobal("id"))
	die();

$id = 0 + $id;
if (!$id)
	die();

dbconn(false);

hit_count();

loggedinorreturn();

$res = mysql_query("SELECT name FROM torrents WHERE id = $id");
$torrow = mysql_fetch_array($res);
if (!$torrow)
	die();

stdhead("B�ta athugasemd vi� \"" . $torrow["name"] . "\"");

?>
<h1>B�ta athugasemd vi� "<?= htmlspecialchars($torrow["name"]) ?>"</h1>
Stj�rnendur vilja minna notendur � a� vera ekki of �kurteisir � sv�rum, annars er h�tta � vi�v�run.
<p>
<form method="post" action="takecomment.php">
<input type="hidden" name="id" value="<?= $id ?>" />
<textarea name="main" rows="10" cols="60"></textarea>
</p>
<p><input type="submit" class=btn value="B�ta vi�!" /></p>
</form>
<?

$res = mysql_query("SELECT comments.id, text, comments.added, username, users.id as user, users.avatar FROM comments LEFT JOIN users ON comments.user = users.id WHERE torrent = $id ORDER BY comments.id DESC LIMIT 5");

$allrows = array();
while ($row = mysql_fetch_array($res))
	$allrows[] = $row;

if (count($allrows)) {
	print("<h2>N�justu athugasemdirnar, � �fugri r��</h2>\n");
	commenttable($allrows);
}

stdfoot();

hit_end();

?>
