<?php
ob_start("ob_gzhandler");
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

stdhead("Beiðnissíða");

begin_main_frame();

echo '<h1>Beiðnislistinn</h1>'."\n";
echo '<p><a href="requests.php">Leggja inn beiðni</a>&nbsp;&nbsp;<a href="viewrequests.php?requestorid='.$CURUSER['id'].'">Skoða mínar beiðnir</a></p>';
echo '<p>Raða eftir <a href="'.$_SERVER['PHP_SELF'].'?';
if(isset($_GET['category']))
	echo 'category='.$_GET['category'];
if(isset($_GET['filter']))
	echo '&filter='.$_GET['filter'];
echo '&sort=votes">atkvæðum</a>, <a href="'.$_SERVER['PHP_SELF'].'?';
if(isset($_GET['category']))
	echo 'category='.$_GET['category'];
if(isset($_GET['filter']))
	echo '&filter='.$_GET['filter'];
echo '&sort=request">nafn beiðnar</a> eða <a href="'.$_SERVER['PHP_SELF'].'?';
if(isset($_GET['category']))
	echo 'category='.$_GET['category'];
if(isset($_GET['filter']))
	echo '&filter='.$_GET['filter'];
echo '&sort=added">innsendingardegi</a></p>';
echo '<p><a href='.$_SERVER['PHP_SELF'].'?';
if(!empty($_GET['category'])) {
	$categ = $_GET['category'];
	echo 'category='.$_GET['category'];
}
if(isset($_GET['requestorid']))
	$requestorid = $_GET['requestorid'];
if(isset($_GET['sort'])) {
	echo '&sort='.$_GET['sort'];
	$sort = $_GET['sort'];
} else
	$sort = '';
if(isset($_GET['search'])) {
	$search = $_GET['search'];
	$search = ' AND MATCH (requests.request, requests.descr) AGAINST ('.sqlesc($search).' IN BOOLEAN MODE) ';
} else
	$search = '';
if(isset($_GET['filter']))
	$filter = ' '.$_GET['filter'];
else
	$filter = '';
echo '&filter=true>Fela uppfylltar</a></p>';


if (isset($sort) && $sort === 'votes')
	$sql_sort = ' ORDER BY votes DESC ';
elseif (isset($sort) && $sort === 'request')
	$sql_sort = ' ORDER BY request ';
else
	$sql_sort = ' ORDER BY added DESC ';


if (isset($filter) && $filter === 'true')
	$filter = ' AND requests.filledby=0 ';
else
	$filter = '';


if (isset($requestorid)) {
	if (isset($categ) && $categ !== '0')
		$categ = ' AND requests.cat='.$categ.' AND requests.userid='.$requestorid;
	else
		$categ = ' AND requests.userid = '.$requestorid;
} elseif (isset($categ) && $categ === '0')
	$categ = '';
elseif (isset($categ))
	$categ = ' AND requests.cat = '.$categ;
else
	$categ = '';

/*
if (isset($categ) && $categ !== '0')
	$categ = ' AND requests.cat = '.$categ;
*/

if(!empty($categ) || !empty($filter) || !empty($search))
	$sql = 'SELECT COUNT(*) FROM requests,categories,users WHERE requests.cat=categories.id AND requests.userid=users.id '.$categ.$filter.$search;
else
	$sql = 'SELECT COUNT(*) FROM requests';

$res = mysql_query($sql) or sqlerr();
//$row = mysql_fetch_array($res);
$count = mysql_result($res,0);

$perpage = 50;
list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER['PHP_SELF'].'?'.'category='.$categ.'&sort='.$sort.'&filter='.$filter.'&');

echo $pagertop;

$sql = 'SELECT users.downloaded, users.uploaded, users.username, requests.comments, requests.filled, requests.filledby, requests.id, requests.userid, requests.request, requests.added, (SELECT COUNT(*) FROM addedrequests WHERE requestid=requests.id) AS votes, categories.name AS cat FROM requests,categories,users WHERE requests.cat=categories.id AND requests.userid=users.id'.$categ.$filter.$search.$sql_sort.$limit;
$res = mysql_query($sql) or sqlerr();
$num = mysql_num_rows($res);



echo '<form method="get" action="viewrequests.php">';
?>
<select name="category">
<option value="0">(Sýna allt)</option>
<?

$cats = genrelist();
$catdropdown = "";
foreach ($cats as $cat) {
$catdropdown .= "<option value=\"" . $cat["id"] . "\"";
$catdropdown .= ">" . htmlspecialchars($cat["name"]) . "</option>\n";
}

?>
<?= $catdropdown ?>
</select>
<?
print("<input type=submit align=center value=Breyta style='height: 22px'>\n");
print("</form>\n<p></p>");

print("<form method=get action=viewrequests.php>");
print("<b>Leita að beiðni: </b><input type=text size=40 name=search>");
print("<input type=submit align=center value=Leita style='height: 22px'>\n");
print("</form><p></p><br />");





print("<form method=post action=takedelreq.php>");
print("<table border=1 width=950 cellspacing=0 cellpadding=5>\n");
print("<tr><td class=colhead align=left>Beiðni</td><td class=colhead align=center>Flokkur</td><td class=colhead align=center width=150>Bætt við</td><td class=colhead align=center>Beiðandi</td><td class=colhead align=center>Uppfyllt?</td><td class=colhead align=center>Uppfyllt af</td><td class=colhead align=center>Atkvæði</td><td class=colhead align=center>Aths.</td><td class=colhead align=center>Eyða</td></tr>\n"); for ($i = 0; $i < $num; ++$i) {



$arr = mysql_fetch_assoc($res);

if ($arr["downloaded"] > 0)
{
$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
$ratio = "<font color=" . get_ratio_color($ratio) . "><b>$ratio</b></font>";
}
else if ($arr["uploaded"] > 0)
$ratio = "Inf.";
else
$ratio = "---";


$res2 = mysql_query("SELECT username from users where id=" . $arr['filledby']);
$arr2 = mysql_fetch_assoc($res2);
if (isset($arr2['username']))
$filledby = $arr2['username'];
else
$filledby = ' ';
$addedby = '<td style="padding: 0px" align="center"><a href="userdetails.php?id='.$arr['userid'].'"><b>'.$arr['username'].' ('.$ratio.')</b></a></td>';
$filled = $arr['filled'];
if ($arr['filledby'] > '0')
	$filled = '<a href="'.$filled.'"><font color="green"><b>Já</b></font></a>';
else
	$filled = '<a href="reqdetails.php?id='.$arr['id'].'"><font color="red"><b>Nei</b></font></a>';

echo '<tr><td align="left"><a href="reqdetails.php?id='.$arr['id'].'"><b>';
if(!empty($arr['request']))
	echo $arr['request'];
else
	echo '[Án titils]';
echo '</b></a></td>
<td align="center">'.$arr['cat'].'</td>
<td align="center">'.$arr['added'].'</td>'.$addedby.'
<td>'.$filled.'</td>
<td><a href="userdetails.php?id='.$arr['filledby'].'"><b>'.$arr2['username'].'</b></a></td>
<td><a href="votesview.php?requestid='.$arr['id'].'"><b>'.$arr['votes'].'</b></a></td>
<td align="center"><a href="reqdetails.php?id='.$arr['id'].'"><b>'.$arr['comments'].'</b></td>
<td><input type="checkbox" name="delreq[]" value="'.$arr['id'].'" /></td></tr>'."\n";
}

echo '</table>'."\n";

echo '<p><input type="submit" value="Eyða"></p>';
echo '</form>';

echo $pagerbottom;

stdfoot();
die;

?>
