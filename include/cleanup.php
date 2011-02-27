<?

require_once("bittorrent.php");

function docleanup() {
	global $torrent_dir, $signup_timeout, $max_dead_torrent_time, $autoclean_interval;

	set_time_limit(0);
	ignore_user_abort(1);

	do {
		$res = mysql_query("SELECT id FROM torrents");
		$ar = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			$ar[$id] = 1;
		}

		if (!count($ar))
			break;

		$dp = @opendir($torrent_dir);
		if (!$dp)
			break;

		$ar2 = array();
		while (($file = readdir($dp)) !== false) {
			if (!preg_match('/^(\d+)\.torrent$/', $file, $m))
				continue;
			$id = $m[1];
			$ar2[$id] = 1;
			if (isset($ar[$id]) && $ar[$id])
				continue;
			$ff = $torrent_dir . "/$file";
			unlink($ff);
		}
		closedir($dp);

		if (!count($ar2))
			break;

		$delids = array();
		foreach (array_keys($ar) as $k) {
			if (isset($ar2[$k]) && $ar2[$k])
				continue;
			$delids[] = $k;
			unset($ar[$k]);
		}
		//if (count($delids))
		//	mysql_query("DELETE FROM torrents WHERE id IN (" . join(",", $delids) . ")");

		$res = mysql_query("SELECT torrent FROM peers GROUP BY torrent");
		$delids = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			if (isset($ar[$id]) && $ar[$id])
				continue;
			$delids[] = $id;
		}
		if (count($delids))
			mysql_query("DELETE FROM peers WHERE torrent IN (" . join(",", $delids) . ")");

		$res = mysql_query("SELECT torrent FROM files GROUP BY torrent");
		$delids = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			if (isset($ar[$id]))
				continue;
			$delids[] = $id;
		}
//		if (count($delids))
//			mysql_query("DELETE FROM files WHERE torrent IN (" . join(",", $delids) . ")");
	} while (0);

	$deadtime = deadtime();
	mysql_query("DELETE FROM peers WHERE last_action < FROM_UNIXTIME($deadtime)");

	$deadtime -= $max_dead_torrent_time;
	mysql_query("UPDATE torrents SET visible='no' WHERE visible='yes' AND last_action < FROM_UNIXTIME($deadtime)");

	$deadtime = time() - $signup_timeout;
	mysql_query("DELETE FROM users WHERE status = 'pending' AND added < FROM_UNIXTIME($deadtime) AND last_login < FROM_UNIXTIME($deadtime) AND last_access < FROM_UNIXTIME($deadtime)");

	$torrents = array();
	$res = mysql_query("SELECT torrent, seeder, COUNT(*) AS c FROM peers GROUP BY torrent, seeder");
	while ($row = mysql_fetch_assoc($res)) {
		if ($row["seeder"] == "yes")
			$key = "seeders";
		else
			$key = "leechers";
		$torrents[$row["torrent"]][$key] = $row["c"];
	}

	$res = mysql_query("SELECT torrent, COUNT(*) AS c FROM comments GROUP BY torrent");
	while ($row = mysql_fetch_assoc($res)) {
		$torrents[$row["torrent"]]["comments"] = $row["c"];
	}

	$fields = explode(":", "comments:leechers:seeders");
	$res = mysql_query("SELECT id, seeders, leechers, comments FROM torrents");
	while ($row = mysql_fetch_assoc($res)) {
		$id = $row["id"];
		$torr = $torrents[$id];
		foreach ($fields as $field) {
			if (!isset($torr[$field]))
				$torr[$field] = 0;
		}
		$update = array();
		foreach ($fields as $field) {
			if ($torr[$field] != $row[$field])
				$update[] = "$field = " . $torr[$field];
		}
		if (count($update))
			mysql_query("UPDATE torrents SET " . implode(",", $update) . " WHERE id = $id");
	}



	// Update stats
//	$seeders = get_row_count("peers", "WHERE seeder='yes'");
//	$leechers = get_row_count("peers", "WHERE seeder='no'");
//	mysql_query("UPDATE avps SET value_u=$seeders WHERE arg='seeders'") or sqlerr(__FILE__, __LINE__);
//	mysql_query("UPDATE avps SET value_u=$leechers WHERE arg='leechers'") or sqlerr(__FILE__, __LINE__);

	// update forum post/topic count
	$forums = mysql_query("select id from forums");
	while ($forum = mysql_fetch_assoc($forums))
	{
		$postcount = 0;
		$topiccount = 0;
		$topics = mysql_query("select id from topics where forumid=$forum[id]");
		while ($topic = mysql_fetch_assoc($topics))
		{
			$res = mysql_query("select count(*) from posts where topicid=$topic[id]");
			$arr = mysql_fetch_row($res);
			$postcount += $arr[0];
			++$topiccount;
		}
		mysql_query("update forums set postcount=$postcount, topiccount=$topiccount where id=$forum[id]");
	}

// delete old torrents // old code for TTL
$days = 28;
$dt = sqlesc(get_date_time(gmtime() - ($days * 86400)));
$res = mysql_query("SELECT id, name FROM torrents WHERE added < $dt");
while ($arr = mysql_fetch_assoc($res))
{
@unlink("$torrent_dir/$arr[id].torrent");
mysql_query("DELETE FROM torrents WHERE id=$arr[id]");
mysql_query("DELETE FROM snatched WHERE torrentid =$arr[id]");
mysql_query("DELETE FROM peers WHERE torrent=$arr[id]");
mysql_query("DELETE FROM comments WHERE torrent=$arr[id]");
mysql_query("DELETE FROM files WHERE torrent=$arr[id]");
mysql_query("DELETE FROM thanks WHERE torrentid =$arr[id]");
write_log("Torrentinu $arr[id] ($arr[name]) var eytt af kerfinu (eldri en $days daga)");
}
//Uppfæra RSS
        $fd1 = @fopen("rss.xml", "w");
        $fd2 = fopen("rssdd.xml", "w");
        $cats = "";
        $query = mysql_query("SELECT id, name FROM categories");
        while ($flokkar = mysql_fetch_assoc($query))
                $cats[$flokkar["id"]] = $flokkar["name"];
        $s = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>\n<rss version=\"0.91\">\n<channel>\n" .
                "<title>IsTorrent</title>\n<description>50 Nýjustu torrent</description>\n<link>$DEFAULTBASEURL/</link>\n";
        @fwrite($fd1, $s);
        @fwrite($fd2, $s);
        $query2 = mysql_query("SELECT id,name,descr,filename,category,seeders,leechers FROM torrents where seeders > 0 ORDER BY added DESC LIMIT 50") or sqlerr(__FILE__, __LINE__);
        while ($rssdot = mysql_fetch_assoc($query2))
        {
                $nafn = $rssdot["name"];
                $cat = $cats[$rssdot["category"]];
                $s = "<item>\n<title>" . htmlspecialchars("$nafn ($cat)") . "</title>\n" .
                        "<description>" . htmlspecialchars($rssdot["descr"]) . "</description>\n" .
                        "<seeders>" . htmlspecialchars($rssdot["seeders"]) . "</seeders>\n" .
                        "<leechers>" . htmlspecialchars($rssdot["leechers"]) . "</leechers>\n";
                @fwrite($fd1, $s);
                @fwrite($fd2, $s);
                @fwrite($fd1, "<link>$DEFAULTBASEURL/details.php?id=$rssdot[id]&amp;hit=1</link>\n</item>\n");
                $filename = htmlspecialchars($rssdot["filename"]);
                @fwrite($fd2, "<link>$DEFAULTBASEURL/download.php/$rssdot[id]/$filename</link>\n</item>\n");
        }
        $s = "</channel>\n</rss>\n";
        @fwrite($fd1, $s);
        @fwrite($fd2, $s);
        @fclose($fd1);
        @fclose($fd2);
}
?>
