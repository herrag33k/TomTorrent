<?
require "include/bittorrent.php";

dbconn();

loggedinorreturn();

function gethostbyaddr_with_cache($a) {
	global $dns_cache;
	if ($dns_cache[$a]) {
		return $dns_cache[$a];
	} else {
		$temp = gethostbyaddr($a);
		$dns_cache[$a] = $temp;
		return $temp;
	}
}

stdhead("Duplicate IP users");
begin_frame("Duplicate IP users:", true);
begin_table();

if ($CURUSER["id"] != $user["id"])
{
if (get_user_class() >= UC_MODERATOR)
{
$res = mysql_query("SELECT * FROM users WHERE enabled='yes' AND status='confirmed' AND ip<>'' ORDER BY ip") or sqlerr();
$num = mysql_num_rows($res);
print("<tr align=center><td class=colhead width=90>User</td>
<td class=colhead width=70>Email</td>
<td class=colhead width=70>Registered</td>
<td class=colhead width=75>Last access</td>
<td class=colhead width=70>Downloaded</td>
<td class=colhead width=70>Uploaded</td>
<td class=colhead width=45>Ratio</td>
<td class=colhead width=125>IP</td></tr>\n");
$uc = 0;
while($ras=mysql_fetch_assoc($res))
{
if ($ip <> $ras['ip'])
{
$ros = mysql_query("SELECT * FROM users WHERE ip='".$ras['ip']."' ORDER BY id") or sqlerr();
$num2 = mysql_num_rows($ros);
if ($num2 > 1)
{
$uc++;
while($arr = mysql_fetch_assoc($ros))
{
if ($arr['added'] == '0000-00-00 00:00:00')
$arr['added'] = '-';
if ($arr['last_access'] == '0000-00-00 00:00:00')
$arr['last_access'] = '-';
if($arr["downloaded"] != 0)
$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
else
$ratio="---";

$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
$uploaded = mksize($arr["uploaded"]);
$downloaded = mksize($arr["downloaded"]);
$added = substr($arr['added'],0,10);
$last_access = substr($arr['last_access'],0,10);
if($uc%2 == 0)
$utc = "a08f74";
else
$utc = "bbaf9b";
echo '<tr bgcolor="#'.$utc.'"><td align=left><b><a href="userdetails.php?id=' . $arr['id'] . '">' . 
$arr['username'].'</b></a>' . get_user_icons($arr) . '</td>
<td align=center>'.$arr[email].'</td>
<td align=center>'.$added.'</td>
<td align=center>'.$last_access.'</td>
<td align=center>'.$downloaded.'</td>
<td align=center>'.$uploaded.'</td>
<td align=center>'.$ratio.'</td>
<td align=center>'.gethostbyaddr_with_cache($arr[ip]).'</td></tr>'."\n";
$ip = $arr[ip];
}
}
}
}
}
else
{
print("<br><table width=60% border=1 cellspacing=0 cellpadding=9><tr><td align=center>");
print("<h2>You are not able to view this page.</h2></table></td></tr>");
}
}

stdfoot();
?>
