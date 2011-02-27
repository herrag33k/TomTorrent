<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Svindlaranemi');
if (get_user_class() >= UC_SYSOP) {
	// Mesta hlutfall
	$sql = 'SELECT users.username AS username, uploads.userid, uploads.upload AS upl, uploads.download AS downl FROM uploads,users WHERE 
uploads.userid = users.id 
AND users.enabled != \'no\' 
AND users.deleted != \'1\' 
AND uploads.upload > '.(1*1024*1024*1024).' 
ORDER BY uploads.upload
DESC';
//	echo nl2br($sql);
	$res = mysql_query($sql) or sqlerr();
	begin_frame('Mesta deiling');
	echo '<table><tr><td>Notandanafn</td><td>Deilt</td><td>Deilt í bætum</td><td>Sótt</td></tr>';
	$i = '0';
	while($row = mysql_fetch_array($res)) {
		$i++;
		echo '<tr>
		<td>'.$i.'. <a href="/userdetails.php?id='.$row['userid'].'">'.$row['username'].'</a></td>
		<td>'.mksize($row['upl']).'</td>
		<td>'.$row['upl'].'</td>
		<td>'.mksize($row['downl']).'</td>
		</tr>';
	}
	echo '</table>';
	end_frame();
} else
	echo 'Þessi hluti vefsins er eingöngu fyrir stjórnendur!';
end_frame();
end_main_frame();
stdfoot();
?>
