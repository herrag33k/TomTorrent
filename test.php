<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Notendur sem virða ekki reglur um hlutföll');
$addtime = time()-(1*24*60*60);
$time = date('Y-m-d H:i:s', $addtime);
$sql = 'SELECT torrents.id AS tid,torrents.name,torrents.added,users.id AS uid,users.24rule AS rule24,(SELECT COUNT(*) FROM peers WHERE torrent=torrents.id AND seeder=\'yes\') AS seeds FROM torrents,users WHERE (users.id = torrents.owner) ORDER BY id DESC';
$res = mysql_query($sql);
$t1 = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400)));
while($row = mysql_fetch_array($res)) {
	$t2 = str_replace(array(' ',':','-'),'',$row['added']);
	if($row['seeds'] === '0' && $t1>$t2)
		echo $row['uid'].'<br />';
	elseif($row['seeds'] < '1' && $t2>$t1)
		echo $row['uid'].'<br />';
	elseif($row['seeds'] < '2' && $t2>$t1 && $row['rule24'] !== '1')
		echo $row['uid'].'<br />';
} end_frame();
end_main_frame();
stdfoot();
?>
