<?

require "include/bittorrent.php";

dbconn(false);

loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
  die;

$remove = $HTTP_GET_VARS['remove'];
if (is_valid_id($remove))
{
  mysql_query("DELETE FROM bans WHERE id=$remove") or sqlerr();
  write_log("Ban $remove was removed by $CURUSER[id] ($CURUSER[username])");
}

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST" && get_user_class() >= UC_ADMINISTRATOR)
{
	$first = trim($HTTP_POST_VARS["first"]);
	$last = trim($HTTP_POST_VARS["last"]);
	$comment = trim($HTTP_POST_VARS["comment"]);
	if (!$first || !$last || !$comment)
		stderr("Error", "Missing form data.");
	$first = ip2long($first);
	$last = ip2long($last);
	if ($first == -1 || $last == -1)
		stderr("Error", "Bad IP address.");
	$comment = sqlesc($comment);
	$added = sqlesc(get_date_time());
	mysql_query("INSERT INTO bans (added, addedby, first, last, comment) VALUES($added, $CURUSER[id], $first, $last, $comment)") or sqlerr(__FILE__, __LINE__);
	header("Location: $BASEURL$HTTP_SERVER_VARS[REQUEST_URI]");
	die;
}

ob_start("ob_gzhandler");
$ofset = $_GET['ofset'];
if(!$ofset)
$ofset = 0;
$ofsetx = $ofset*30;
$pg = $_GET['pg'];
  $ofset30 = $ofsetx+30;
  $ofsety = $ofset-1;
  $ofsetz = $ofset+1;
  $ofsetc = $ofsetx+1;
  if(!$pg)
  $pg = 0;
if($pg == 1)
$res = mysql_query("SELECT * FROM bans  WHERE comment LIKE 'PeerGuardian: %' ORDER BY added DESC limit $ofsetx , 30") or sqlerr();
else
$res = mysql_query("SELECT * FROM bans  WHERE comment NOT LIKE 'PeerGuardian: %' ORDER BY added DESC limit $ofsetx , 30") or sqlerr();
stdhead("Bans");

print("<h1>Current Bans</h1>\n");

if (mysql_num_rows($res) == 0) {
  print("<p align=center><b>Nothing found</b></p>");
  if($ofsety >= 1)
  print("<a href=?pg=" . $pg . "&ofset=" . $ofsety . ">Fyrri 30</a>\n");
  if($pg != 1)
  echo "<a href=?pg=1&offset=$ofset>PeerGuardian Bönn</a>";
  else
  echo "<a href=?pg=0&offset=$ofset>Notanda Bönn</a>";
}
else
{
  print("<table border=1 cellspacing=0 cellpadding=5>\n");
  print("<tr><td class=colhead>Added</td><td class=colhead align=left>First IP</td><td class=colhead align=left>Last IP</td>".
    "<td class=colhead align=left>By</td><td class=colhead align=left>Comment</td><td class=colhead>Remove</td></tr>\n");

  while ($arr = mysql_fetch_assoc($res))
  {
  	$r2 = mysql_query("SELECT username FROM users WHERE id=$arr[addedby]") or sqlerr();
  	$a2 = mysql_fetch_assoc($r2);
	$arr["first"] = long2ip($arr["first"]);
	$arr["last"] = long2ip($arr["last"]);
 	  print("<tr><td>$arr[added]</td><td align=left>$arr[first]</td><td align=left>$arr[last]</td><td align=left><a href=userdetails.php?id=$arr[addedby]>$a2[username]".
 	    "</a></td><td align=left>$arr[comment]</td><td><a href=bans.php?remove=$arr[id]>Remove</a></td></tr>\n");
  }
  print("</table><br>\n");
  if($ofset < 1) {
  $prev = '';
  } else {
  $prev = '<a href=?pg=' . $pg . '&ofset=' . $ofsety . '>Fyrri 30</a>';
  }
  $next = "<a href=?pg=" . $pg . "&ofset=" . $ofsetz . ">Næstu 30</a>";
  echo "$prev $ofsetc - $ofset30 $next<br>";
  if($pg != 1)
  echo "<a href=?pg=1&offset=$ofset>PeerGuardian Bönn</a>";
  else
  echo "<a href=?pg=0&offset=$ofset>Notanda Bönn</a>";
}

if (get_user_class() >= UC_ADMINISTRATOR)
{
	print("<h2>Add ban</h2>\n");
	print("<table border=1 cellspacing=0 cellpadding=5>\n");
	print("<form method=post action=bans.php>\n");
	print("<tr><td class=rowhead>First IP</td><td><input type=text name=first size=40></td>\n");
	print("<tr><td class=rowhead>Last IP</td><td><input type=text name=last size=40></td>\n");
	print("<tr><td class=rowhead>Comment</td><td><input type=text name=comment size=40></td>\n");
	print("<tr><td colspan=2><input type=submit value='Okay' class=btn></td></tr>\n");
	print("</form>\n</table>\n");
}

stdfoot();

?>