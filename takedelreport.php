<?
require_once("include/bittorrent.php");
function bark($msg) {
 stdhead();
   stdmsg("Failed", $msg);
 stdfoot();
 exit;
}
dbconn();
loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
die();

$res = mysql_query ("SELECT id FROM reports WHERE dealtwith=0 AND id IN (" . implode(", ", $_POST[delreport]) . ")");

while ($arr = mysql_fetch_assoc($res))
mysql_query ("UPDATE reports SET dealtwith=1, dealtby = $CURUSER[id] WHERE id = $arr[id]") or sqlerr();

header("Refresh: 0; url=reports.php");
?>
