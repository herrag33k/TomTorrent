<?
require_once("include/bittorrent.php");
dbconn();
hit_start();
loggedinorreturn();
/*
parked();
*/

$userid = $CURUSER["id"];
$torrentid = $_POST["torrentid"];
$thx_sql = "SELECT COUNT(*) FROM thanks WHERE torrentid=$torrentid AND userid=$userid";
$thxbefore = mysql_result(mysql_query($thx_sql),0);
if (isset($userid) && isset($torrentid) && $thxbefore < '1')
{
$res = mysql_query("INSERT INTO thanks (torrentid, userid) VALUES ($torrentid, $userid)");
header("Location: $BASEURL/details.php?id=$torrentid&thanks=1");
}
else {
header("Location: $BASEURL/browse.php");
}
hit_end()
?>
