<?

require_once("include/bittorrent.php");

$id = $_GET['id'];
$md5 = $_GET['secret'];

if (!$id)
	die("�a� vantar id");

dbconn();

$res = mysql_query("SELECT passhash, editsecret, status FROM users WHERE id = $id");
$row = mysql_fetch_array($res);

if (!$row)
	die("Engin r��");

if ($row["status"] != "pending") {
	header("Refresh: 0; url=/ok.php?type=confirmed");
	exit();
}

$sec = hash_pad($row["editsecret"]);
if ($md5 != md5($sec))
	die('Rangt Secret - �essi villa er undir ranns�kn og � ekki a� gerast. Vinsamlegast �framsendu sta�festingarp�stinn me� k��anum til torrent@torrent.is');

mysql_query("UPDATE users SET status='confirmed', editsecret='' WHERE id=$id AND status='pending'");

if (!mysql_affected_rows())
	die("�etta haf�i engin �hrif");

logincookie($id, $row["passhash"]);

header("Refresh: 0; url=/ok.php?type=confirm");

?>
