<?
require("include/bittorrent.php");
dbconn();
	$addtime = time()-(24*60*60);
	$date = date('dm', $addtime);
	$sql = 'SELECT users.id, users.modcomment, SUM(uploads.download) AS download FROM users,uploads WHERE users.id = uploads.userid AND users.kennitala LIKE \''.$date.'%\' AND uploads.date LIKE \''.date('Ymd',$addtime).'%\' GROUP BY users.id';
//	echo $sql;
	$res = mysql_query($sql);
	$date = date('Ymd', $addtime);
	while($row = mysql_fetch_assoc($res)) {
//		echo $row['id'].' - '.$row['download'].'<br />';
		$modcomment = gmdate('Y-m-d').' - Afmælisgjöf'."\n".'Fékk '.$row['download'].' frádrátt af niðurhali.'."\n".$row['modcomment'];
//		echo nl2br($modcomment).'<br />';
		$sql = 'UPDATE users SET modcomment = \''.addslashes($modcomment).'\', downloaded=downloaded-'.$row['download'].' WHERE id='.$row['id'];
//		echo $sql;
		mysql_query($sql);
	}
?>
