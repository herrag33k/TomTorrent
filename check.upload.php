<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Gagnamagn notanda');
if (get_user_class() >= UC_SYSOP) {
	if(isset($_GET['orderby']))
		$orderby = $_GET['orderby'];
	else
		$orderby = '';
	if($orderby != 'upload' && $orderby != 'date')
		$orderby = 'upload';
	if(isset($_GET['order']))
		$order = $_GET['order'];
	else
		$order = '';
	if($order != 'DESC' && $order != 'ASC')
		$order = 'DESC';
	if(!$_GET['id'])
		$userid = $CURUSER['id'];
	else
		$userid = $_GET['id'];
	if(empty($_GET['limit']))
		$limit = '100';
	else
		$limit = $_GET['limit'];
	$res = mysql_query("SELECT date,upload,download FROM uploads WHERE userid=$userid ORDER BY $orderby $order LIMIT $limit") or sqlerr();
	if(mysql_num_rows($res) < 1) {
		echo "Engin skr�� umfer� � �ennan notanda.";
	} else {
		echo '<a href="/check.upload.php?orderby=date&amp;order=ASC&amp;id='.$userid.'">Fyrstu f�rslur</a> - ';
		echo '<a href="/check.upload.php?orderby=date&amp;order=DESC&amp;id='.$userid.'">N�justu f�rslur</a> - ';
		echo '<a href="/check.upload.php?orderby=upload&amp;order=ASC&amp;id='.$userid.'">Minnsta deilimagn</a> - ';
		echo '<a href="/check.upload.php?orderby=upload&amp;order=DESC&amp;id='.$userid.'">Mesta deilimagn</a><br /><br />';
		echo '<table><tr><td>T�masetning</td><td>Deiling</td><td>Deiling � b�tum</td><td>Ni�urhal</td></tr>';
		$lastupload = '';
		while ($a = mysql_fetch_assoc($res))
		{
			echo '<tr>
			<td>'.$a['date'].'</td>
			<td>'.mksize($a['upload']).'</td>';
			if($a['upload'] === $lastupload)
				echo '<td style="background-color:red">'.$a['upload'].'</td>';
			else
				echo '<td>'.$a['upload'].'</td>';
			echo '<td>'.mksize($a['download']).'</td>
			</tr>';
			$lastupload = $a['upload'];
		}
		echo '</table>';
	}
} else {
	echo '�essi hluti s��unnar er eing�ngu �tla�ur kerfisstj�ra';
}
end_frame();
end_main_frame();
stdfoot();
?>
