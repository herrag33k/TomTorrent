<?

require_once("include/bittorrent.php");

hit_start();

if (!preg_match(':^/(\d{1,10})/([\w]{32})/(.+)$:', $_SERVER["PATH_INFO"], $matches))
	echo 'Slóð ekki rétt';
//	httperr();

$id = 0 + $matches[1];
$md5 = $matches[2];
$email = urldecode($matches[3]);

if (!$id)
	echo 'Ekkert ID í slóð';
//	httperr();

dbconn();

hit_count();

$res = mysql_query("SELECT editsecret FROM users WHERE id = $id");
$row = mysql_fetch_array($res);

if (!$row)
	echo 'Lykill fannst ekki í gagnagrunni';
//	httperr();

$sec = hash_pad($row["editsecret"]);
if (preg_match('/^ *$/s', $sec))
	httperr();
if ($md5 != md5($sec . $email . $sec))
	echo 'Villa í öryggiskóða';
else
	mysql_query("UPDATE users SET editsecret='', email=" . sqlesc($email) . " WHERE id=$id AND editsecret=" . sqlesc($row["editsecret"]));

if (!mysql_affected_rows())
	echo 'Tókst ekki að framkvæma breytingu á gagnagrunni';
//	httperr();

@header("Refresh: 0; url=/my.php?emailch=1");

hit_end();

?>
