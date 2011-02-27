<?php
require_once('include/bittorrent.php');
dbconn();
loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
die("Access denied.");


if(!is_numeric($_GET["inv"])) {
echo "You need inv variable in the link"; 
die; 
}
if(!is_numeric($_GET["id"])) { 
echo "You need id variable in the link"; 
die; 
}


$id = 0 + $_GET['id'];
$inv = 0 + $_GET['inv'];

stdhead('Invite Tree', false);

echo('<table border=0 width=600 cellspacing=0 cellpadding=5>
<tr><td class=tabletitle><b>Invite Tree</b></td></tr>');

if ($inv != 0) {
echo "<tr><td><font color=green>Went up one level to user number $inv before making the tree</font></td></tr>";
$ret = mysql_query("SELECT username, id, uploaded, downloaded, invitari, email, enabled FROM users WHERE 
status = 'confirmed' AND invitari = $inv ORDER BY username") or sqlerr();
}
else
$ret = mysql_query("SELECT username, id, uploaded, downloaded, invitari, email, enabled FROM users WHERE 
status = 'confirmed' AND invitari = $id ORDER BY username") or sqlerr();

while ($arr = mysql_fetch_assoc($ret)) {

if ($arr['downloaded'] > 0) {
$ratio = $arr['uploaded'] / $arr['downloaded'];
$ratio = number_format($ratio, 2);
$color = get_ratio_color($ratio);
if ($color)
$ratio = "(<font color=$color>$ratio</font>)";
}
else
if ($arr['uploaded'] > 0)
$ratio = '(Inf.)';
else
$ratio = '(---)';

$end="";
if ($arr["enabled"] == "no")
$end .= " <img src=\"images/disabled.gif\"> ";

$end .= $arr["email"];
echo("<tr><td class=tableb align=left>.<a href=userdetails.php?id=$arr[id]><b><u>$arr[username]</u></b></a> 
$ratio $end");

$rei = mysql_query("SELECT DISTINCT username, id, invitari, downloaded, uploaded, email, enabled FROM users 
WHERE invitari = $arr[id] AND status='confirmed'") or sqlerr();
while ($arr2 = mysql_fetch_assoc($rei)) {

if($arr2['invitari'] > 0) {
if ($arr2['downloaded'] > 0) {
$ratio = $arr2['uploaded'] / $arr2['downloaded'];
$ratio = number_format($ratio, 2);
$color = get_ratio_color($ratio); 
if ($color)
$ratio = "(<font color=$color>$ratio</font>)";
}
else
if ($arr2['uploaded'] > 0)
$ratio = '(Inf.)';
else
$ratio = '(---)';

$end="";
if ($arr2["enabled"] == "no")
$end .= " <img src=\"images/disabled.gif\"> ";

$end .= $arr2["email"];


$user1 = "<br> -<a href=userdetails.php?id=$arr2[id]>$arr2[username]</a> $ratio $end"; }
else
$user1 = "";

print("$user1");

$rel = mysql_query("SELECT DISTINCT username, id, invitari, downloaded, uploaded, email, enabled FROM users 
WHERE invitari = $arr2[id] AND status='confirmed'") or sqlerr();
while ($arr3 = mysql_fetch_assoc($rel)) {

if($arr3['invitari'] > 0) {
if ($arr3['downloaded'] > 0) {
$ratio = $arr3['uploaded'] / $arr3['downloaded'];
$ratio = number_format($ratio, 2);
$color = get_ratio_color($ratio);
if ($color)
$ratio = "(<font color=$color>$ratio</font>)";
}
else
if ($arr3['uploaded'] > 0)
$ratio = '(Inf.)';
else
$ratio = '(---)';

$end="";
if ($arr3["enabled"] == "no")
$end .= " <img src=\"images/disabled.gif\"> ";

$end .= $arr3["email"];

$user2 = "<br> -<a href=userdetails.php?id=$arr3[id]>$arr3[username]</a> $ratio $end"; }
else
$user2 = "";

print("$user2");

$rek = mysql_query("SELECT DISTINCT username, id, invitari, uploaded, downloaded, enabled, email FROM users 
WHERE invitari = $arr3[id] AND status='confirmed'") or sqlerr();
while ($arr4 = mysql_fetch_assoc($rek)) {

if($arr3['invitari'] > 0){
if ($arr4['downloaded'] > 0) {
$ratio = $arr4['uploaded'] / $arr4['downloaded'];
$ratio = number_format($ratio, 2);
$color = get_ratio_color($ratio);
if ($color)
$ratio = "(<font color=$color>$ratio</font>)";
}
else
if ($arr4['uploaded'] > 0)
$ratio = '(Inf.)';
else
$ratio = '(---)';

$end="";
if ($arr4["enabled"] == "no")
$end .= " <img src=\"images/disabled.gif\"> ";

$end .= $arr4["email"];
$user3 = "<br> -<a href=userdetails.php?id=$arr4[id]>$arr4[username]</a> $ratio
$end"; }
else
$user3 = "";

print("$user3");

$rem = mysql_query("SELECT DISTINCT username, id, invitari, uploaded, downloaded, enabled, email FROM users 
WHERE invitari = $arr4[id] AND status='confirmed'") or sqlerr();
while ($arr5 = mysql_fetch_assoc($rek)) {

if($arr5['invitari'] > 0) {
if ($arr5['downloaded'] > 0) {
$ratio = $arr5['uploaded'] / $arr5['downloaded'];
$ratio = number_format($ratio, 2);
$color = get_ratio_color($ratio);
if ($color)
$ratio = "(<font color=$color>$ratio</font>)";
}
else
if ($arr5['uploaded'] > 0)
$ratio = '(Inf.)';
else
$ratio = '(---)';

$end="";
if ($arr5["enabled"] == "no")
$end .= " <img src=\"images/disabled.gif\"> ";

$end .= $arr5["email"];

$user4 = "<br> -<a
href=userdetails.php?id=$arr5[id]>$arr5[username]</a> $ratio $end"; }
else
$user4 = "";

print("$user4");

} 
} 

} 
}
print("</td></tr>");
}
print("</table>");

stdfoot();
?>
