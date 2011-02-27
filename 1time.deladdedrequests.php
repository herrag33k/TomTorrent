<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Eyða atkvæðum með eftirspurnum þar sem höfundur kýs sjálfur');
$sql = 'SELECT addedrequests.id AS addedid,requests.id AS 
minid,addedrequests.userid AS addeduserid,requests.userid AS minuserid,addedrequests.requestid AS addedrid FROM 
addedrequests,requests WHERE requests.userid = addedrequests.userid AND (requests.id = addedrequests.requestid)';
$res = mysql_query($sql);
//	echo '<table>';
//	echo '<tr><td>addedrequests.id</td><td>requests.id</td><td>addedrequests.userid</td><td>requests.userid</td><td>addedrequests.requestid</td></tr>';

while($t = mysql_fetch_assoc($res)) {
//	echo '<tr><td>'.$t['addedid'].'</td><td>'.$t['minid'].'</td><td>'.$t['addeduserid'].'</td><td>'.$t['minuserid'].'</td><td>'.$t['addedrid'].'</td></tr>';
	$sql = 'DELETE FROM addedrequests WHERE id = '.$t['addedid'];
//	echo $sql;
	mysql_query($sql);
}
	echo '</table>';

end_frame();
end_main_frame();
stdfoot();
?>
