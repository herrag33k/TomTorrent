<?php
require_once('include/bittorrent.php');
dbconn();
loggedinorreturn();

if($CURUSER['id'] != '2') {
die('Átt ekkert að koma hingað!');
}

$id = 0 + $_GET['id'];

stdhead('Invite Tree', false);

echo('<table border=0 width=600 cellspacing=0 cellpadding=5>
<tr><td class=tabletitle><b>Invite Tree</b></td></tr>');

$ret = mysql_query("SELECT username, id, uploaded, downloaded, invitari 
FROM users WHERE status = 'confirmed' AND invitari = $id ORDER BY 
username") or sqlerr();
while ($arr = mysql_fetch_assoc($ret)) {

if ($arr['downloaded'] > 0) {
$ratio = $arr['uploaded'] / $arr['downloaded'];
$ratio = number_format($ratio, 3);
$color = get_ratio_color($ratio);
if ($color)
$ratio = "(<font color=$color>$ratio</font>)";
}
else
if ($arr['uploaded'] > 0)
$ratio = '(Inf.)';
else
$ratio = '(---)';

echo("<tr><td class=tableb align=left>&diams;<a 
href=userdetails.php?id=$arr[id]><b><u>$arr[username]</u></b></a> 
$ratio");

$rei = mysql_query("SELECT DISTINCT username, id, invitari FROM users 
WHERE invitari = $arr[id] AND status='confirmed'") or sqlerr();
while ($arr2 = mysql_fetch_assoc($rei)) {

if($arr2['invitari'] > 0)
$user1 = "<br>&nbsp;&nbsp;&nbsp;-<a 
href=userdetails.php?id=$arr2[id]>$arr2[username]</a>";
else
$user1 = "";

print("$user1");
/*
$rel = mysql_query("SELECT DISTINCT username, id, invitari FROM users 
WHERE invitari = $arr2[id] AND status='confirmed'") or sqlerr();
while ($arr3 = mysql_fetch_assoc($rel)) {

if($arr3['invitari'] > 0)
$user2 = "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-<a 
href=userdetails.php?id=$arr3[id]>$arr3[username]</a>";
else
$user2 = "";

print("$user2");

} */
}
print("</td></tr>");
}
print("</table>");

stdfoot();
?>
