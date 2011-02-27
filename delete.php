<?

require_once("include/bittorrent.php");

hit_start();

function bark($msg) {
  stdhead();
  stdmsg("Eyðing mistókst!", $msg);
  stdfoot();
  exit;
}

if (!mkglobal("id"))
	bark("hef ekki gögn úr formum");

$id = 0 + $id;
if (!$id)
	die();

dbconn();

hit_count();

loggedinorreturn();

$res = mysql_query("SELECT name,owner,seeders FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
	die();

if ($CURUSER["id"] != $row["owner"] && get_user_class() < UC_MODERATOR)
	bark("Þú ert ekki eigandinn! Hvernig gerðist þetta?\n");

$reason = trim($_POST["reason"]);
if (!$reason)
  bark("Gjörðu svo vel að skrifa ástæðu þessarar eyðingar.");

deletetorrent($id);

write_log("Torrentinu $id ($row[name]) var eytt af $CURUSER[username] ($reason)\n");

$msg = sqlesc("Torrentinu ".$row[name]." var eytt af ".$CURUSER[username].". Ástæða: ".$reason);
$added = sqlesc(get_date_time());
mysql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, $row[owner], $msg, $added)") or sqlerr(__FILE__, __LINE__);

stdhead("Torrenti eytt!");

if (isset($_POST["returnto"]))
	$ret = "<a href=\"" . htmlspecialchars($_POST["returnto"]) . "\">Fara til baka þaðan sem þú komst</a>";
else
	$ret = "<a href=\"./\">Aftur á yfirlit</a>";

?>
<h2>Torrent eytt!</h2>
<p><?= $ret ?></p>
<?

stdfoot();

hit_end();

?>
