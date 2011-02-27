<?
require "include/bittorrent.php";

dbconn();

loggedinorreturn();

function reqcommenttable($rows)
{
	global $CURUSER, $HTTP_SERVER_VARS;
	begin_main_frame();
	begin_frame();
	$count = 0;

	foreach ($rows as $row) {
		print("<p class=sub>#" . $row["id"] . " bY: ");
		if (isset($row["username"])) {
			$username = $row["username"];
			$ratres = mysql_query("SELECT uploaded, downloaded FROM users WHERE username='$username'");
			$rat = mysql_fetch_array($ratres);
			if ($rat["downloaded"] > 0) {
				$ratio = $rat['uploaded'] / $rat['downloaded'];
				$ratio = number_format($ratio, 3);
				$color = get_ratio_color($ratio);
				if ($color)
					$ratio = "<font color=$color>$ratio</font>";
			} else
				if ($rat["uploaded"] > 0)
					$ratio = "Inf.";
				else
					$ratio = "---";

			$title = $row["title"];
			if ($title == "")
				$title = get_user_class_name($row["class"]);
			else
				$title = htmlspecialchars($title);
			print("<a name=comm".$row["id"]." href=userdetails.php?id=".$row["user"]."><b>".htmlspecialchars($row["username"])."</b></a>".($row["donor"] == "yes" ? "<img src=pic/star.gif alt='Donor'>" : "").($row["warned"] == "yes" ? "<img src="."/pic/warned.gif alt=\"Warned\">" : "")." ($title) (Ratio: $ratio)\n");
		} else
			print("<a name=\"comm" . $row["id"] . "\"><i>(orphaned)</i></a>\n");
		print(" at " . $row["added"] . " GMT" .($row["user"] == $CURUSER["id"] || get_user_class() >= UC_MODERATOR ? "- [<a href=reqcomment.php?action=edit&amp;cid=$row[id]>Edit</a>]" : "") .(get_user_class() >= UC_MODERATOR ? "- [<a href=reqcomment.php?action=delete&amp;cid=$row[id]>Delete</a>]" : "") . "</p>\n");$avatar = ($CURUSER["avatars"] == "yes" ? htmlspecialchars($row["avatar"]) : "");
		if (!$avatar)
			$avatar = "/pic/default_avatar.gif";
		$text = format_comment($row["text"]);
		begin_table(true);
		print("<tr valign=top>\n");
		print("<td align=center width=150 style='padding: 0px'><img width=150 src=$avatar></td>\n");
		print("<td class=text>$text</td>\n");
		print("</tr>\n");
		end_table();
	}
	end_frame();
	end_main_frame();
}

stdhead("Smáatriði um beiðni");
$id = $_GET["id"];
if(!empty($id) && !is_numeric($id))
	die('"id" is not a number');
$res = mysql_query("SELECT * FROM requests WHERE id = $id") or sqlerr();
$num = mysql_fetch_array($res);

$s = $num["request"];

print("<h1>Upplýsingar um $s</h1>\n");

print("<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
print("<tr><td align=left>Beiðni</td><td width=90% align=left>$num[request]</td></tr>");
if ($num["descr"])
print("<tr><td align=left>Upplýsingar</td><td width=90% align=left>$num[descr]</td></tr>");
print("<tr><td align=left>Bætt við</td><td width=90% align=left>$num[added]</td></tr>");

$cres = mysql_query("SELECT username FROM users WHERE id=$num[userid]");
   if (mysql_num_rows($cres) == 1)
   {
     $carr = mysql_fetch_assoc($cres);
     $username = "$carr[username]";
   }
print("<tr><td align=left>Beiðandi</td><td width=90% align=left>$username</td></tr>");
print("<tr><td align=left>Kjóstu um beiðnina</td><td width=50% align=left><a href=addrequest.php?id=$id><b>Kjósa</b></a></tr></tr>");

if ($num["filled"] == NULL)
{
print("<form method=get action=reqfilled.php>");
print("<tr><td align=left>Uppfyllt beiðni</td><td>Sláðu inn <b>alla</b> slóð torrentsins t.d. http://torrent.is/details.php?id=1 (bara klipptu/límdu frá öðrum glugga) 
eða breyttu slóðinni sem er þarna nú þegar til að fá rétta auðkennið</td></tr>");
print("</table>");
print("<input type=text size=80 name=filledurl value=http://torrent.is/details.php?id=1>\n");
print("<input type=hidden value=$id name=requestid>");
print("<input type=submit value=\"Uppfylla beiðni\" style='height: 22px'>\n</form>");
echo 'Áður en þú sendir að þú hafir uppfyllt beiðnina, <a href="/faq.php#81">athugaðu hvað SOS nefnir um það</a><br />';
} else
echo '<br />Beiðninni hefur verið svarað. Slóðin á viðeigandi torrent er <a href="'.$num['filled'].'">'.$num['filled'].'</a>';

print("<p></p><form method=\"get\" action=\"requests.php#add\"><input type=\"submit\" value=\"Bæta við beiðni\" style='height: 22px' /></form>");

$commentbar = "<p align=center><a class=index 
href=reqcomment.php?action=add&amp;tid=$id>Add comment</a></p>\n";

$subres = mysql_query("SELECT COUNT(*) FROM comments WHERE req = $id");
$subrow = @mysql_fetch_array($subres);
$count = $subrow[0];
print("</td></tr></table>");
if (!$count) {
print("<h2>No comments</h2>\n");

}
else {
list($pagertop, $pagerbottom, $limit) = pager(20, $count, 
"reqdetails.php?id=$id&", array(lastpagedefault => 1));

$subres = mysql_query("SELECT comments.id, text, user, comments.added, avatar, warned, "."username, title, class, donor FROM comments LEFT JOIN users ON comments.user = users.id WHERE req = " ."$id ORDER BY comments.id $limit") or sqlerr(__FILE__, __LINE__);
$allrows = array();
while ($subrow = mysql_fetch_array($subres))
$allrows[] = $subrow;

print($commentbar);
print($pagertop);

reqcommenttable($allrows);

print($pagerbottom);
}

print($commentbar);

stdfoot();
die;

?>
