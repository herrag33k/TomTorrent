<?

require_once("include/bittorrent.php");

dbconn();

$sql = 'SELECT id,username,kennitala FROM users WHERE birta_afm = 1 AND enabled = \'yes\' AND deleted=0 AND kennitala LIKE \''.date('dm').'%\'';
$res = mysql_query($sql);

$output = '<a href="/faq.php#89">Afmælisbörn dagsins:</a> ';
$nar = '1'.date('y');
if(mysql_num_rows($res) === 0)
	$output .= 'Engin skráð afmæli í dag...';
else {
	while($row = mysql_fetch_assoc($res)) {
		$output .= '<a href="userdetails.php?id='.$row['id'].'">'.$row['username'].' ('.($nar-substr($row['kennitala'], 4, 2)).')</a>&nbsp;&nbsp;';
	}
}
file_put_contents('cache-birthday.txt', $output);

?>
