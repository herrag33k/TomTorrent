<?
ob_start("ob_gzhandler");
require "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead("Beiðnissíða");

if (get_user_class() < UC_GOOD_USER)
{
	print("<h1>Fyrirgefðu</h1><p>Þú verður að gegna stöðunni virkur notandi eða betri, sjáðu <a href=/faq.php#23><b>SOS</b></a> fyrir upplýsingar um stöður. Að gefnu tilefni er bannað að leggja inn eftirspurnir annars staðar á Istorrent vefnum.</p>");
	die();
}
if(requests_free($CURUSER['id']) > '0') {
	echo '<h1>Leggja inn beiðni</h1>';
	echo 'Til að skoða beiðnir, <a href="/viewrequests.php">klikkaðu hér</a><br />'."\n";

	/*

	$res = mysql_query("SELECT users.username, requests.id, requests.userid, requests.request, requests.added, categories.name as cat FROM requests inner join categories on requests.cat = categories.id inner join users on requests.userid = users.id order by requests.id desc LIMIT 10") or sqlerr();
	$num = mysql_num_rows($res);

	print("<table border=1 width=800 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead align=left width=400>Beðið um</td><td class=colhead align=center width=100>Flokkur</td><td class=colhead align=center>Bætt við</td><td class=colhead align=center width=100>Beðið af</td></tr>\n");
	for ($i = 0; $i < $num; ++$i)
	{
		$arr = mysql_fetch_assoc($res);
	{

	$addedby = "<td style='padding: 0px' align=center><b><a href=userdetails.php?id=$arr[userid]>$arr[username]</a></b></td>";
	}

	print("<tr><td align=left><a href=reqdetails.php?id=$arr[id]><b>$arr[request]</b></a></td><td align=left>$arr[cat]</td>" . "<td align=center>$arr[added]</td>"."$addedby</tr>\n");
	}
	print("<tr><td align=center colspan=4><form method=\"get\" action=viewrequests.php><input type=\"submit\" value=\"Sýna allt\" style='height: 22px' /></form></td></tr>\n");
	print("</table>\n");

	*/

	print("<br>\n");

	$where = "WHERE userid = " . $CURUSER["id"] . "";
	$res2 = mysql_query("SELECT * FROM requests $where") or sqlerr();
	$num2 = mysql_num_rows($res2);

	/*print("<table border=1 width=800 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead align=left width=400>Þínar beiðnir</td><td class=colhead align=center>Flokkur</td><td class=colhead align=center>Bætt við</td></tr>\n");
	for ($i = 0; $i < $num; ++$i)
	{
	$arr = mysql_fetch_assoc($res2);

	print("<tr><td align=left><b>$arr[request]</b></td><td align=left>$arr[cat]</td>" .
	"<td align=center>$arr[added]</td>".
	"</tr>\n");
	}
	print("</table>");

	print("<br>\n");
	*/
?>



	<table border=1 width=800 cellspacing=0 cellpadding=5>
	<tr><td class=colhead align=left>Leita að torrentum (t.d. að torrentinu áður en þú leggur inn beiðni)</td></tr>
	<tr><td align=left><form method="get" action=browse.php>
	<input type="text" name="search" size="40" value="<?= htmlspecialchars($searchstr) ?>" />in
	<select name="cat">
	<option value="0">(allir flokkar)</option>
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
	$deadchkbox .= " /> innihalda dauð torrent\n";

?>
	<?= $catdropdown ?>
	</select>
	<?= $deadchkbox ?>
	<input type="submit" value="Leita!" style='height: 18px' />
	</form>
	</td></tr></table>

	<? print("<br>\n");

	print("<form method=post action=takerequest.php><a name=add id=add></a>\n");
	print("<table border=1 width=800 cellspacing=0 cellpadding=5>\n");
	print("<tr><td align=center><b>Heiti skráar eða útgáfu: </b><input type=text size=40 name=requestartist>");
	/*print("<b> Titill: </b><input type=text size=40 name=requesttitle>");*/
?>

	<select name="category">
	<option value="0">(Velja flokk)</option>
<?

	$res2 = mysql_query("SELECT id, name FROM categories order by name");
	$num = mysql_num_rows($res2);
	$catdropdown2 = "";
	for ($i = 0; $i < $num; ++$i)
	{
		$cats2 = mysql_fetch_assoc($res2);
		$catdropdown2 .= "<option value=\"" . $cats2["id"] . "\"";
		$catdropdown2 .= ">" . htmlspecialchars($cats2["name"]) . "</option>\n";
	}

?>
<?= $catdropdown2 ?>
	</select>

	<? print("<br>\n");

	echo '<tr><td align="center">Nánari upplýsingar (ekki skylda)<br><textarea name="descr" rows="5" cols="100"></textarea>'."\n";
	echo '<tr><td align="center">Áður en þú ýtir á takkann, <a href="/faq.php#76" target="_blank">athugaðu hvað SOS nefnir um að leggja inn eftirspurn</a> <input type="submit" value="Framkvæma!" style="height: 22px">'."\n";
	echo '</form>'."\n";
	echo '</table>'."\n";
} else {
	echo 'Þú færð eina eftirspurn fyrir hver 10 gígabæti sem þú hefur deilt. Þú átt enga eftirspurn inni.<br />';
	echo 'Nánari upplýsingar er hægt að fá í <a href="/faq.php#84">SOS færslu 84</a>';
}
stdfoot();
die;

?>
