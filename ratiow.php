<?
require_once("include/bittorrent.php");
dbconn();
$minratio = 0.3;
$maxdt = sqlesc(get_date_time(gmtime() - 86400*30));
$limit = 5*1024*1024*1024;
$res = mysql_query("SELECT * FROM users WHERE uploaded / downloaded <= $minratio AND downloaded >= $limit AND added < $maxdt AND class = 0 AND enabled = 'yes'") or sqlerr();
$today = sqlesc(get_date_time(gmtime()));
while ($a = mysql_fetch_assoc($res)) {
	$ratio = number_format($a['uploaded'] / $a['downloaded'], 2);
	$userid = $a['id'];
	$username = $a['username'];
	mysql_query("INSERT into ratiow SET userid = $userid, username = '$username', ratio = $ratio, date = $today") or die(mysql_error());
}
?>
