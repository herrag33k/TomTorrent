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
	print("<h1>�� getur ekki breytt �essum torrent</h1>\n");
	print("<p>�� ert ekki eigandi �essa torrents, e�a �� ert ekki <a href=\"login.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;nowarn=1\">skr��ur inn</a>.</p>\n");
}
else {
	print("<form method=post action=takeedit.php enctype=multipart/form-data>\n");
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	if (isset($_GET["returnto"]))
		print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
	print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"10\">\n");
	tr("Nafn Torrents", "<input type=\"text\" name=\"name\" value=\"" . htmlspecialchars($row["name"]) . "\" size=\"80\" />", 1);
	tr("NFO skr�", "<input type=radio name=nfoaction value='keep' checked>Halda n�verandi<br>".
	"<input type=radio name=nfoaction value='update'>Uppf�ra:<br><input type=file name=nfo size=80>", 1);
if ((strpos($row["ori_descr"], "<") === false) || (strpos($row["ori_descr"], "&lt;") !== false))
  $c = "";
else
  $c = " checked";
	tr("Uppl�singar", "<textarea name=\"descr\" rows=\"10\" cols=\"80\">" . 
htmlspecialchars($row["ori_descr"]) . "</textarea><br>(HTML ekki leyft. <a href=tags.php>Smelltu h�r</a> til a� f� uppl�singar um leyfileg sni�.)", 1);

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
		tr("Nafnlaust torrent",'<input name="anonymous" type="checkbox" value="1" checked="checked"> Haka�u vi� ef torrenti� � a� vera nafnlaust',1);
	elseif($row['anonymous'] !== '1' && $CURUSER['class'] >= UC_GOOD_USER)
		tr("Nafnlaust torrent",'<input name="anonymous" type="checkbox" value="1"> Haka�u vi� ef torrenti� � a� vera nafnlaust',1);
	if($row['scene'] == 'y')
		tr("Scene �tg�fa",'<input name="scene" type="checkbox" value="y" checked="checked">Scene �tg�fa',1);
	else
		tr("Scene �tg�fa",'<input name="scene" type="checkbox" value="y">Scene �tg�fa',1);
	if($row['gamalt'] == 2)
	tr("Aldur","<input type=radio name=gamalt checked value=no>N�tt (Gefi� �t innan 14 daga)<br><input type=radio name=gamalt value=yes>Gamalt (Eldra en 14 daga)",1);
	else
	tr("Aldur","<input type=radio name=gamalt value=no>N�tt (Gefi� �t innan 14 daga)<br><input type=radio name=gamalt checked value=yes>Gamalt (Eldra en 14 daga)",1);
	tr("Flokkur", $s, 1);
	tr("S�nilegt", "<input type=\"checkbox\" name=\"visible\"" . (($row["visible"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" /> S�nilegt � a�als��u<br /><table border=0 cellspacing=0 cellpadding=0 width=420><tr><td class=embedded>Athuga�u a� torrenti� ver�ur sj�lfkrafa s�nilegt lei� og einhver byrjar a� deila (seeda) �v�, og ver�ur sj�lfkrafa �s�nilegt (dautt) �egar enginn hefur veri� a� deila �v� um t�ma. Noti� �etta bara til a� fl�ta �eirri a�ger�. Athugi� einnig er h�gt a� leita a� �s�nilegum (dau�) torrentum, �a� er bara ekki grunn stilling.</td></tr></table>", 1);
	
	if ($CURUSER["class"] >= UC_MODERATOR) {
		tr("Banna�", "<input type=\"checkbox\" name=\"banned\"" . (($row["banned"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" /> J�", 1);
		tr("Sprengt", "<input type=\"checkbox\" name=\"nuked\"" . (($row["nuked"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" /> J�", 1);
		tr("�st��a sprengingar", "<input type=\"text\" name=\"nukedr\" value=\"" . htmlspecialchars($row["nukedr"]) . "\" size=\"80\" />", 1);
	}
	print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value='Sta�festa' style='height: 25px; width: 100px'> <input type=reset value='H�tta vi�' style='height: 25px; width: 100px'></td></tr>\n");
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
		echo 'Ey�a torrent skr�. �st��a: <input type="text" size="40" name="reason"> <input type="submit" value="Ey�a" style="height: 25px">'."\n";
	else
		echo '�� getur ekki eytt torrent f�rslu sem er eldri en 3ja klst.'."\n";
	print("</form>\n");
	print("</p>\n");
}

stdfoot();

hit_end();

?>
