<?

require_once("include/bittorrent.php");

hit_start();

if (!mkglobal("username:password"))
	die();

dbconn();

hit_count();

function bark($text = "Rangt notandanafn og/eða lykilorð, til að endursetja lykilorð getur þú smellt <a href=recover.php>hér</a>.")
{
  stderr("Innskráning mistókst!", $text);
}

$res = mysql_query('SELECT id, passhash, status, secret, uploaded, downloaded, enabled, deleted, lasttorrent FROM users WHERE username = '.sqlesc($username)); 
$row = mysql_fetch_array($res);

if (!$row)
	bark();

if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
	bark();

@$ratio = ($row['uploaded']/$row['downloaded']);

if ($row['deleted'] == '1')
	stderr('Innskráning mistókst!','Aðgangnum hefur verið eytt vegna broti á reglum, óvirkni, af eigin ósk eða að beiðni bjóðanda.');
elseif ($row['enabled'] == 'no' && $ratio <= '0.2')
	stderr('Innskráning mistókst!', 'Aðgangurinn þinn hefur verið gerður óvirkur vegna lélegra hlutfalla. Vinsamlegast hafðu samband við stjórnendur í gegnum tölvupóst á torrent@torrent.is');
elseif ($row['enabled'] == 'no')
	stderr('Innskráning mistókst!','Aðgangnum þínum hefur verið gerður óvirkur vegna brota á reglum. Nánari upplýsingar á torrent@torrent.is');
elseif ($row['status'] !== 'confirmed')
	stderr('Innskráning mistókst!','Þú hefur ekki enn þá staðfest aðganginn þinn. Staðfestingarslóðin ætti að hafa verið send í gegnum tölvupóst.');


	logincookie($row["id"], $row["passhash"]);
	$_SESSION['lasttorrent'] = $row['lasttorrent'];

	if (!empty($_POST["returnto"]))
		header("Location: $BASEURL$_POST[returnto]");
	else
		header("Location: $BASEURL/my.php");


hit_end();

?>
