<?
if($_GET['ip'])
	$ip = $_GET['ip'];
else
	$ip = $_SERVER['REMOTE_ADDR'];

	$cidr = file("/www/torrent.is/www/bann-listi.txt");
	if(matchCIDR($ip, $cidr))
		$allow = 0;

echo $ip.'<br />';
echo $allow;
?>
