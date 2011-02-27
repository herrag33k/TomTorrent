<?php
require_once("include/bittorrent.php");
hit_start();
dbconn();
loggedinorreturn();

stdhead("Endurræsa beiðni");

begin_main_frame();

$requestid = $_GET["requestid"];


$res = mysql_query("SELECT userid, filledby FROM requests WHERE id =$requestid") or sqlerr();
 $arr = mysql_fetch_assoc($res);


if (($CURUSER[id] === $arr[userid]) || (get_user_class() >= UC_MODERATOR) || ($CURUSER[id] === $arr[filledby]))
{

 @mysql_query("UPDATE requests SET filled='', filledby=0 WHERE id =$requestid") or sqlerr();
 
 print("Beiðni $requestid endursett.");
}
else
 print("Fyrirgefðu, þú getur ekki endursett beiðni sem þú átt ekki");


end_main_frame();
stdfoot();
?>
