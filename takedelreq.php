<?
require_once("include/bittorrent.php");
function bark($msg) {
 stdhead();
   stdmsg("Villa", $msg);
 stdfoot();
 exit;
}
dbconn();
loggedinorreturn();
if (get_user_class() >= UC_MODERATOR) {

	if (empty($_POST["delreq"]))
	   bark("Ekki skilja eftir neina reiti tóma.");

	$do="DELETE FROM requests WHERE id IN (" . implode(", ", $_POST[delreq]) . ")";
	$do2="DELETE FROM addedrequests WHERE requestid IN (" . implode(", ", $_POST[delreq]) . ")";
	$res2=mysql_query($do2);
	$res=mysql_query($do);
} else {
	bark("Þú ert ekki starfsmaður, burt með yður");
}
header("Refresh: 0; url=viewrequests.php");
?>
