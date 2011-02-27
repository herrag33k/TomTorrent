<?
require_once("include/benc.php");
require_once("include/bittorrent.php");

hit_start();

ini_set("upload_max_filesize",$max_torrent_size);

function bark($msg) {
genbark($msg, "Sending mist�kst!");
}

dbconn();

hit_count();

loggedinorreturn();

if(slots($CURUSER['id'],'free') < '1')
	bark("�� hefur ekki n�g af lausum h�lfum til a� b�a til torrent.");
if(find_unseeded($CURUSER['id']) === '1')
	bark('�� m�tt ekki senda inn torrent skr� � �essari stundu. Anna� hvort er eitthva� af �v� sem �� hefur sent inn �n deilanda e�a �� ert eini deilandinn � torrenti sem �� hefur sent inn seinustu 24 klukkustundirnar.');

foreach(explode(":","descr:type:name") as $v) {
if (!isset($_POST[$v]))
bark("vantar g�gn");
}

if (!isset($_FILES["file"]))
bark("vantar skr�");

$f = $_FILES["file"];
$fname = unesc($f["name"]);
if (empty($fname))
bark("T�mt skr�arnafn!");

$nfofile = $_FILES['nfo'];
//if ($nfofile['name'] == '')
//bark("No NFO!");

//if ($nfofile['size'] == 0)
//bark("0-byte NFO");

if ($nfofile['size'] > (512*1024))
bark("NFO skr� of st�r! H�mark 512 k�l�b�ti.");

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
bark("�� ver�ur a� sl� inn l�singu!");

$catid = (0 + $_POST["type"]);
if (!is_valid_id($catid))
bark("�� ver�ur a� velja flokk til a� setja torrent skr�nna �!");

if (!validfilename($fname))
bark("�gilt skr�arnafn!");
if (!preg_match('/^(.+)\.torrent$/si', $fname, $matches))
bark("�gilt skr�arnafn (ekki .torrent).");
$shortfname = $torrent = $matches[1];
if (!empty($_POST["name"]))
$torrent = unesc($_POST["name"]);

$tmpname = $f["tmp_name"];
if (!is_uploaded_file($tmpname))
bark("�k");
if (!filesize($tmpname))
bark("T�m skr�!");

$dict = bdec_file($tmpname, $max_torrent_size);
if (!isset($dict))
bark("Hva� varstu eiginlega a� senda? �etta er ekki grunnk��u� skr�!");

function dict_check($d, $s) {
if ($d["type"] != "dictionary")
bark("ekki or�ab�k - A101");
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
 bark("or�ab�k hefur ekki lykla");
if (isset($t)) {
 if ($dd[$k]["type"] != $t)
  bark("�gild f�rsla � or�ab�k");
 $ret[] = $dd[$k]["value"];
}
else
 $ret[] = $dd[$k];
}
return $ret;
}

function dict_get($d, $k, $t) {
if ($d["type"] != "dictionary")
bark("ekki or�ab�k - A102");
$dd = $d["value"];
if (!isset($dd[$k]))
return;
$v = $dd[$k];
if ($v["type"] != $t)
bark("�gild tegund or�ab�karf�rslu");
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
bark("Loka� er � torrent fr� ��rum s��um, b�i� til n� torrent me� tilkynningarsl��inni " . $announce_urls[0] . ". Tilkynningarsl��in sem 
stendur � torrent skr�nni sem �� sendir er " . $ann . ".");

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
bark("Hef hvorki lengd n� skr�r");
if (!count($flist))
bark("engar skr�r");
$totallen = 0;
foreach ($flist as $fn) {
list($ll, $ff) = dict_check($fn, "length(integer):path(list)");
$totallen += $ll;
$ffa = array();
foreach ($ff as $ffe) {
 if ($ffe["type"] != "string")
  bark("skr�arheitisvilla");
 $ffa[] = $ffe["value"];
}
if (!count($ffa))
 bark("skr�arheitisvilla");
$ffe = implode("/", $ffa);
$filelist[] = array($ffe, $ll);
}
$type = "multi";
}

if($_POST['type'] === '9') {
	// Minnsta st�r� � samtals st�r� � DVD-R flokknum
	$dvdr_min = 2*1024*1024*1024;
	if($totallen <= $dvdr_min) {
		// Finna �t ef einhver skr� endar � *.vob, *.nrg e�a *.iso
		for($f_i=0;$f_i<count($filelist);$f_i++) {
			$ending = substr($filelist[$f_i][0], '-4', '4');
			if($ending == '.iso' || $ending == '.nrg' || $ending == '.vob' || $ending == '.bin')
				$dvdr_gott = '1';
		}
		if($dvdr_gott != '1')
			bark('�leyfileg skr�arn�fn � torrent skr�nni fyrir DVD-R flokkinn');
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
bark("torrent hefur �egar veri� innsent!");
bark("mysql gubba�i: ".mysql_error());
}
$id = mysql_insert_id();

@mysql_query("DELETE FROM files WHERE torrent = $id");
foreach ($filelist as $file) {
@mysql_query("INSERT INTO files (torrent, filename, size) VALUES ($id, ".sqlesc($file[0]).",".$file[1].")");
}

move_uploaded_file($tmpname, "$torrent_dir/$id.torrent");

write_log("Torrenti� $id ($torrent) var sent inn af " . $CURUSER["username"]);

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
