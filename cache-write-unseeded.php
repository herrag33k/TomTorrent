<?
require_once("include/bittorrent.php");
dbconn();

$sql = 'SELECT torrents.id AS tid,torrents.added,users.id AS uid,(SELECT COUNT(*) FROM peers WHERE torrent=torrents.id AND seeder=\'yes\') AS seeds FROM torrents,users WHERE (users.id = torrents.owner) ORDER BY users.id ASC'; $res = mysql_query($sql);
$t1 = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400)));
while($row = mysql_fetch_array($res)) {
	$notseeding='0';
	$t2 = str_replace(array(' ',':','-'),'',$row['added']);
	if($row['seeds'] === '0' && $t1>$t2)
		$notseeding='1';
	if($notseeding === '1')
		$user[] = $row['uid'];
}
$user = array_values(array_unique($user));

$dir = '/www/torrent.is/www/cache-unseeded/';

if($handle = opendir($dir)) {
	while(false !== ($file = readdir($handle))) {
		if($file != '.' && $file != '..')
			unlink($dir.$file);
	}
}

for($i = '0';$i < count($user);$i++) {
	$file = $dir.$user[$i];
	file_put_contents($file, '0');
}
?>
