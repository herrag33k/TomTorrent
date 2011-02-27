<?
require_once("include/benc.php");
require_once("include/bittorrent.php");

hit_start();

ini_set("upload_max_filesize",$max_torrent_size);

function bark($msg) {
genbark($msg, "Sending mistókst!");
}

dbconn();

hit_count();

loggedinorreturn();

if(slots($CURUSER['id'],'free') < '1')
	bark("Þú hefur ekki nóg af lausum hólfum til að búa til torrent.");
if(find_unseeded($CURUSER['id']) === '1')
	bark('Þú mátt ekki senda inn torrent skrá á þessari stundu. Annað hvort er eitthvað af því sem þú hefur sent inn án deilanda eða þú ert eini deilandinn á torrenti sem þú hefur sent inn seinustu 24 klukkustundirnar.');

foreach(explode(":","descr:type:name") as $v) {
if (!isset($_POST[$v]))
bark("vantar gögn");
}

if (!isset($_FILES["file"]))
bark("vantar skrá");

$f = $_FILES["file"];
$fname = unesc($f["name"]);
if (empty($fname))
bark("Tómt skráarnafn!");

$nfofile = $_FILES['nfo'];
//if ($nfofile['name'] == '')
//bark("No NFO!");

//if ($nfofile['size'] == 0)
//bark("0-byte NFO");

if ($nfofile['size'] > (512*1024))
bark("NFO skrá of stór! Hámark 512 kílóbæti.");

$nfofilename = $nfofile['tmp_name'];
$old = $_POST["gamalt"];
if($old =='yes')
	$gamalt = 1;
else
	$gamalt = 2;

if($_POST['scene'] === 'y' || $_POST['scene'] === 'n')
	$scene = $_POST['scene'];
else
	$scene = 'n';

if($_POST['anonymous'] === '1')
	$anonymous = '1';
else
	$anonymous = '0';

//if (@!is_uploaded_file($nfofilename))
//bark("NFO upload failed");

$descr = unesc($_POST["descr"]);
if (!$descr)
bark("Þú verður að slá inn lýsingu!");

$catid = (0 + $_POST["type"]);
if (!is_valid_id($catid))
bark("Þú verður að velja flokk til að setja torrent skránna í!");

if (!validfilename($fname))
bark("Ógilt skráarnafn!");
if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches))
bark("Ógilt skráarnafn (ekki .torrent).");
$shortfname = $torrent = $matches[1];
if (!empty($_POST["name"]))
$torrent = unesc($_POST["name"]);

$tmpname = $f["tmp_name"];
if (!is_uploaded_file($tmpname))
bark("ík");
if (!filesize($tmpname))
bark("Tóm skrá!");

$dict = bdec_file($tmpname, $max_torrent_size);
if (!isset($dict))
bark("Hvað varstu eiginlega að senda? Þetta er ekki grunnkóðuð skrá!");

function dict_check($d, $s) {
if ($d["type"] != "dictionary")
bark("ekki orðabók - A101");
$a = explode(":", $s);
$dd = $d["value"];
$ret = array();
foreach ($a as $k) {
unset($t);
if (preg_match('/^(.*)\((.*)\)$/', $k, $m)) {
 $k = $m[1];
 $t = $m[2];
}
if (!isset($dd[$k]))
 bark("orðabók hefur ekki lykla");
if (isset($t)) {
 if ($dd[$k]["type"] != $t)
  bark("ógild færsla í orðabók");
 $ret[] = $dd[$k]["value"];
}
else
 $ret[] = $dd[$k];
}
return $ret;
}

function dict_get($d, $k, $t) {
if ($d["type"] != "dictionary")
bark("ekki orðabók - A102");
$dd = $d["value"];
if (!isset($dd[$k]))
return;
$v = $dd[$k];
if ($v["type"] != $t)
bark("ógild tegund orðabókarfærslu");
return $v["value"];
}

// This section creates the additonal dictionary entries
$dict["value"]["info"]["value"]["private"]["type"] = "integer";
$dict["value"]["info"]["value"]["private"]["value"] = 1;
$dict["value"]["info"]["value"]["source"]["type"] = "string";
$dict["value"]["info"]["value"]["source"]["value"] = "Istorrent";
$dict["value"]["info"]["value"]["source"]["strlen"] = strlen($dict["value"]["info"]["value"]["source"]["value"]);

// This bencodes and bdecodes again - necessary...
$fn = benc($dict);
$dict = bdec($fn);

list($ann, $info) = dict_check($dict, "announce(string):info");
list($dname, $plen, $pieces) = dict_check($info, "name(string):piece length(integer):pieces(string)");

if (!in_array($ann, $announce_urls, 1))
bark("Lokað er á torrent frá öðrum síðum, búið til ný torrent með tilkynningarslóðinni " . $announce_urls[0] . ". Tilkynningarslóðin sem 
stendur í torrent skránni sem þú sendir er " . $ann . ".");

if (strlen($pieces) % 20 != 0)
bark("invalid pieces");

$filelist = array();
$totallen = dict_get($info, "length", "integer");
if (isset($totallen)) {
$filelist[] = array($dname, $totallen);
$type = "single";
}
else {
$flist = dict_get($info, "files", "list");
if (!isset($flist))
bark("Hef hvorki lengd né skrár");
if (!count($flist))
bark("engar skrár");
$totallen = 0;
foreach ($flist as $fn) {
list($ll, $ff) = dict_check($fn, "length(integer):path(list)");
$totallen += $ll;
$ffa = array();
foreach ($ff as $ffe) {
 if ($ffe["type"] != "string")
  bark("skráarheitisvilla");
 $ffa[] = $ffe["value"];
}
if (!count($ffa))
 bark("skráarheitisvilla");
$ffe = implode("/", $ffa);
$filelist[] = array($ffe, $ll);
}
$type = "multi";
}

if($_POST['type'] === '9') {
	// Minnsta stærð á samtals stærð í DVD-R flokknum
	$dvdr_min = 2*1024*1024*1024;
	if($totallen <= $dvdr_min) {
		// Finna út ef einhver skrá endar á *.vob, *.nrg eða *.iso
		for($f_i=0;$f_i<count($filelist);$f_i++) {
			$ending = substr($filelist[$f_i][0], '-4', '4');
			if($ending == '.iso' || $ending == '.nrg' || $ending == '.vob' || $ending == '.bin')
				$dvdr_gott = '1';
		}
		if($dvdr_gott != '1')
			bark('Óleyfileg skráarnöfn í torrent skránni fyrir DVD-R flokkinn');
	}
}

$infohash = pack("H*", sha1($info["string"]));

// Replace punctuation characters with spaces

$torrent = str_replace("_", " ", $torrent);

$nfo = sqlesc(str_replace("\x0d\x0d\x0a", "\x0d\x0a", @file_get_contents($nfofilename)));
$ret = mysql_query("INSERT INTO torrents (gamalt, scene, anonymous, search_text, filename, owner, visible, 
info_hash, name, size, numfiles, type, descr, ori_descr, category, save_as, added, last_action, 
nfo) 
VALUES ($gamalt, \"$scene\", $anonymous, ". implode(",", array_map("sqlesc", array(searchfield("$shortfname 
$dname $torrent"), $fname, $CURUSER["id"], "no", $infohash, $torrent, $totallen, 
count($filelist), $type, $descr, $descr, 0 + $_POST["type"], $dname))) .", '" . 
get_date_time() . "', '" . get_date_time() . "', $nfo)");
if (!$ret) {
if (mysql_errno() == 1062)
bark("torrent hefur þegar verið innsent!");
bark("mysql gubbaði: ".mysql_error());
}
$id = mysql_insert_id();

@mysql_query("DELETE FROM files WHERE torrent = $id");
foreach ($filelist as $file) {
@mysql_query("INSERT INTO files (torrent, filename, size) VALUES ($id, ".sqlesc($file[0]).",".$file[1].")");
}

move_uploaded_file($tmpname, "$torrent_dir/$id.torrent");

write_log("Torrentið $id ($torrent) var sent inn af " . $CURUSER["username"]);

// Code to write the updated dictionary to the torrent file
$fp = fopen("$torrent_dir/$id.torrent", "w");
if ($fp)
{
@fwrite($fp, benc($dict), strlen(benc($dict)));
fclose($fp);
}

// Jump to torrent details...
header("Location: $DEFAULTBASEURL/details.php?id=$id&uploaded=1");

hit_end();

?>
