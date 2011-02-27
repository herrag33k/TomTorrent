<?

require_once("include/bittorrent.php");

hit_start();

dbconn(false);

hit_count();

loggedinorreturn();

stdhead("Myndaalbúm notenda Istorrent");

$where = "WHERE banned = 'no'";
$res = mysql_query("SELECT COUNT(*) FROM innmyndir $where");
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
?>
<h1>Engar myndir!</h1>
<p>Şağ hafa engar myndir veriğ sendar inn.</p>
<?
}
else {
	list($pagertop, $pagerbottom, $limit) = pager(20, $count, "myndaalbum.php?");

	$query = mysql_query("SELECT * from innmyndir $where ORDER BY id DESC $limit");

	print($pagertop);
	echo "<table border=1 cellspacing=0 cellpadding=5><tr>
		<td class=colhead>#</td>
		<td class=colhead>Mynd</td>
		<td class=colhead>Send inn af</tr>";
	while($res = mysql_fetch_array($query)) {
	$rid = $res['id'];
	$rnafn = $res['nafn'];
	$rusername = $res['username'];
	$radult = $res['adult'];
	if($radult == 'yes')
	$rnafn = "<b><font color=red>18+ </font></b>". $rnafn;
	echo "<tr>
	<td><b>$rid</b></td>
	<td><a style='text-decoration: none' href=$BASEURL/myndir.php?id=$rid>$rnafn</a></td>
	<td>$rusername</td></tr>";
	}
	echo "</table>";
	echo "<p><a href=/mynda-upload.php>Senda inn nıja</a><br>
	<a href=/mymyndir.php>Mínar myndir</a>";
	print($pagerbottom);
}

stdfoot();

hit_end();

?>
