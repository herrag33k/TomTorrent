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
	   bark("Ekki skilja eftir neina reiti t�ma.");

	$do="DELETE FROM requests WHERE id IN (" . implode(", ", $_POST[delreq]) . ")";
	$do2="DELETE FROM addedrequests WHERE requestid IN (" . implode(", ", $_POST[delreq]) . ")";
	$res2=mysql_query($do2);
	$res=mysql_query($do);
} else {
	bark("�� ert ekki starfsma�ur, burt me� y�ur");
}
header("Refresh: 0; url=viewrequests.php");
?>
