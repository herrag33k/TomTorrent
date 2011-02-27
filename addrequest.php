<?

require_once("include/bittorrent.php");

dbconn();

hit_start();

hit_count();

stdhead("Kjósa");

$requestid = $_GET["id"];
$userid = $CURUSER["id"];
$res = mysql_query("SELECT * FROM addedrequests WHERE requestid=$requestid and userid = $userid") or sqlerr();
$arr = mysql_fetch_assoc($res);
$voted = $arr;

if ($voted) {
?>
<h1>Þú hefur þegar kosið</h1>
<p>Þú hefur þegar kosið um þessa beiðni, eingöngu 1 atkvæði er leyft fyrir hverja beiðni</p><p>Til baka á <a href=viewrequests.php><b>beiðnir</b></a></p>
<?
}
else {

mysql_query("UPDATE requests SET hits = hits + 1 WHERE id=$requestid") or sqlerr();
@mysql_query("INSERT INTO addedrequests VALUES(0, $requestid, $userid)") or sqlerr();

print("<h1>Atkvæði móttekið</h1>");
print("<p>Tókst að greiða atkvæði fyrir $requestid</p><p>Til baka á <a href=viewrequests.php><b>beiðnir</b></a></p>");

}

hit_end();

?>
