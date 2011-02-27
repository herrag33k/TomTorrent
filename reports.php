<?

require "include/bittorrent.php";

dbconn(false);

loggedinorreturn();

ob_start("ob_gzhandler");


stdhead("Active Reports");

begin_main_frame();

if (get_user_class() >= UC_MODERATOR)
{
$type = $_GET["type"];
if ($type == "user")
$where = " WHERE type = 'user'";
else if ($type == "torrent")
$where = " WHERE type = 'torrent'";
else
$where = "";

$res = mysql_query("SELECT count(id) FROM reports $where") or die(mysql_error());
$row = mysql_fetch_array($res);

$count = $row[0];
$perpage = 25;
list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] . "?type=" . $_GET["type"] . "&" );

print("<h1 align=center>Reports</h1>");

echo $pagertop;

print("<table border=1 cellspacing=0 cellpadding=5 align=center width=95%>\n");
print("<tr><td class=colhead align=left>By</td><td class=colhead align=left>Reporting</td><td class=colhead align=left>Type</td><td class=colhead align=left>Reason</td><td class=colhead align=left>Dealt With</td><td class=colhead align=center>Mark Dealt With</td>");
if (get_user_class() >= UC_SYSOP)
 printf("<td class=colhead align=left>Delete</td>");
print("</tr>");
print("<form method=post action=takedelreport.php>");

$res = mysql_query("SELECT reports.id, reports.dealtwith,reports.dealtby, reports.addedby, reports.votedfor, reports.reason, reports.type, users.username FROM reports INNER JOIN users on reports.addedby = users.id $where ORDER BY id desc $limit");

while ($arr = mysql_fetch_assoc($res))
{
if ($arr[dealtwith])
{
$res3 = mysql_query("SELECT username FROM users WHERE id=$arr[dealtby]");
$arr3 = mysql_fetch_assoc($res3);
$dealtwith = "<font color=green><b>Yes - <a href=userdetails.php?id=$arr[dealtby]><b>$arr3[username]</b></a></b></font>";
}
else
$dealtwith = "<font color=red><b>No</b></font>";
if ($arr[type] == "user")
{
$type = "userdetails";
$res2 = mysql_query("SELECT username FROM users WHERE id=$arr[votedfor]");
$arr2 = mysql_fetch_assoc($res2);
$name = $arr2[username];
}
else if ($arr[type] == "torrent")
{
$type = "details";
$res2 = mysql_query("SELECT name FROM torrents WHERE id=$arr[votedfor]");
$arr2 = mysql_fetch_assoc($res2);
$name = $arr2[name];
if ($name == "")
 $name = "<b>[Deleted]</b>";
}
print("<tr><td><a href=userdetails.php?id=$arr[addedby]><b>$arr[username]</b></a></td><td align=left><a href=$type.php?id=$arr[votedfor]><b>$name</b></a></td><td align=left>$arr[type]</td><td align=left>$arr[reason]</td><td align=left>$dealtwith</td><td><input type=\"checkbox\" name=\"delreport[]\" value=\"" . $arr[id] . "\" /></td>\n");
if (get_user_class() >= UC_SYSOP)
 printf("<td><a href=delreport.php?id=$arr[id]>Delete</a></td>");
print("</tr>");
}

print("</table>\n");

print("<p align=right><input type=submit value=Confirm></p>");
print("</form>");

echo $pagerbottom;
end_main_frame();
stdfoot();
} else {
print("<h1>Aðeins fyrir Stjórnendur</h1>");
end_main_frame();
stdfoot();
}
?>
