<?

require_once("include/bittorrent.php");

hit_start();

dbconn(false);

hit_count();

loggedinorreturn();

stdhead("Myndir ". $CURUSER["username"]);

$where = "WHERE userid = " . $CURUSER["id"] . "";
$res = mysql_query("SELECT COUNT(*) FROM innmyndir $where");
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
?>
<h1>Engar myndir!</h1>
<p>�� hefur ekki sent inn neinar myndir �annig a� h�r er ekkert.</p>
<?
}
else {
	list($pagertop, $pagerbottom, $limit) = pager(20, $count, "mymyndir.php?");

	$query = mysql_query("SELECT * from innmyndir $where ORDER BY id DESC $limit");

	print($pagertop);
	echo "<table border=1 cellspacing=0 cellpadding=5><tr>
		<td class=colhead>#</td>
		<td class=colhead>Nafn</td>
		<td class=colhead>URL</td></tr>";
	while($res = mysql_fetch_array($query)) {
	$rid = $res['id'];
	$rnafn = $res['nafn'];
	echo "<tr>
	<td>$rid</td>
	<td>$rnafn</td>
	<td><a href=$BASEURL/myndir.php?id=$rid>$BASEURL/myndir.php?id=$rid</a></td></tr>";
	}
	echo "</table>";
	echo '<p><a href="/mynda-upload.php">Senda inn n�ja</a><br>
	<a href="/myndaalbum.php">Mynda alb�m</a>';
	print($pagerbottom);
}

stdfoot();

hit_end();

?>
