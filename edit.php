<?

require_once("include/bittorrent.php");

hit_start();

if (!mkglobal("id"))
	die();

$id = 0 + $id;
if (!$id)
	die();

dbconn();

hit_count();

loggedinorreturn();

$res = mysql_query("SELECT * FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
	die();

stdhead("Breyta torrent \"" . $row["name"] . "\"");

if (!isset($CURUSER) || ($CURUSER["id"] != $row["owner"] && get_user_class() < UC_MODERATOR)) {
	print("<h1>Þú getur ekki breytt þessum torrent</h1>\n");
	print("<p>Þú ert ekki eigandi þessa torrents, eða þú ert ekki <a href=\"login.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;nowarn=1\">skráður inn</a>.</p>\n");
}
else {
	print("<form method=post action=takeedit.php enctype=multipart/form-data>\n");
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	if (isset($_GET["returnto"]))
		print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
	print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"10\">\n");
	tr("Nafn Torrents", "<input type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row["name"]) . "\" size=\"80\" />", 1);
	tr("NFO skrá", "<input type=radio name=nfoaction value='keep' checked>Halda núverandi<br>".
	"<input type=radio name=nfoaction value='update'>Uppfæra:<br><input type=file name=nfo size=80>", 1);
if ((strpos($row["ori_descr"], "<") === false) || (strpos($row["ori_descr"], "&lt;") !== false))
  $c = "";
else
  $c = " checked";
	tr("Upplýsingar", "<textarea name=\"descr\" rows=\"10\" cols=\"80\">" . 
htmlspecialchars($row["ori_descr"]) . "</textarea><br>(HTML ekki leyft. <a href=tags.php>Smelltu hér</a> til að fá upplýsingar um leyfileg snið.)", 1);

	$s = "<select name=\"type\">\n";

	$cats = genrelist();
	foreach ($cats as $subrow) {
		$s .= "<option value=\"" . $subrow["id"] . "\"";
		if ($subrow["id"] == $row["category"])
			$s .= " selected=\"selected\"";
		$s .= ">" . htmlspecialchars($subrow["name"]) . "</option>\n";
	}

	$s .= "</select>\n";
	if($row['anonymous'] === '1')
		tr("Nafnlaust torrent",'<input name="anonymous" type="checkbox" value="1" checked="checked"> Hakaðu við ef torrentið á að vera nafnlaust',1);
	elseif($row['anonymous'] !== '1' && $CURUSER['class'] >= UC_GOOD_USER)
		tr("Nafnlaust torrent",'<input name="anonymous" type="checkbox" value="1"> Hakaðu við ef torrentið á að vera nafnlaust',1);
	if($row['scene'] == 'y')
		tr("Scene útgáfa",'<input name="scene" type="checkbox" value="y" checked="checked">Scene útgáfa',1);
	else
		tr("Scene útgáfa",'<input name="scene" type="checkbox" value="y">Scene útgáfa',1);
	if($row['gamalt'] == 2)
	tr("Aldur","<input type=radio name=gamalt checked value=no>Nýtt (Gefið út innan 14 daga)<br><input type=radio name=gamalt value=yes>Gamalt (Eldra en 14 daga)",1);
	else
	tr("Aldur","<input type=radio name=gamalt value=no>Nýtt (Gefið út innan 14 daga)<br><input type=radio name=gamalt checked value=yes>Gamalt (Eldra en 14 daga)",1);
	tr("Flokkur", $s, 1);
	tr("Sýnilegt", "<input type=\"checkbox\" name=\"visible\"" . (($row["visible"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" /> Sýnilegt á aðalsíðu<br /><table border=0 cellspacing=0 cellpadding=0 width=420><tr><td class=embedded>Athugaðu að torrentið verður sjálfkrafa sýnilegt leið og einhver byrjar að deila (seeda) því, og verður sjálfkrafa ósýnilegt (dautt) þegar enginn hefur verið að deila því um tíma. Notið þetta bara til að flýta þeirri aðgerð. Athugið einnig er hægt að leita að ósýnilegum (dauð) torrentum, það er bara ekki grunn stilling.</td></tr></table>", 1);
	
	if ($CURUSER["class"] >= UC_MODERATOR) {
		tr("Bannað", "<input type=\"checkbox\" name=\"banned\"" . (($row["banned"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" /> Já", 1);
		tr("Sprengt", "<input type=\"checkbox\" name=\"nuked\"" . (($row["nuked"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" /> Já", 1);
		tr("Ástæða sprengingar", "<input type=\"text\" name=\"nukedr\" value=\"" . htmlspecialchars($row["nukedr"]) . "\" size=\"80\" />", 1);
	}
	print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value='Staðfesta' style='height: 25px; width: 100px'> <input type=reset value='Hætta við' style='height: 25px; width: 100px'></td></tr>\n");
	print("</table>\n");
	print("</form>\n");
	print("<p>\n");
	print("<form method=\"post\" action=\"delete.php\">\n");
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	if (isset($_GET["returnto"]))
		print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
	$t1 = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - (60*60*3))));
	$t2 = str_replace(array(' ',':','-'),'',$row['added']);
	if($t2>$t1 || $CURUSER['class'] >= UC_MODERATOR)
		echo 'Eyða torrent skrá. Ástæða: <input type="text" size="40" name="reason"> <input type="submit" value="Eyða" style="height: 25px">'."\n";
	else
		echo 'Þú getur ekki eytt torrent færslu sem er eldri en 3ja klst.'."\n";
	print("</form>\n");
	print("</p>\n");
}

stdfoot();

hit_end();

?>
