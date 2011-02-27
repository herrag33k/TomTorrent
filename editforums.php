<?
ob_start();
require_once("include/bittorrent.php");
dbconn(false);
loggedinorreturn();
if (get_user_class() < UC_SYSOP) {
die("Access denied.");
}

stdhead("Edit Forums");
print("<h1>Edit Forums</h1>\n");
print("</br>");
print("<table width=90% border=1 cellspacing=0 cellpadding=2><tr><td align=center>\n");

//Delete Forums

if($_GET['action'] == 'delforum')
{
$delid = $_GET['forumid'];
$name = $_GET['forumname'];
$sure = $_GET['sure'];
if($sure == 'yes')
{
$query = "DELETE FROM forums WHERE id = " .sqlesc($delid) . " LIMIT 1";
$sql= mysql_query($query);
echo("Forum, '$name' has been succesfully deleted! [ <a href='editforums.php'>Back to editing screen</a> ]");
end_frame();
stdfoot();
die();
}else{
if($delid >= 0)
{
echo("Are you sure you want to delete forum '$name'?
( <strong><a href='". $_SERVER['PHP_SELF'] . 
"?action=delforum&forumid=$delid&forumname=$name&sure=yes'>Y</a></strong>
/ <strong><a href='". $_SERVER['PHP_SELF'] . "'>N</a></strong> )");
}
}
end_frame();
stdfoot();
die();
}

//Add new forum

if($_GET['action'] == 'add')
{
echo("<a href='editforums.php'><strong>Back</strong></a>");
echo("<br><form name='add' method='get' action='". $_SERVER['PHP_SELF'] ."'>");
echo("<input type='hidden' name='action' value='takeadd'>");
echo("<table class=main cellspacing=0 cellpadding=5 width=50%>");
echo("<tr><td>Name: </td><td align='center'><input type='text' size=50 name='name'></td></tr>");
echo("<tr><td>Description: </td><td align='center'><input type='text' size=50 name='desc'></td></tr>");
echo("<tr><td>Sort: </td><td align='center'><input type='text' size=50 name='sort'></td></tr>");
echo("<tr><td>Viewable By: </td><td align=center><select name=viewby>");
for($i=0; $i<7; $i++)
{
echo("<option value=$i>". get_user_class_name($i) ."</option>/n");
}
echo("</select></td></tr>\n");
echo("<tr><td>Able to post: </td><td align=center><select name=apost>");
for($i=0; $i<7; $i++)
{
echo("<option value=$i>". get_user_class_name($i) ."</option>/n");
}
echo("</select></td></tr>\n");
echo("<tr><td>Able to Create Topics: </td><td align=center><select name=acreate>");
for($i=0; $i<7; $i++)
{
echo("<option value=$i>". get_user_class_name($i) ."</option>/n");
}
echo("</select></td></tr>\n");

echo("<tr><td colspan=2><div align='center'><input type='Submit'></div></td></tr></table>");
}

if($_GET['action'] == 'takeadd')
{
$sort = $_GET['sort'];
$name = $_GET['name'];
$desc = $_GET['desc'];
$viewby = $_GET['viewby'];
$apost = $_GET['apost'];
$acreate = $_GET['acreate'];
if(!$sort || !$name || !$desc)
{
echo("Please fill in all required fields <a href='editforums.php?action=add'><strong>back</strong></a>");
end_frame();
stdfoot();
die();
}
$query = "SELECT sort FROM forums WHERE sort = $sort";
$loc = mysql_query($query);
if(mysql_num_rows($loc) > '0')
{
echo("That sort number already exists! <a href='editforums.php?action=add'><strong>back</strong></a>");
}else{
$query = "INSERT INTO forums SET
sort = '$sort',
name = '$name',
description = '$desc',
minclasswrite = '$viewby',
minclassread = '$apost',
minclasscreate = '$acreate'";
mysql_query($query) or sqlerr(__FILE__,__LINE__);
echo("Forum added <a href='editforums.php?action=add'><strong>back</strong></a>");
}
end_frame();
stdfoot();
die();
}

// Edit forum

if($_GET['action'] == 'editforum')
{
$edid = $_GET['forumid'];
$query = "SELECT * FROM forums WHERE id = $edid";
$loc = mysql_query($query);
$row = mysql_fetch_assoc($loc);

echo("<a href='editforums.php'><strong>Back</strong></a>");
echo("<br><form name='add' method='get' action='". $_SERVER['PHP_SELF'] ."'>");
echo("<input type='hidden' name='action' value='takeedit'>");
echo("<input type='hidden' name='forumid' value='$edid'>");
echo("<table class=main cellspacing=0 cellpadding=5 width=50%>");
echo("<tr><td>Name: </td><td align='center'><input type='text' value='".$row['name']."' size=50 
name='name'></td></tr>");
echo("<tr><td>Description: </td><td align='center'><input type='text' value='".$row['description']."' size=50 
name='desc'></td></tr>");
echo("<tr><td>Sort: </td><td align='center'><input type='text' value='".$row['sort']."' size=50 
name='sort'></td></tr>");

echo("<tr><td>Viewable by:</td><td align=center><select name=viewby>\n");
for($i=0; $i<7; $i++)
{ 
echo("<option value ='$i'". ($i == $row['minclassread'] ? "selected" : "") 
.">".get_user_class_name($i)."</option>\n");
}
echo("</select> or higher</td></tr>");
echo("<tr><td>Able to post:</td><td align=center><select name=apost>\n");
for($i=0; $i<7; $i++)
{ 
echo("<option value ='$i'". ($i == $row['minclasswrite'] ? "selected" : "") .">" . get_user_class_name($i) . 
"</option>\n");
}
echo("</select> or higher</td></tr>");
echo("<tr><td>Able to create topics:</td><td align=center><select name=atopic>\n");
for($i=0; $i<7; $i++)
{ 
echo("<option value ='$i'". ($i == $row['minclasscreate'] ? "selected" : "") 
.">".get_user_class_name($i)."</option>\n");
}
echo("</select> or higher</td></tr>");
echo("<tr><td colspan=2><div align='center'><input type='Submit'></div></td></tr></table>");
}

if($_GET['action'] == 'takeedit')
{
$newname = $_GET['name'];
$newdesc = $_GET['desc'];
$newsort = $_GET['sort'];
$newread = $_GET['viewby'];
$newwrite = $_GET['apost'];
$newcreate = $_GET['atopic'];
$id = $_GET['forumid'];
$query = "SELECT * FROM forums WHERE id = $id";
$loc = mysql_query($query);
$row = mysql_fetch_assoc($loc);
$query2 = "SELECT sort FROM forums";
$loc2 = mysql_query($query2);
while($row2 = mysql_fetch_assoc($loc2))
{
if($newsort == $row2['sort'] && $row['sort'] !== $newsort)
{
$se = 'TRUE';
}
}
if($newname == $row['name'] && $newdesc == $row['description'] && $newsort == $row['sort'] && $newread == 
$row['minclassread']
&& $newwrite == $row['minclasswrite'] && $newcreate == $row['minclasscreate'])
echo("No change has been made <a href='editforums.php?action=editforum&forumid=$id'><strong>back</strong></a><br>");
elseif($se == 'TRUE')
echo("This sort number already exists <a 
href='editforums.php?action=editforum&forumid=$id'><strong>back</strong></a><br>");
else{ 

if($newname != $row['name'])
$updateset[] = "name = ". sqlesc($newname);
if($newdesc != $row['description'])
$updateset[] = "description = ". sqlesc($newdesc);
if($newsort != $row['sort'])
$updateset[] = "sort = $newsort";
if($newread != $row['minclassread'])
$updateset[] = "minclassread = $newread";
if($newwrite != $row['minclasswrite'])
$updateset[] = "minclasswrite = $newwrite";
if($newcreate != $row['minclasscreate'])
$updateset[] = "minclasscreate = $newcreate";

//echo $updateset[0] . "<br>" . $updateset[1] . "<br>";

mysql_query("UPDATE forums SET " . implode(", ", $updateset) . " WHERE id=$id") or sqlerr(__FILE__, __LINE__); 

echo("Forum updated! <a href='editforums.php'><strong>back</strong></a><br>");
}
end_frame();
stdfoot();
die();
}

// Current Forums

echo("<br><table class=main cellspacing=0 cellpadding=5>");
echo("<tr>
<td class=colhead>Sort:</td>
<td class=colhead>ID:</td>
<td class=colhead>Name:</td>
<td class=colhead>Description:</td>
<td class=colhead>Viewable By:</td>
<td class=colhead>Able To Post:</td>
<td class=colhead>Able To Create Topics:</td>
<td class=colhead>Number Of Topics:</td>
<td class=colhead>Number Of Posts:</td>
<td class=colhead>Browse:</td>
<td class=colhead>Edit:</td>
<td class=colhead>Delete:</td>
</tr>");
$query = "SELECT * FROM forums ORDER BY sort ASC";
$loc = mysql_query($query);
while($row = mysql_fetch_array($loc))
{
$id = $row['id'];
$sort = $row['sort'];
$name = $row['name'];
$desc = $row['description'];
$minr = $row['minclassread'];
$minw = $row['minclasswrite'];
$minc = $row['minclasscreate'];
$posts = $row['postcount'];
$topics = $row['topiccount'];
echo"<tr>
<td><strong>$sort</strong><td><strong>$id</strong></td></td><td>$name</td><td>$desc</td>
<td>".get_user_class_name($minr)."s or higher</td>
<td>".get_user_class_name($minw)."s or higher</td>
<td>".get_user_class_name($minc)."s or higher</td>
<td>$topics</td><td>$posts</td>
<td><div align='center'><a href='forums.php?action=viewforum&forumid=$id'>
<img src='$BASEURL/pic/viewnfo.gif' border='0' class=special /></a></div></td>
<td><div align='center'><a href='editforums.php?action=editforum&forumid=$id'>
<img src='$BASEURL/pic/multipage.gif' border='0' class=special /></a></div></td>
<td><div align='center'><a href='editforums.php?action=delforum&forumid=$id&forumname=$name'>
<img src='$BASEURL/pic/warned2.gif' border='0' class=special /></a></div></td>
</tr>";
}
echo"</table>";
if(!$_GET['action'])
{
echo"<br><a href=".$_SERVER['PHP_SELF']."?action=add>Add new Forum</a><br><br>";
}else{
echo"<br><br>";
}
end_frame();
stdfoot();

