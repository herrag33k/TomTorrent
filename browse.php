<?php
$s_array = get_defined_vars();
$s_array2 = array_keys($s_array['_GET']);
$s_catz = '&';
for($ai=0;$ai <= count($s_array2);$ai++) {
	if(is_numeric(ltrim($s_array2[$ai],'c')))
		$s_catz .= $s_array2[$ai].'=1&';
}
$_REQUEST['s_catz'] = rtrim($s_catz,'&');

require_once("include/bittorrent.php");
hit_start();
dbconn(false);

loggedinorreturn();
hit_count();
dbconn();
?>
<?
ob_start("ob_gzhandler");


hit_start();
dbconn(false);

loggedinorreturn();
hit_count();

$cats = genrelist();

$searchstr = unesc($_GET['search']);
$cleansearchstr = searchfield($searchstr);
if (empty($cleansearchstr))
	unset($cleansearchstr);

if(isset($_GET["sort"]))
{
  $order = mysql_real_escape_string($_GET['sort']);
  $scending = $_GET['d'];
    if($scending != 'DESC' && $scending != 'ASC')
      die('Máttir reyna / Nice try');
  $orderby = "ORDER BY $order $scending";
} else {
	$orderby = "ORDER BY torrents.id DESC";
}
$addparam = "";
$wherea = array();
$wherecatina = array();

if ($_GET["incldead"] == 1)
{
	$addparam .= "incldead=1&amp;";
	if (!isset($CURUSER) || get_user_class < UC_ADMINISTRATOR)
		$wherea[] = "banned = 'no'";
} else
	if ($_GET["incldead"] == 2)
{
	$addparam .= "incldead=2&amp;";
		$wherea[] = "visible = 'no'";
}
	else
		$wherea[] = "visible = 'yes'";
		

	if ($_GET["hideold"] == 1)
{
	$addparam .= "hideold=1&amp;";
		$wherea[] = "gamalt = '2'";
}
	elseif($_GET["hideold"] == 2)
	{
	$addparam .= "hideold=2&amp;";
		$wherea[] = "gamalt = '1'";
	}

	if ($_GET["onlyscene"] == 'y')
{
	$addparam .= "onlyscene&amp;";
		$wherea[] = "scene = 'y'";
}

$category = $_GET["cat"];

if (!$_GET && $CURUSER["notifs"])
{
	$all = True;
 	foreach ($cats as $cat)
	{
		$all &= $cat[id];
	  if (strpos($CURUSER["notifs"], "[cat" . $cat[id] . "]") !== False)
	  {
	    $wherecatina[] = $cat[id];
	    $addparam .= 'c'.$cat[id].'=1&amp;';
	  }
	}
}
elseif ($category)
{
	if (!is_valid_id($category))
		stderr("Villa", "Rangur Flokkur, ID $category.");
	$wherecatina[] = $category;
	$addparam .= "cat=$category&amp;";
}
else
{
	$all = True;
	foreach ($cats as $cat)
	{
		$all &= $_GET["c$cat[id]"];
	  if ($_GET["c$cat[id]"])
	  {
	    $wherecatina[] = $cat[id];
	    $addparam .= "c$cat[id]=1&amp;";
	  }
	}
}

if ($all)
{
	$wherecatina = array();
  $addparam = "";
}

if (count($wherecatina) > 1)
	$wherecatin = implode(",",$wherecatina);
elseif (count($wherecatina) == 1)
	$wherea[] = "category = $wherecatina[0]";

$wherebase = $wherea;

if (isset($cleansearchstr))
{
	$wherea[] = 'MATCH (torrents.name, torrents.descr, ori_descr) AGAINST (' . sqlesc($searchstr).' IN BOOLEAN MODE)';
	//$wherea[] = "0";
	$addparam .= "search=" . urlencode($searchstr) . "&amp;";
	//$orderby = "";
}

$where = implode(" AND ", $wherea);
if ($wherecatin)
	$where .= ($where ? " AND " : "") . "category IN(" . $wherecatin . ")";

if ($where != "")
	$where = "WHERE $where";

$res = mysql_query("SELECT COUNT(*) FROM torrents $where") or die(mysql_error());
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count && isset($cleansearchstr)) {
	$wherea = $wherebase;
	$orderby = "ORDER BY id DESC";
	$searcha = explode(" ", $cleansearchstr);
	$sc = 0;
	foreach ($searcha as $searchss) {
		if (strlen($searchss) <= 1)
			continue;
		$sc++;
		if ($sc > 5)
			break;
		$ssa = array();
		foreach (array("search_text", "ori_descr") as $sss)
			$ssa[] = "$sss LIKE '%" . sqlwildcardesc($searchss) . "%'";
		$wherea[] = "(" . implode(" OR ", $ssa) . ")";
	}
	if ($sc) {
		$where = implode(" AND ", $wherea);
		if ($where != "")
			$where = "WHERE $where";
		$res = mysql_query("SELECT COUNT(*) FROM torrents $where");
		$row = mysql_fetch_array($res);
		$count = $row[0];
	}
}

$torrentsperpage = $CURUSER["torrentsperpage"];
if (!$torrentsperpage)
	$torrentsperpage = 15;

if ($count)
{
	list($pagertop, $pagerbottom, $limit) = pager($torrentsperpage, $count, "browse.php?" . $addparam);
	$query = "SELECT torrents.id, torrents.nuked, torrents.nukedr, torrents.category, torrents.leechers, 
	torrents.seeders, torrents.name, torrents.times_completed, torrents.size, torrents.added, 	
	torrents.comments, torrents.numfiles, torrents.filename, torrents.reviewed, torrents.anonymous, torrents.owner, 
	IF(torrents.nfo <> '', 1, 0) as nfoav," . 
	"IF(torrents.numratings < $minvotes, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, categories.name AS cat_name, categories.image AS cat_pic, users.username FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id $where $orderby $limit";
	"categories.name AS cat_name, categories.image AS cat_pic, users.username FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id $where $orderby $limit";
//die($query);
	$res = mysql_query($query) or die(mysql_error());
}
else
	unset($res);
if (isset($cleansearchstr))
	stdhead("Niðurstöður leitar að \"$searchstr\"");
else
	stdhead();
?>

<STYLE TYPE="text/css" MEDIA=screen>

  a.catlink:link, a.catlink:visited{
		text-decoration: none;
	}

	a.catlink:hover {
		color: #A83838;
	}

</STYLE>
<table width=750 class=main border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
<form method="get" action=browse.php>
<p align="center">
Leit:
<input type="text" name="search" size="40" value="<?= htmlspecialchars($searchstr) ?>" />
í
<select name="cat">
<option value="0">(öllum flokkum)</option>
<?


$cats = genrelist();
$catdropdown = "";
foreach ($cats as $cat) {
    $catdropdown .= "<option value=\"" . $cat["id"] . "\"";
    if ($cat["id"] == $_GET["cat"])
        $catdropdown .= " selected=\"selected\"";
    $catdropdown .= ">" . htmlspecialchars($cat["name"]) . "</option>\n";
}

$deadchkbox = "<input type=\"checkbox\" name=\"incldead\" value=\"1\"";
if ($_GET["incldead"])
    $deadchkbox .= " checked=\"checked\"";
$deadchkbox .= " /> Dauðir torrent meðtaldir\n";

?>
<?= $catdropdown ?>
</select>
<?= $deadchkbox ?>
<input type="submit" value="Leita" />
</p>
</form>
</td></tr></table>
<form method="get" action="browse.php">
<table>
<tr>
<td width=200>
			<center><select name=hideold>
<option value="0">Nýtt & gamalt</option>
<option value="1"<? print($_GET["hideold"] == 1 ? " selected" : ""); ?>>Nýtt</option>
<option value="2"<? print($_GET["hideold"] == 2 ? " selected" : ""); ?>>Gamalt</option>
			</select>
  		<input type="submit" class=btn value="Sýna"/></center>
</td>
</tr>
<tr><td>
<input type="checkbox" name="onlyscene" value="y"<? print($_GET["onlyscene"] == y ? " checked" 
: ""); 
?>>Scene útgáfur</option>
  		<input type="submit" class=btn value="Framkvæma"/>
</td></tr>
</table>
<table class=bottom>
<tr>
<td class=bottom>
	<table class=bottom>
	<tr>
<?
$i = 0;
foreach ($cats as $cat)
{
	print(($i && $i % 6 == 0) ? "</tr><tr>" : "");
	print("<td class=bottom style=\"padding-bottom: 2px;padding-left: 5px\"><div align=right><a class=catlink href=browse.php?c$cat[id]=1>" . htmlspecialchars($cat[name]) . "</a><input name=c$cat[id] type=\"checkbox\" " . (in_array($cat[id],$wherecatina) ? "checked " : "") . "value=1></div></td>\n");
	$i++;
}
?>
	</tr>
	</table>
</td>

<td class=bottom>
<table class=main>
	<tr>
		<td class=bottom style="padding: 1px;padding-left: 10px">
			<select name=incldead>
<option value="0">Aðeins Virkir</option>
<option value="1"<? print($_GET["incldead"] == 1 ? " selected" : ""); ?>>Allir</option>
<option value="2"<? print($_GET["incldead"] == 2 ? " selected" : ""); ?>>Aðeins Dauðir</option>
			</select>
  	</td>
  </tr>
  <tr>
  	<td class=bottom style="padding: 1px;padding-left: 10px">
  	<div align=center>
  		<input type="submit" class=btn value="Sækja!"/>
  	</div>
  	</td>
  </tr>
  </table>
</td>
</tr>
</table>
</form>
<?
if($_GET['c5'] == '1')
	echo '<br /><br /><span style="font-size:small;font-weight:bold">Viðvörun: Efni í þessum niðurstöðum gæti verið eingöngu fyrir einstaklinga 18 ára eða eldri sem telja sig ráða við að horfa á fólk stunda kynlíf eða tengdar athafnir!</span>';
if (isset($cleansearchstr))
print("<h2>Niðurstöður leitar að \"" . htmlspecialchars($searchstr) . "\"</h2>\n");

if ($count) {
	print($pagertop);

	torrenttable($res);

	print($pagerbottom);
}
else {
	if (isset($cleansearchstr)) {
		print("<h2>Ekkert fannst!</h2>\n");
		print("<p>Reyndu aftur með breyttum leitarstreng.</p>\n");
	}
	else {
		print("<h2>Ekkert hér!</h2>\n");
		print("<p>Reyndu aftur seinna.</p>\n");
	}
}

stdfoot();

hit_end();
?>
