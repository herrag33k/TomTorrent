<?php
require_once("include/bittorrent.php");
hit_start();
dbconn();
loggedinorreturn();

stdhead("Uppfylla bei�ni");

begin_main_frame();

$filledurl = $_GET["filledurl"];
$requestid = $_GET["requestid"];

if($filledurl == '' || $filledurl == 'http://torrent.is/details.php?id=1') {
	stdmsg('Villa!', 'Au�kenni m� ekki vera �a� sj�lfgefna e�a t�mt.');
	$err = '1';
}

if($err != '1') {
$res = mysql_query("SELECT users.username, requests.userid, requests.request FROM requests inner join users on requests.userid = users.id where requests.id = $requestid") or sqlerr();
 $arr = mysql_fetch_assoc($res);

$res2 = mysql_query("SELECT username FROM users where id =" . $CURUSER[id]) or sqlerr();
 $arr2 = mysql_fetch_assoc($res2);


$msg = "Bei�nin ��n, [url=reqdetails.php?id=" . $requestid . "][b]" . $arr[request] . "[/b][/url] hefur veri� uppfyllt af [url=userdetails.php?id=" . $CURUSER[id] . "][b]" . $arr2[username] . "[/b][/url]. �� getur n�� � �a� fr� [url=" . $filledurl. "][b]" . $filledurl. "[/b][/url].  Gj�r�u svo vel a� skilja eftir �akkir �ar sem vi� �.  Ef, einhverra hluta vegna, �essi bei�ni er ekki �a� sem �� spur�ir um, gj�r�u svo vel a� endursetja bei�nina svo einhver annar geti uppfyllt hana en �� getur �a� me� �v� a� fara � [URL=reqreset.php?requestid=" . $requestid . "]�essa sl��[/url].  [b]EKKI[/b] fara � �ennan tengil nema �� s�rt alveg viss um a� bei�nin hafi ekki veri� uppfyllt (ekki be�in/n um sta�festingu).";

       mysql_query ("UPDATE requests SET filled = '$filledurl', filledby = $CURUSER[id] WHERE id = $requestid") or sqlerr();
mysql_query("INSERT INTO messages (poster, sender, receiver, added, msg) VALUES(0, 0, $arr[userid], '" . get_date_time() . "', " . sqlesc($msg) . ")") or sqlerr(__FILE__, __LINE__);


print("Bei�ni $requestid uppfyllt � <a href=$filledurl>$filledurl</a>.  Notandi <a href=userdetails.php?id=$arr[userid]><b>$arr[username]</b></a> f�kk sj�lfkrafa 
einkaskilabo�.  Ef �� hefur gert mist�k � innsl�tti sl��arinnar e�a �� hefur uppg�tva� a� �etta torrent uppfylli ekki bei�nina, gj�r�u svo vel a� endursetja bei�nina svo 
einhver annar geti uppfyllt hana me� �v� a� <a href=reqreset.php?requestid=$requestid>klikka h�r</a>  <b>EKKI</b> fara � �ennan tengil nema �� s�rt viss um a� �a� hafi 
veri� vandam�l.");

}
end_main_frame();
stdfoot();
?>
