<?
require "include/bittorrent.php";
dbconn();
$query = mysql_query("SELECT * from peers where connectable = 'no' && informed = '0' GROUP by 'userid'") or die(mysql_error());
while ($res = mysql_fetch_array($query)) {
	$userid = $res['userid'];
	$skilabo� = "[b]�� ert bakvi� eldvegg/NAT[/b]
	Kerfi� n�r ekki a� tengjast ��r � �eim portum sem eru stilt � torrent forritinu hj� ��r
	Vinsamlegast opna�u �au port sem �� ert a� nota
	
	[b]Til a� sj� hvernig �� stillir port � helstu BitTorrent forritum[/b]
	�� getur �� smelt h�r: http://torrent.is/portstillingar.php
	
	[i]H�r getur �� fundi� uppl�singar um hvernig � a� opna port � routernum hj� ��r[/i]
	[b]Allir helstu routerar � �slandi[/b]
	http://deilir.is/instruct.php
	
	[b]Flest a�rir routerar[/b]
	http://www.portforward.com
	
	[i]Ef �� f�kst �essi skilabo� en ert samt b�inn a� opna port, �� g�ti veri� a� �� �urfir a� endurr�sa torrentin
	(�au byrja ekki upp� n�tt)[/i]";
		echo $userid . " - Sent<br>";
	mysql_query("UPDATE peers SET informed = 1 where userid = '$userid'");
	mysql_query("INSERT INTO `messages` ( `id` , `sender` , `receiver` , `added` , `msg` , `unread` , `poster` , `location` )
	VALUES ('', '0', '$userid', NOW( ) , '$skilabo�', 'yes', '0', 'in')");
}
?>
