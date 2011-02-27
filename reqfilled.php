<?php
require_once("include/bittorrent.php");
hit_start();
dbconn();
loggedinorreturn();

stdhead("Uppfylla beiðni");

begin_main_frame();

$filledurl = $_GET["filledurl"];
$requestid = $_GET["requestid"];

if($filledurl == '' || $filledurl == 'http://torrent.is/details.php?id=1') {
	stdmsg('Villa!', 'Auðkenni má ekki vera það sjálfgefna eða tómt.');
	$err = '1';
}

if($err != '1') {
$res = mysql_query("SELECT users.username, requests.userid, requests.request FROM requests inner join users on requests.userid = users.id where requests.id = $requestid") or sqlerr();
 $arr = mysql_fetch_assoc($res);

$res2 = mysql_query("SELECT username FROM users where id =" . $CURUSER[id]) or sqlerr();
 $arr2 = mysql_fetch_assoc($res2);


$msg = "Beiðnin þín, [url=reqdetails.php?id=" . $requestid . "][b]" . $arr[request] . "[/b][/url] hefur verið uppfyllt af [url=userdetails.php?id=" . $CURUSER[id] . "][b]" . $arr2[username] . "[/b][/url]. Þú getur náð í það frá [url=" . $filledurl. "][b]" . $filledurl. "[/b][/url].  Gjörðu svo vel að skilja eftir þakkir þar sem við á.  Ef, einhverra hluta vegna, þessi beiðni er ekki það sem þú spurðir um, gjörðu svo vel að endursetja beiðnina svo einhver annar geti uppfyllt hana en þú getur það með því að fara á [URL=reqreset.php?requestid=" . $requestid . "]þessa slóð[/url].  [b]EKKI[/b] fara á þennan tengil nema þú sért alveg viss um að beiðnin hafi ekki verið uppfyllt (ekki beðin/n um staðfestingu).";

       mysql_query ("UPDATE requests SET filled = '$filledurl', filledby = $CURUSER[id] WHERE id = $requestid") or sqlerr();
mysql_query("INSERT INTO messages (poster, sender, receiver, added, msg) VALUES(0, 0, $arr[userid], '" . get_date_time() . "', " . sqlesc($msg) . ")") or sqlerr(__FILE__, __LINE__);


print("Beiðni $requestid uppfyllt á <a href=$filledurl>$filledurl</a>.  Notandi <a href=userdetails.php?id=$arr[userid]><b>$arr[username]</b></a> fékk sjálfkrafa 
einkaskilaboð.  Ef þú hefur gert mistök í innslætti slóðarinnar eða þú hefur uppgötvað að þetta torrent uppfylli ekki beiðnina, gjörðu svo vel að endursetja beiðnina svo 
einhver annar geti uppfyllt hana með því að <a href=reqreset.php?requestid=$requestid>klikka hér</a>  <b>EKKI</b> fara á þennan tengil nema þú sért viss um að það hafi 
verið vandamál.");

}
end_main_frame();
stdfoot();
?>
