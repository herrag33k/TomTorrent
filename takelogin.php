<?

require_once("include/bittorrent.php");

hit_start();

if (!mkglobal("username:password"))
	die();

dbconn();

hit_count();

function bark($text = "Rangt notandanafn og/e�a lykilor�, til a� endursetja lykilor� getur �� smellt <a href=recover.php>h�r</a>.")
{
  stderr("Innskr�ning mist�kst!", $text);
}

$res = mysql_query('SELECT id, passhash, status, secret, uploaded, downloaded, enabled, deleted, lasttorrent FROM users WHERE username = '.sqlesc($username)); 
$row = mysql_fetch_array($res);

if (!$row)
	bark();

if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
	bark();

@$ratio = ($row['uploaded']/$row['downloaded']);

if ($row['deleted'] == '1')
	stderr('Innskr�ning mist�kst!','A�gangnum hefur veri� eytt vegna broti � reglum, �virkni, af eigin �sk e�a a� bei�ni bj��anda.');
elseif ($row['enabled'] == 'no' && $ratio <= '0.2')
	stderr('Innskr�ning mist�kst!', 'A�gangurinn �inn hefur veri� ger�ur �virkur vegna l�legra hlutfalla. Vinsamlegast haf�u samband vi� stj�rnendur � gegnum t�lvup�st � torrent@torrent.is');
elseif ($row['enabled'] == 'no')
	stderr('Innskr�ning mist�kst!','A�gangnum ��num hefur veri� ger�ur �virkur vegna brota � reglum. N�nari uppl�singar � torrent@torrent.is');
elseif ($row['status'] !== 'confirmed')
	stderr('Innskr�ning mist�kst!','�� hefur ekki enn �� sta�fest a�ganginn �inn. Sta�festingarsl��in �tti a� hafa veri� send � gegnum t�lvup�st.');


	logincookie($row["id"], $row["passhash"]);
	$_SESSION['lasttorrent'] = $row['lasttorrent'];

	if (!empty($_POST["returnto"]))
		header("Location: $BASEURL$_POST[returnto]");
	else
		header("Location: $BASEURL/my.php");


hit_end();

?>
