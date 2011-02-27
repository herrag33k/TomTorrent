<?

require_once("include/bittorrent.php");

hit_start();

function bark($msg) {
	genbark($msg, "Edit failed!");
}

if (!mkglobal("id:name:descr:type"))
	bark("missing form data");

$id = 0 + $id;
if (!$id)
	die();

dbconn();

hit_count();

loggedinorreturn();

$res = mysql_query("SELECT owner, filename, save_as FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
	die();

if ($CURUSER["id"] != $row["owner"] && get_user_class() < UC_MODERATOR)
	bark("Þú ert ekki eigandinn! Hvernig gerðist þetta?\n");

$updateset = array();

$fname = $row["filename"];
preg_match('/^(.+)\.torrent$/si', $fname, $matches);
$shortfname = $matches[1];
$dname = $row["save_as"];

$nfoaction = $_POST['nfoaction'];
if ($nfoaction == "update")
{
  $nfofile = $_FILES['nfo'];
  if (!$nfofile) die("No data " . var_dump($_FILES));
  if ($nfofile['size'] > 524288)
    bark("NFO skrá of stór! Hámark 512 kílóbæti.");
  $nfofilename = $nfofile['tmp_name'];
  if (@is_uploaded_file($nfofilename) && @filesize($nfofilename) > 0)
    $updateset[] = "nfo = " . sqlesc(str_replace("\x0d\x0d\x0a", "\x0d\x0a", file_get_contents($nfofilename)));
}
else
  if ($nfoaction == "remove")
    $updateset[] = "nfo = ''";

if($_POST['gamalt'] == 'yes')
	$gamalt = 1;
else
	$gamalt = 2;

$updateset[] = "anonymous = '" . ($_POST["anonymous"] ? "1" : "0") . "'";
$updateset[] = "scene = '" . ($_POST["scene"] ? "y" : "n") . "'";
$updateset[] = "gamalt = " . sqlesc($gamalt);
$updateset[] = "name = " . sqlesc($name);
$updateset[] = "search_text = " . sqlesc(searchfield("$shortfname $dname $torrent"));
$updateset[] = "descr = " . sqlesc($descr);
$updateset[] = "ori_descr = " . sqlesc($descr);
$updateset[] = "category = " . (0 + $type);
if ($CURUSER["class"] >= UC_MODERATOR) {
	if ($_POST["banned"]) {
		$updateset[] = "banned = 'yes'";
		$_POST["visible"] = 0;
	}
	else
		$updateset[] = "banned = 'no'";
	if ($_POST['nuked']) {
		if(!$_POST['nukedr'])
			bark("Verður að koma með ástæðu fyrir sprengingu");
		$updateset[] ="nuked = 'yes'";
		$updateset[] = "nukedr = '". $_POST['nukedr'] ."'";
	}
	else
		$updateset[] = "nuked = 'no'";
}
$updateset[] = "visible = '" . ($_POST["visible"] ? "yes" : "no") . "'";

mysql_query("UPDATE torrents SET " . join(",", $updateset) . " WHERE id = $id");

write_log("Torrentinu $id ($name) var breytt af $CURUSER[username]");
if (($fd1 = @fopen("rss.xml", "w")) && ($fd2 = fopen("rssdd.xml", "w")))
{
	$cats = "";
	$res = mysql_query("SELECT id, name FROM categories");
	while ($arr = mysql_fetch_assoc($res))
		$cats[$arr["id"]] = $arr["name"];
	$s = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>\n<rss version=\"0.91\">\n<channel>\n" .
		"<title>Istorrent</title>\n<description>50 nýjustu torrent</description>\n<link>$DEFAULTBASEURL/</link>\n";
	@fwrite($fd1, $s);
	@fwrite($fd2, $s);
	$r = mysql_query("SELECT id,name,descr,filename,category,seeders,leechers FROM torrents where seeders > 0 ORDER BY added DESC LIMIT 50") or sqlerr(__FILE__, __LINE__);
	while ($a = mysql_fetch_assoc($r))
	{
		$nafn = $a["name"];
		$cat = $cats[$a["category"]];
		$s = "<item>\n<title>" . htmlspecialchars("$nafn ($cat)") . "</title>\n" .
			"<description>" . htmlspecialchars($a["descr"]) . "</description>\n" .
			"<seeders>" . htmlspecialchars($a["seeders"]) . "</seeders>\n" .
			"<leechers>" . htmlspecialchars($a["leechers"]) . "</leechers>\n";
		@fwrite($fd1, $s);
		@fwrite($fd2, $s);
		@fwrite($fd1, "<link>$DEFAULTBASEURL/details.php?id=$a[id]&amp;hit=1</link>\n</item>\n");
		$filename = htmlspecialchars($a["filename"]);
		@fwrite($fd2, "<link>$DEFAULTBASEURL/download.php/$a[id]/$filename</link>\n</item>\n");
	}
	$s = "</channel>\n</rss>\n";
	@fwrite($fd1, $s);
	@fwrite($fd2, $s);
	@fclose($fd1);
	@fclose($fd2);
	}
$returl = "details.php?id=$id&edited=1";
if (isset($_POST["returnto"]))
	$returl .= "&returnto=" . urlencode($_POST["returnto"]);
header("Refresh: 0; url=$returl");

hit_end();

?>
