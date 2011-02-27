<?
require_once("include/benc.php");
require_once("include/bittorrent.php");
dbconn(); 
/* RSS feeds*/
if (($fd1 = @fopen("rss.xml", "w")) && ($fd2 = fopen("rssdd.xml", "w")))
{
	$cats = "";
	$res = mysql_query("SELECT id, name FROM categories");
	while ($arr = mysql_fetch_assoc($res))
		$cats[$arr["id"]] = $arr["name"];
	$s = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>\n<rss version=\"0.91\">\n<channel>\n" .
		"<title>Istorrent</title>\n<description>10 nýjustu torrent</description>\n<link>$DEFAULTBASEURL/</link>\n";
	@fwrite($fd1, $s);
	@fwrite($fd2, $s);
	$r = mysql_query("SELECT id,name,descr,filename,category,seeders,leechers FROM torrents where seeders > 0 ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
	while ($a = mysql_fetch_assoc($r))
	{
		$nafn = $a["name"];
		$cat = $cats[$a["category"]];
		$s = "<item>\n<title>" . htmlspecialchars("$nafn ($cat)") . "</title>\n" .
			"<description>" . htmlspecialchars($a["descr"]) . "</description>\n" .
			"<seeders>" . htmlspecialchars($a["seeders"]) . "</seeders>\n" .
			"<leechers>" . htmlspecialchars($a["leechers"]) . "</leechers>\n";
		@fwrite($fd1, $s);
		@fwrite($fd2, $s);
		@fwrite($fd1, "<link>$DEFAULTBASEURL/details.php?id=$a[id]&amp;hit=1</link>\n</item>\n");
		$filename = htmlspecialchars($a["filename"]);
		@fwrite($fd2, "<link>$DEFAULTBASEURL/download.php/$a[id]/$filename</link>\n</item>\n");
	}
	$s = "</channel>\n</rss>\n";
	@fwrite($fd1, $s);
	@fwrite($fd2, $s);
	@fclose($fd1);
	@fclose($fd2);
	}/*
		$fd1 = @fopen("rss.xml", "w");
	$fd2 = fopen("rssdd.xml", "w");
	$cats = "";
	$query = mysql_query("SELECT id, name FROM categories");
	while ($flokkar = mysql_fetch_assoc($query))
		$cats[$flokkar["id"]] = $flokkar["name"];
	$s = "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>\n<rss version=\"0.91\">\n<channel>\n" .
		"<title>Istorrent</title>\n<description>10 nýjustu torrent</description>\n<link>$DEFAULTBASEURL/</link>\n";
	@fwrite($fd1, $s);
	@fwrite($fd2, $s);
	$query2 = mysql_query("SELECT id,name,descr,filename,category,seeders,leechers FROM torrents ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
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
	@fclose($fd2);*/
?>
