<?
require "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead("Aðvörun notenda");
$warned = number_format(get_row_count("users", "WHERE warned='yes'"));
begin_frame("Notendur með aðvörun: ($warned)", true);
begin_table();

if ($CURUSER["id"] != $user["id"])
{
if (get_user_class() >= UC_MODERATOR){
$res = mysql_query("SELECT * FROM users WHERE warned=1 AND enabled='yes' ORDER BY (users.uploaded/users.downloaded)") or sqlerr();
$num = mysql_num_rows($res);
print("<table border=1 width=675 cellspacing=0 cellpadding=2><form action=\"nowarn.php\" method=post>\n");
print("<tr align=center><td class=colhead width=90>Notendanafn</td>
     <td class=colhead width=70>Skráning</td>
  <td class=colhead width=75>Seinasta Innskráning</td>
  <td class=colhead width=70>Deilt</td>
  <td class=colhead width=70>Sótt</td>
  <td class=colhead width=45>Hlutfall</td>
  <td class=colhead width=125>Aðvörun<br>rennur út</td>
  <td class=colhead width=65>Fjarlægja aðvörun</td>
  <td class=colhead width=65>Óvirkja notanda</td></tr>\n");
for ($i = 1; $i <= $num; $i++)
{
 $arr = mysql_fetch_assoc($res);
 if ($arr['added'] == '0000-00-00 00:00:00')
   $arr['added'] = '-';
 if ($arr['last_access'] == '0000-00-00 00:00:00')
   $arr['last_access'] = '-';
 

if($arr["downloaded"] != 0){
 $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
} else {
 $ratio="---";
}
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
   $uploaded = mksize($arr["uploaded"]);
   $downloaded = mksize($arr["downloaded"]);
// $uploaded = str_replace(" ", "<br>", mksize($arr["uploaded"]));
// $downloaded = str_replace(" ", "<br>", mksize($arr["downloaded"]));

$added = substr($arr['added'],0,10);
$last_access = substr($arr['last_access'],0,10);

print("<tr><td align=left><a href=/userdetails.php?id=$arr[id]><b>$arr[username]</b></a>" .($arr["donated"] > 0 ? "<img src=/pic/star.gif border=0 alt='Donor'>" : "")."</td>
      <td align=center>$added</td>
   <td align=center>$last_access</td>
   <td align=center>$uploaded</td>
   <td align=center>$downloaded</td>
   <td align=center>$ratio</td>
   <td align=center>$arr[warneduntil]</td>
   <td bgcolor=\"#008000\" align=center><input type=\"checkbox\" name=\"usernw[]\" value=\"$arr[id]\"></td>
   <td bgcolor=\"#FF000\" align=center><input type=\"checkbox\" name=\"desact[]\" value=\"$arr[id]\"></td></tr>\n");
}
print("<tr><td colspan=10 align=right><input type=\"submit\" name=\"submit\" value=\"Staðfesta\"></td></tr>\n");
print("<input type=\"hidden\" name=\"nowarned\" value=\"nowarned\"></form></table>\n");
print("<p>$pagemenu<br>$browsemenu</p>");
}else
{
print("<br><table width=60% border=1 cellspacing=0 cellpadding=9><tr><td align=center>");
print("<h2>Þú hefur ekki leyfi til að skoða þennan hluta síðunnar</h2></table></td></tr>");
}
}

stdfoot();
die;

?>
