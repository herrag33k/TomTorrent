<?

ob_start("ob_gzhandler");

require_once("include/bittorrent.php");

hit_start();

$verifystring = verifystring($_GET['id'],'num');
if($verifystring !== TRUE)
        die($verifystring);

function getagent($httpagent)
{
		return "$httpagent";
}

function dltable($name, $arr, $torrent)
{

	global $CURUSER;
	$s = "<b>" . count($arr) . " $name</b>\n";
	if (!count($arr))
		return $s;
	$s .= "\n";
	$s .= "<table width=100% class=main border=1 cellspacing=0 cellpadding=5>\n";
	$s .= "<tr><td class=colhead>Notandi</td>" .
          "<td class=colhead align=center>Tengjanlegur</td>".
          "<td class=colhead align=right>Sent</td>".
          "<td class=colhead align=right>Hra�i</td>".
          "<td class=colhead align=right>S�tt</td>" .
          "<td class=colhead align=right>Hra�i</td>" .
          "<td class=colhead align=right>Hlutfall</td>" .
          "<td class=colhead align=right>Kominn me�</td>" .
          "<td class=colhead align=right>Tengdur</td>" .
          "<td class=colhead align=right>I�julaus</td>" .
          "<td class=colhead align=left>Forrit</td></tr>\n";
	$now = time();
	$moderator = (isset($CURUSER) && get_user_class() >= UC_MODERATOR);
$mod = get_user_class() >= UC_MODERATOR;
	foreach ($arr as $e) {


                // user/ip/port
                // check if anyone has this ip
                ($unr = mysql_query("SELECT username, privacy FROM users WHERE id=$e[userid] ORDER BY last_access DESC LIMIT 1")) or die;
                $una = mysql_fetch_array($unr);
				if ($una["privacy"] == "strong") continue;
		$s .= "<tr>\n";
                if ($una["username"])
                  $s .= "<td><a href=userdetails.php?id=$e[userid]><b>$una[username]</b></a></td>\n";
                else
                  $s .= "<td>" . ($mod ? $e["ip"] : preg_replace('/\.\d+$/', ".xxx", $e["ip"])) . "</td>\n";
		$secs = max(1, ($now - $e["st"]) - ($now - $e["la"]));
        $s .= "<td align=center>" . ($e[connectable] == "yes" ? "J�" : "Nei") . "</td>\n";
		$s .= "<td align=right>" . mksize($e["uploaded"]) . "</td>\n";
		$s .= "<td align=right><nobr>" . mksize($e["uploaded"] / $secs) . "/s</nobr></td>\n";
		$s .= "<td align=right>" . mksize($e["downloaded"]) . "</td>\n";
		if ($e["seeder"] == "no")
			$s .= "<td align=right><nobr>" . mksize($e["downloaded"] / $secs) . "/s</nobr></td>\n";
		else
			$s .= "<td align=right><nobr>" . mksize($e["downloaded"] / max(1, $e["finishedat"] - $e[st])) . "/s</nobr></td>\n";
                if ($e["downloaded"])
                {
                  $ratio = $e["uploaded"] / $e["downloaded"];
                  if ($ratio < 0.1)
                    $s .= "<td align=\"right\"><font color=#ff0000>" . number_format($ratio, 2) . "</font></td>\n";
                  else if ($ratio < 0.2)
                    $s .= "<td align=\"right\"><font color=#ee0000>" . number_format($ratio, 2) . "</font></td>\n";
                  else if ($ratio < 0.3)
                    $s .= "<td align=\"right\"><font color=#dd0000>" . number_format($ratio, 2) . "</font></td>\n";
                  else if ($ratio < 0.4)
                    $s .= "<td align=\"right\"><font color=#cc0000>" . number_format($ratio, 2) . "</font></td>\n";
                  else if ($ratio < 0.5)
                    $s .= "<td align=\"right\"><font color=#bb0000>" . number_format($ratio, 2) . "</font></td>\n";
                  else if ($ratio < 0.6)
                    $s .= "<td align=\"right\"><font color=#aa0000>" . number_format($ratio, 2) . "</font></td>\n";
                  else if ($ratio < 0.7)
                    $s .= "<td align=\"right\"><font color=#990000>" . number_format($ratio, 2) . "</font></td>\n";
                  else if ($ratio < 0.8)
                    $s .= "<td align=\"right\"><font color=#880000>" . number_format($ratio, 2) . "</font></td>\n";
                  else if ($ratio < 0.9)
                    $s .= "<td align=\"right\"><font color=#770000>" . number_format($ratio, 2) . "</font></td>\n";
                  else if ($ratio < 1)
                    $s .= "<td align=\"right\"><font color=#660000>" . number_format($ratio, 2) . "</font></td>\n";
                  else
                    $s .= "<td align=\"right\">" . number_format($ratio, 2) . "</td>\n";
                }
                else
                  if ($e["uploaded"])
                    $s .= "<td align=right>Inf.</td>\n";
                  else
                    $s .= "<td align=right>---</td>\n";
		$s .= "<td align=right>" . sprintf("%.2f%%", 100 * (1 - ($e["to_go"] / $torrent["size"]))) . "</td>\n";
		$s .= "<td align=right>" . mkprettytime($now - $e["st"]) . "</td>\n";
		$s .= "<td align=right>" . mkprettytime($now - $e["la"]) . "</td>\n";
		$s .= "<td align=left>" . htmlspecialchars(getagent($e["agent"])) . "</td>\n";
		$s .= "</tr>\n";
	}
	$s .= "</table>\n";
	return $s;
}

dbconn(false);

loggedinorreturn();
if($CURUSER['class'] >= UC_MODERATOR && !empty($_GET['yfirfarid'])) {
	$sql = 'UPDATE torrents SET reviewed='.$CURUSER['id'].' WHERE id='.$_GET['id'];
	mysql_query($sql);
	$header = 'Refresh: 0; url='.$BASEURL.'/details.php?id='.$_GET['id'];
	header($header);
	die();
}


hit_count();
$id = $_GET["id"];
$id = 0 + $id;
if (!isset($id) || !$id)
	die();

$res = mysql_query("SELECT torrents.seeders, torrents.banned, torrents.leechers, torrents.info_hash, torrents.filename, LENGTH(torrents.nfo) AS nfosz, UNIX_TIMESTAMP() - UNIX_TIMESTAMP(torrents.last_action) AS lastseed, torrents.numratings, torrents.name, IF(torrents.numratings < $minvotes, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.owner, torrents.anonymous, torrents.save_as, torrents.descr, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.type, torrents.numfiles, torrents.reviewed, categories.name AS cat_name, users.username FROM torrents,categories,users WHERE torrents.category = categories.id AND  torrents.owner = users.id AND torrents.id = $id")
	or sqlerr();
$row = mysql_fetch_array($res);

$owned = $moderator = 0;
	if (get_user_class() >= UC_MODERATOR)
		$owned = $moderator = 1;
	elseif ($CURUSER["id"] == $row["owner"])
		$owned = 1;
//}

if (!$row || ($row["banned"] == "yes" && !$moderator))
	stderr("Villa", "Ekkert torrent me� au�kenni� $id.");
else {
	if ($_GET["hit"]) {
		mysql_query("UPDATE torrents SET views = views + 1 WHERE id = $id");
		if ($_GET["tocomm"])
			header("Location: $BASEURL/details.php?id=$id&page=0#startcomments");
		elseif ($_GET["filelist"])
			header("Location: $BASEURL/details.php?id=$id&filelist=1#filelist");
		elseif ($_GET["toseeders"])
			header("Location: $BASEURL/details.php?id=$id&dllist=1#seeders");
		elseif ($_GET["todlers"])
			header("Location: $BASEURL/details.php?id=$id&dllist=1#leechers");
		else
			header("Location: $BASEURL/details.php?id=$id");
		hit_end();
		exit();
	}

	if (!isset($_GET["page"])) {
		stdhead("Uppl�singar um torrenti� \"" . $row["name"] . "\"");

		if ($CURUSER["id"] == $row["owner"] || get_user_class() >= UC_MODERATOR)
			$owned = 1;
		else
			$owned = 0;

		$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

		if ($_GET["uploaded"]) {
			print("<h2>A�ger� T�kst!</h2>\n");
			print("<p>�� getur byrja� a� deila n�na. <b>ATH</b> torrenti� mun ekki sj�st � s��unni fyrr en �� gerir �a�!</p><p><font color=red><b>ATH! �llum torrent skr�m er breytt eftir a� �eim er uploada�, �ess vegna �arftu a� s�kja skr�nna h��an og deila henni � sta� �eirrar torrent skr�r sem �� b�r� til.</b></font>\n");
		}
		elseif ($_GET["edited"]) {
			print("<h2>Breyting t�kst!</h2>\n");
			if (isset($_GET["returnto"]))
				print("<p><b>Til baka <a href=\"" . htmlspecialchars($_GET["returnto"]) . "\">�a�an sem �� komst</a>.</b></p>\n");
		}
		elseif (isset($_GET["searched"])) {
			print("<h2>Leit ��n a� \"" . htmlspecialchars($_GET["searched"]) . "\" gaf eina �tkomu:</h2>\n");
		}
		elseif ($_GET["rated"])
			print("<h2>Einkunn gefin!</h2>\n");

$s=$row["name"];
		print("<h1>$s</h1>\n");
		if($CURUSER['class'] >= UC_MODERATOR && $row['reviewed'] < '1') {
			echo '<a href="/details.php?id='.$_GET['id'].'&amp;yfirfarid=1">Merkja torrent sem yfirfari�</a><br />';
			echo 'Eyddu torrentinu ef �a� uppfyllir ekki reglur Istorrent.<br /><br />';
		} elseif($CURUSER['class'] >= UC_MODERATOR)
			echo '<b>�etta torrent hefur veri� yfirfari� af stj�rnanda</b><br /><br />';

		if($row['cat_name'] == 'XXX (18+)') {
		        echo '<p><span style="font-size:small;font-weight:bold">Vi�v�run: Efni � �essari skr� er eing�ngu fyrir einstaklinga 18 �ra e�a eldri sem telja sig r��a vi� a� horfa � f�lk stunda kynl�f!</span></p>';
		}
                print("<table width=750 border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");

		$url = "edit.php?id=" . $row["id"];
		if (isset($_GET["returnto"])) {
			$addthis = "&amp;returnto=" . urlencode($_GET["returnto"]);
			$url .= $addthis;
			$keepget .= $addthis;
		}
		$editlink = "a href=\"$url\" class=\"sublink\"";

//		$s = "<b>" . htmlspecialchars($row["name"]) . "</b>";
//		if ($owned)
//			$s .= " $spacer<$editlink>[Edit torrent]</a>";
//		tr("Name", $s, 1);

		print("<tr><td class=rowhead width=1%>S�kja</td><td width=99% align=left><a class=\"index\" href=\"download.php/$id/" . rawurlencode($row["filename"]) . "\">" . htmlspecialchars($row["filename"]) . "</a>");

	$t1 = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400*2)));
        $t2 = str_replace(array(' ',':','-'),'',$row['added']);
	if($row['owner'] !== $CURUSER['id'] && $t2>$t1 && slots($CURUSER['id'],'free') < '1')
		echo '<br /><span style="color:red">�� hefur ekki n�gan h�lfafj�lda til a� hefja �etta ni�urhal. �� getur n�� � skr�na en �a� mun ekki byrja fyrr en �� hefur laust h�lf.</span>';
	if($t1>$t2)
		echo '<br /><span style="color:green">�etta torrent tekur ekki h�lf, nj�ttu vel.</span>';
	if($row['owner'] !== $CURUSER['id'] && find_unseeded($CURUSER['id'],'dl') === '1')
		echo '<br /><span style="color:red">�� ert ekki a� deila inn torrenti sem �� hefur sent inn, ��r mun ekki takast a� byrja � �essari skr� fyrr en �a� hefur veri� laga�.</span>';

		echo '</td></tr>';
//		tr("Downloads&nbsp;as", $row["save_as"]);

		function hex_esc($matches) {
			return sprintf("%02x", ord($matches[0]));
		}
		tr("md5 summa", preg_replace_callback('/./s', "hex_esc", hash_pad($row["info_hash"])));

		if (!empty($row["descr"]))
			tr("L�sing", str_replace(array("\n", "  "), array("<br>\n", "&nbsp; "), format_comment($row["descr"])), 1);
if ($row["nfosz"] > 0)
  print("<tr><td class=rowhead>NFO</td><td align=left><a href=viewnfo.php?id=$row[id]><b>Sko�a NFO</b></a> (" .
     mksize($row["nfosz"]) . ")</td></tr>\n");
		if ($row["visible"] == "no")
			tr("S�nilegt", "<b>nei</b> (dautt)", 1);
		if ($moderator)
			{
			if($row["banned"] == 'yes')
				$bannad = "J�";
			else
				$bannad = "Nei";
			tr("Banna�", $bannad);
			}

		if (isset($row["cat_name"]))
			tr("Flokkur", $row["cat_name"]);
		else
			tr("Flokkur", "(enginn valinn)");

		tr("Seinast deilt", "Seinasta hreyfing " . mkprettytime($row["lastseed"]) . " s��an");
		tr("St�r�",mksize($row["size"]) . " (" . number_format($row["size"]) . " b�ti)");

		$s = "";
		$s .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr><td valign=\"top\" class=embedded>";
		if (!isset($row["rating"])) {
			if ($minvotes > 1) {
				$s .= "Engin enn �� (�arf minnst $minvotes en er me� ";
				if ($row["numratings"])
					$s .= "a�eins " . $row["numratings"];
				else
					$s .= "engin";
				$s .= ")";
			}
			else
				$s .= "Engin einkunn gefin";
		}
		else {
			$rpic = ratingpic($row["rating"]);
			if (!isset($rpic))
				$s .= "invalid?";
			else
				$s .= "$rpic (" . $row["rating"] . " af 5 me� " . $row["numratings"] . " einkunnir gefnar)";
		}
		$s .= "\n";
		$s .= "</td><td class=embedded>$spacer</td><td valign=\"top\" class=embedded>";
		if (!isset($CURUSER))
			$s .= "(<a href=\"login.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;nowarn=1\">Skr��u �ig inn</a> til a� gefa einkunn)";
		else {
			$ratings = array(
					5 => "Ge�veikt!",
					4 => "Nokku� gott",
					3 => "Allt � lagi",
					2 => "Frekar slappt",
					1 => "Sorp!",
			);
			if (!$owned || $moderator) {
				$xres = mysql_query("SELECT rating FROM ratings WHERE torrent = $id AND user = " . $CURUSER["id"]);
				$xrow = mysql_fetch_array($xres);
				if ($xrow)
					$s .= "(�� gafst �essu torrent \"" . $xrow["rating"] . " - " . $ratings[$xrow["rating"]] . "\")";
				else {
					$s .= "<form method=\"post\" action=\"takerate.php\"><input type=\"hidden\" name=\"id\" value=\"$id\" />\n";
					$s .= "<select name=\"rating\">\n";
					$s .= "<option value=\"0\">(gefa einkunn)</option>\n";
					foreach ($ratings as $k => $v) {
						$s .= "<option value=\"$k\">$k - $v</option>\n";
					}
					$s .= "</select>\n";
					$s .= "<input type=\"submit\" value=\"Gefa!\" />";
					$s .= "</form>\n";
				}
			}
		}
		$s .= "</td></tr></table>";
		tr("Einkunn", $s, 1);



		tr("Sent inn", $row["added"]);
		tr("Flettingar", $row["views"]);
		tr("Sko�a�", $row["hits"]);
		tr("S�tt", $row["times_completed"] . " sinnum/sinni");

		$keepget = "";
		if($row['anonymous'] !== '1')
			$uprow = '<a href=userdetails.php?id=' . $row["owner"] . '><b>' . htmlspecialchars($row["username"]) . '</b></a>';
		elseif($CURUSER['class'] >= UC_MODERATOR)
			$uprow = '<a href=userdetails.php?id='.$row['owner'].'>'.$row['username'].'</a> - <b>Nafnleynd!</b> - <u>Tr�na�arm�l</u>';
		else
			$uprow = '<i>(Nafnleynd)</i>';
		if ($owned)
			$uprow .= " $spacer<$editlink><b>[Breyta]</b></a>";
		tr("Sent af", $uprow, 1);

		if ($row["type"] == "multi") {
			if (!$_GET["filelist"])
				tr("Fj�ldi skr�a<br /><a href=\"details.php?id=$id&amp;filelist=1$keepget#filelist\" class=\"sublink\">[S�na lista]</a>", $row["numfiles"] . " files", 1);
			else {
				tr("Fj�ldi skr�a", $row["numfiles"] . " files", 1);

				$s = "<table class=main border=\"1\" cellspacing=0 cellpadding=\"5\">\n";

				$subres = mysql_query("SELECT * FROM files WHERE torrent = $id ORDER BY id");
				$s.="<tr><td class=colhead>Sl��</td><td class=colhead align=right>St�r�</td></tr>\n";
				while ($subrow = mysql_fetch_array($subres)) {
					$s .= "<tr><td>" . $subrow["filename"] .
                            "</td><td align=\"right\">" . mksize($subrow["size"]) . "</td></tr>\n";
				}

				$s .= "</table>\n";
				tr("<a name=\"filelist\">Skr�arlisti</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[Fela lista]</a>", $s, 1);
			}
		}

		if (!$_GET["dllist"]) {
			/*
			$subres = mysql_query("SELECT seeder, COUNT(*) FROM peers WHERE torrent = $id GROUP BY seeder");
			$resarr = array(yes => 0, no => 0);
			$sum = 0;
			while ($subrow = mysql_fetch_array($subres)) {
				$resarr[$subrow[0]] = $subrow[1];
				$sum += $subrow[1];
			}
			tr("Peers<br /><a href=\"details.php?id=$id&amp;dllist=1$keepget#seeders\" class=\"sublink\">[See full list]</a>", $resarr["yes"] . " seeder(s), " . $resarr["no"] . " leecher(s) = $sum peer(s) total", 1);
			*/
			tr("Peers<br /><a href=\"details.php?id=$id&amp;dllist=1$keepget#seeders\" class=\"sublink\">[S�na lista]</a>", $row["seeders"] . " seeder(s), " . $row["leechers"] . " leecher(s) = " . ($row["seeders"] + $row["leechers"]) . " peer(s) total", 1);
		}
		else {
			if(get_user_class() < UC_MODERATOR) {
				tr('Deilendur','�essi m�guleiki er eing�ngu a�gengilegur stj�rnendum');
				tr('Skr�arsugur','�essi m�guleiki er eing�ngu a�gengilegur stj�rnendum');
			} else {
				$downloaders = array();
				$seeders = array();
				$subres = mysql_query("SELECT seeder, finishedat, ip, port, uploaded, downloaded, to_go, UNIX_TIMESTAMP(started) AS st, connectable, agent, UNIX_TIMESTAMP(last_action) AS la, userid FROM peers WHERE torrent = $id") or sqlerr();
				while ($subrow = mysql_fetch_array($subres)) {
					if ($subrow["seeder"] == "yes")
						$seeders[] = $subrow;
					else
						$downloaders[] = $subrow;
				}
	
				function leech_sort($a,$b) {
	                                if ( isset( $_GET["usort"] ) ) return seed_sort($a,$b);				
	                                $x = $a["to_go"];
					$y = $b["to_go"];
					if ($x == $y)
						return 0;
					if ($x < $y)
						return -1;
					return 1;
				}
				function seed_sort($a,$b) {
					$x = $a["uploaded"];
					$y = $b["uploaded"];
					if ($x == $y)
						return 0;
					if ($x < $y)
						return 1;
					return -1;
				}

				usort($seeders, "seed_sort");
				usort($downloaders, "leech_sort");

				tr("<a name=\"seeders\">Deilendur</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[Fela lista]</a>", dltable("Seeder(s)", $seeders, $row), 1);
				tr("<a name=\"leechers\">Skr�arsugur</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[Fela lista]</a>", dltable("Leecher(s)", $downloaders, $row), 1);
			}
		}
$torrentid = $_GET["id"];
         $thanks_sql = mysql_query("SELECT * FROM thanks where torrentid=$torrentid");
   $thanks_all = mysql_numrows($thanks_sql);
if ($thanks_all) {
	while($rows_t = mysql_fetch_array($thanks_sql)) {
		$thanks_userid = $rows_t["userid"];
		$user_sql = mysql_query("SELECT * FROM users where id=$thanks_userid");
		$rows_a = mysql_fetch_array($user_sql);
		$username_t = $rows_a["username"];
		$thanksby =  $thanksby."<a href='userdetails.php?id=$thanks_userid'>$username_t</a>, ";
   	}
   	$t_userid = $CURUSER["id"];
	$tsql = mysql_query("SELECT COUNT(*) FROM thanks where torrentid=$torrentid and userid=$t_userid");
	$trows = mysql_fetch_array($tsql);
	$t_ab = $trows[0];
	if ($t_ab == "0") {
		$thanksby = $thanksby." <form action=\"thanks.php\" method=\"post\">
		<input type=\"submit\" name=\"submit\" value=\"Takk!\">
		<input type=\"hidden\" name=\"torrentid\" value=\"$torrentid\">
		</form>";
	} else {
		$thanksby = $thanksby." <form action=\"thanks.php\" method=\"post\">
		<input type=\"submit\" name=\"senda\" value=\"Takk!\" disabled>
		<input type=\"hidden\" name=\"torrentid\" value=\"$torrentid\">
		</form>";
	}
} else {
	$thanksby = "Enginn
	<form action=\"thanks.php\" method=\"post\">
	<input type=\"submit\" name=\"submit\" value=\"Takk!\">
	<input type=\"hidden\" name=\"torrentid\" value=\"$torrentid\">
	</form>
	";
}
	       tr("Vi� ��kkum fyrir:",$thanksby,1);
               print("</table></p>\n");
	} else {
		stdhead("Athugasemdir fyrir deilingu \"" . $row["name"] . "\"");
		print("<h1>Comments for <a href=details.php?id=$id>" . $row["name"] . "</a></h1>\n");
//		print("<p><a href=\"details.php?id=$id\">Til baka � n�kv�mt yfirlit</a></p>\n");
	}

if(get_user_class() >= UC_MODERATOR) {
	$res3 = mysql_query('SELECT COUNT(snatched.id),users.*,torrents.* FROM snatched,users,torrents 
WHERE snatched.userid = users.id 
AND snatched.torrentid = torrents.id 
AND snatched.torrentid =' . $_GET[id] . '
GROUP BY snatched.userid') or die(mysql_error());
	$row = mysql_fetch_array($res3);

	$count = $row[0];
	$perpage = 50;
	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] . "?id=" . $_GET[id] . "&" );

	$res2 = mysql_query("select name from torrents where id = $_GET[id]");
	$arr2 = mysql_fetch_assoc($res2);
	$dt = gmtime() - 180;
	$dt = sqlesc(get_date_time($dt));


	print("<h1 align=center>Sm�atri�i fyrir <a href=details.php?id=$_GET[id]><b>$arr2[name]</b></a></h1>\n");
	print("<p align=center>Efstu notendurnir kl�ru�u seinast</p>");

	echo $pagertop;

	print("<table border=1 cellspacing=0 cellpadding=5 align=center>\n");
	echo '<tr><td class="colhead" align="left">Notandi</td><td class="colhead" align="left">Deilt</td><td class="colhead" align="left">S�tt</td><td class="colhead" align="left">Hlutfall</td><td class="colhead" align="left">Senda skilabo�</td><td class="colhead" align="left"><font color="red">Tilkynna misnotkun</font></td>
	<td class="colhead" align="left">Inni/�ti</td><td class="colhead" align="left">A� Deila</td></tr>';

	$res = mysql_query("select DISTINCT(users.id), users.username, users.uploaded, users.downloaded, snatched.userid from snatched inner join users on snatched.userid = users.id inner join torrents on snatched.torrentid = torrents.id where snatched.torrentid =" . $_GET[id] . " ORDER BY snatched.id desc $limit");
	while ($arr = mysql_fetch_assoc($res))
	{

	$res2 = mysql_query("SELECT id,donor,title,enabled,warned,last_access FROM users WHERE id=$arr[userid]") or sqlerr(__FILE__, __LINE__);
	$arr2 = mysql_fetch_assoc($res2);

	$res3 = mysql_query("SELECT * FROM peers WHERE torrent=$_GET[id] AND userid=$arr[userid]");
	$arr3 = mysql_fetch_assoc($res3);

	if ($arr["downloaded"] > 0)
	{
	$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
	$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
	}
	else
	if ($arr["uploaded"] > 0)
	$ratio = "Inf.";
	else
	$ratio = "---";
	$uploaded =mksize($arr["uploaded"]);
	$downloaded = mksize($arr["downloaded"]);

	print("<tr><td><a href=userdetails.php?id=$arr[userid]><b>$arr[username]</b></a></td><td align=left>$uploaded</td><td align=left>$downloaded</td><td align=left>$ratio</td><td><form method=get action=sendmessage.php><input type=hidden name=receiver value=" .
	$arr[userid]."><input type=submit value=\"Skilabo�\" style='height: 22px'></form><form method=post action=report.php?user=$arr[userid]></td><td align=left><input type=submit value=\"Tilkynna\" style='height: 23px'></form></td><td align=center>" . get_user_icons($arr2, true) .

	"&nbsp; ".("'".$arr2['last_access']."'">$dt?"<img src=".$pic_base_url."online.gif border=0 alt=\"Online\">":"<img src=".$pic_base_url."offline.gif border=0 alt=\"Offline\">" )."</td>"."
	<td align=center>" . ($arr3["seeder"] == "yes" ? "<b><font color=green>J�</font>" : "<font color=red>Nei</font></b>") . "</td></tr>\n");
	}
	print("</table>\n");

	echo $pagerbottom;
}
	print("<p><a name=\"startcomments\"></a></p>\n");

	$commentbar = "<p align=center><a class=index href=addcomment.php?id=$id>Add a comment</a></p>\n";

	$subres = mysql_query("SELECT COUNT(*) FROM comments WHERE torrent = $id");
	$subrow = mysql_fetch_array($subres);
	$count = $subrow[0];

	if (!$count) {
		print("<h2>No comments yet</h2>\n");
	}
	else {
		list($pagertop, $pagerbottom, $limit) = pager(20, $count, "details.php?id=$id&", array(lastpagedefault => 1));

		$subres = mysql_query("SELECT comments.id, text, user, comments.added, avatar, warned, ".
                  "username, title, class, donor FROM comments LEFT JOIN users ON comments.user = users.id WHERE torrent = " .
                  "$id ORDER BY comments.id $limit") or sqlerr(__FILE__, __LINE__);
		$allrows = array();
		while ($subrow = mysql_fetch_array($subres))
			$allrows[] = $subrow;

		print($commentbar);
		print($pagertop);

		commenttable($allrows);

		print($pagerbottom);
	}

	print($commentbar);
}

stdfoot();

hit_end();

?>
