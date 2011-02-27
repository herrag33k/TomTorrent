<?

include('include/bittorrent.php');

dbconn();

function slot_reikn($used,$rule24,$warned,$donor,$class) {
	if($rule24 === '1')
		return '1';
	if($class == UC_BEGINNER)
		$limit = '4';
	if($class == UC_USER)
		$limit = '6';
	if($class == UC_GOOD_USER)
		$limit = '8';
	if($class >= UC_POWER_USER || $donor === '1')
		$limit = '12';
	if($warned === 'yes')
		$limit = '2';
	return ($limit-$used);
}

$dir = '/www/torrent.is/www/cache-slots/';

$addtime = time()-172800;
$time = date('Y-m-d H:i:s', $addtime);
$sql = 'SELECT users.id,COUNT(DISTINCT(peers.torrent)) AS used,users.class,users.warned,users.24rule AS rule24,donor FROM peers,torrents,users WHERE torrents.added>='.sqlesc($time).' AND (peers.torrent = torrents.id) AND (peers.userid = users.id) AND users.deleted=0 AND users.enabled=\'yes\' GROUP BY peers.userid ORDER BY peers.userid';

$res = mysql_query($sql);

if($handle = opendir($dir)) {
	while(false !== ($file = readdir($handle))) {
		if($file != '.' && $file != '..')
			unlink($dir.$file);
	}
}

while($row = mysql_fetch_assoc($res)) {
	$slots = slot_reikn($row['used'],$row['rule24'],$row['warned'],$row['donor'],$row['class']);
	if($slots <= '0') {
		$file = $dir.$row['id'];
		file_put_contents($file, '0');
	}
}

?>
