<?

require_once("include/bittorrent.php");

dbconn();

hit_start();

hit_count();

stdhead("Kj�sa");

$requestid = $_GET["id"];
$userid = $CURUSER["id"];
$res = mysql_query("SELECT * FROM addedrequests WHERE requestid=$requestid and userid = $userid") or sqlerr();
$arr = mysql_fetch_assoc($res);
$voted = $arr;

if ($voted) {
?>
<h1>�� hefur �egar kosi�</h1>
<p>�� hefur �egar kosi� um �essa bei�ni, eing�ngu 1 atkv��i er leyft fyrir hverja bei�ni</p><p>Til baka � <a href=viewrequests.php><b>bei�nir</b></a></p>
<?
}
else {

mysql_query("UPDATE requests SET hits = hits + 1 WHERE id=$requestid") or sqlerr();
@mysql_query("INSERT INTO addedrequests VALUES(0, $requestid, $userid)") or sqlerr();

print("<h1>Atkv��i m�tteki�</h1>");
print("<p>T�kst a� grei�a atkv��i fyrir $requestid</p><p>Til baka � <a href=viewrequests.php><b>bei�nir</b></a></p>");

}

hit_end();

?>
