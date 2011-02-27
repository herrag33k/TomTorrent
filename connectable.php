<?
require "include/bittorrent.php";
dbconn();
$query = mysql_query("SELECT * from peers where connectable = 'no' && informed = '0' GROUP by 'userid'") or die(mysql_error());
while ($res = mysql_fetch_array($query)) {
	$userid = $res['userid'];
	$skilaboð = "[b]Þú ert bakvið eldvegg/NAT[/b]
	Kerfið nær ekki að tengjast þér á þeim portum sem eru stilt í torrent forritinu hjá þér
	Vinsamlegast opnaðu þau port sem þú ert að nota
	
	[b]Til að sjá hvernig þú stillir port í helstu BitTorrent forritum[/b]
	Þá getur þú smelt hér: http://torrent.is/portstillingar.php
	
	[i]Hér getur þú fundið upplýsingar um hvernig á að opna port á routernum hjá þér[/i]
	[b]Allir helstu routerar á Íslandi[/b]
	http://deilir.is/instruct.php
	
	[b]Flest aðrir routerar[/b]
	http://www.portforward.com
	
	[i]Ef þú fékst þessi skilaboð en ert samt búinn að opna port, þá gæti verið að þú þurfir að endurræsa torrentin
	(Þau byrja ekki uppá nýtt)[/i]";
		echo $userid . " - Sent<br>";
	mysql_query("UPDATE peers SET informed = 1 where userid = '$userid'");
	mysql_query("INSERT INTO `messages` ( `id` , `sender` , `receiver` , `added` , `msg` , `unread` , `poster` , `location` )
	VALUES ('', '0', '$userid', NOW( ) , '$skilaboð', 'yes', '0', 'in')");
}
?>
