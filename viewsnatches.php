<?

require "include/bittorrent.php";

dbconn(false);

loggedinorreturn();

ob_start("ob_gzhandler");

if(get_user_class() >= UC_MODERATOR) {

stdhead("Snatch Details");

begin_main_frame();


$res3 = mysql_query("select count(snatched.id) from snatched inner join users on snatched.userid = users.id inner join torrents on snatched.torrentid = torrents.id where snatched.torrentid =" . $_GET[id]) or die(mysql_error());
$row = mysql_fetch_array($res3);

$count = $row[0];
$perpage = 50;
list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] . "?id=" . $_GET[id] . "&" );

$res2 = mysql_query("select name from torrents where id = $_GET[id]");
$arr2 = mysql_fetch_assoc($res2);
$dt = gmtime() - 180;
$dt = sqlesc(get_date_time($dt));

print("<h1 align=center>Snatch Details for <a href=details.php?id=$_GET[id]><b>$arr2[name]</b></a></h1>\n");
print("<p align=center>The users at the top finished the download most recently</p>");

echo $pagertop;

print("<table border=1 cellspacing=0 cellpadding=5 align=center>\n");
print("<tr><td class=colhead align=left>Username</td><td class=colhead align=left>Uploaded</td><td class=colhead align=left>Downloaded</td><td class=colhead align=left>Share Ratio</td><td class=colhead align=left>PM User</td><td class=colhead align=left><font color=red>Report User</font></td><td class=colhead align=left>On/Off</td><td class=colhead align=left>Seeding</td></tr>");

$res = mysql_query("select users.id, users.username, users.uploaded, users.downloaded, snatched.userid from snatched inner join users on snatched.userid = users.id inner join torrents on snatched.torrentid = torrents.id where snatched.torrentid =" . $_GET[id] . " ORDER BY snatched.id desc $limit");
while ($arr = mysql_fetch_assoc($res))
{

$res2 = mysql_query("SELECT id,donor,title,enabled,warned,last_access FROM users WHERE id=$arr[userid]") or sqlerr(__FILE__, __LINE__);
$arr2 = mysql_fetch_assoc($res2);

$res3 = mysql_query("SELECT * FROM peers WHERE torrent=$_GET[id] AND userid=$arr[userid]");
$arr3 = mysql_fetch_assoc($res3);

if ($arr["downloaded"] > 0)
{
$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
else
if ($arr["uploaded"] > 0)
$ratio = "Inf.";
else
$ratio = "---";
$uploaded =mksize($arr["uploaded"]);
$downloaded = mksize($arr["downloaded"]);

print("<tr><td><a href=userdetails.php?id=$arr[userid]><b>$arr[username]</b></a></td><td align=left>$uploaded</td><td align=left>$downloaded</td><td align=left>$ratio</td><td><form method=get action=sendmessage.php><input type=hidden name=receiver value=" .
$arr[userid]."><input type=submit value=\"P M: $arr[username]\" style='height: 22px'></form><form method=post action=report.php?user=$arr[userid]></td><td align=left><input type=submit value=\"Report: $arr[username]\" style='height: 23px'></form></td><td align=center>" . get_user_icons($arr2, true) .

"&nbsp; ".("'".$arr2['last_access']."'">$dt?"<img src=".$pic_base_url."online.gif border=0 alt=\"Online\">":"<img src=".$pic_base_url."offline.gif border=0 alt=\"Offline\">" )."</td>"."
<td align=center>" . ($arr3["seeder"] == "yes" ? "<b><font color=green>Yes</font>" : "<font color=red>No</font></b>") . "</td></tr>\n");
}
print("</table>\n");

echo $pagerbottom;

}
stdfoot();

?>
