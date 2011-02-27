<?

require_once("include/bittorrent.php");

hit_start();

dbconn();

$main = trim($_POST["main"]);
if (!$main)
{
  stdhead();
  stdmsg("Úps...", "Þú verður að slá inn eitthvað!");
  stdfoot();
  exit;
}

hit_count();

if (!isset($CURUSER))
	die();

if (!mkglobal("main:id"))
	die();

$id = 0 + $id;
if (!$id)
	die();

$res = mysql_query("SELECT 1 FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
	die();
$dags = get_date_time();
$sqlinsert = 'INSERT INTO comments (user, torrent, added, text, ori_text) VALUES (\''.$CURUSER[id].'\',\''.$id.'\',\''.$dags.'\','.sqlesc($main).','.sqlesc($main).')';
mysql_query($sqlinsert);


$newid = mysql_insert_id();

mysql_query("UPDATE torrents SET comments = comments + 1 WHERE id = $id");

header("Refresh: 0; url=details.php?id=$id&viewcomm=$newid#comm$newid");

hit_end();

?>
