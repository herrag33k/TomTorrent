<?php
require_once("include/bittorrent.php");
hit_start();
dbconn();
loggedinorreturn();

stdhead("Sta�festa");
begin_main_frame();

$takeuser = $_POST["user"];
$taketorrent = $_POST["torrent"];
$takereason = mysql_real_escape_string($_POST["reason"]);

$user = $_GET["user"];
$torrent = $_GET["torrent"];


if ((isset($takeuser)) && (isset($takereason)))
{
	$res = mysql_query("SELECT id FROM reports WHERE addedby = $CURUSER[id] AND votedfor = $takeuser AND type = 'user'") or sqlerr();
	if (mysql_num_rows($res) == 0)
	{
		mysql_query("INSERT into reports (addedby,votedfor,type,reason) VALUES ($CURUSER[id],$takeuser,'user', '$takereason')") or sqlerr();
		print("Notandi: $takeuser, �st��a: $takereason<p></p>Hefur veri� tilkynntur");
		end_main_frame();
		stdfoot();
		die();
	} else {
		print("�� hefur �egar tilkynnt notandann $takeuser");
		end_main_frame();
		stdfoot();
		die();
	}
}

if ((isset($taketorrent)) && (isset($takereason)))
{
	$res_sql = 'SELECT id FROM reports WHERE addedby ='.$CURUSER[id].' AND votedfor = '.$taketorrent.' AND type = \'torrent\'';
	$res = mysql_query($res_sql) or sqlerr();
	if (mysql_num_rows($res) == 0)
	{
		mysql_query("INSERT into reports (addedby,votedfor,type,reason) VALUES ($CURUSER[id],$taketorrent,'torrent', '$takereason')") or sqlerr();
		print("Torrent: $taketorrent, �st��a: $takereason<p></p>Hefur veri� tilkynnt");
		end_main_frame();
		stdfoot();
		die();
	} else {
		print("�� hefur �egar tilkynnt $taketorrent");
		end_main_frame();
		stdfoot();
		die();
	}
}


if (isset($user))
{
	$res_sql = 'SELECT username, class FROM users WHERE id='.$user;
	$res = mysql_query($res_sql) or sqlerr();
	if (mysql_num_rows($res) == 0)
	{
			print("�gilt notandaau�kenni");
			end_main_frame();
			stdfoot();
			die();
	}
 
	$arr = mysql_fetch_assoc($res);
	if ($arr["class"] >= UC_MODERATOR)
	{
		print("Getur ekki tilkynnt starfsf�lk.");
		end_main_frame();
		stdfoot();
		die();
	} else {
		print("<h2>Ertu viss um a� �� viljir tilkynna notandan <a href=userdetails.php?id=$user><b>$arr[username]</b></a>?</h2><p></p>");
		print("<p>Taktu eftir, �etta <b>er</b> ekki til a� tilkynna skr�arsugur, vi� h�fum k��a til a� sj� um ��r</p>");
		print("<p>�etta form er ekkert gr�n. �eir sem f�flast me� �a� f� vi�v�run og m�gulega bann!</p>");
		echo '<b>�st��a</b> (skylda): <form method=post action=report.php><input type=hidden name=user value='.$user.'><input type=text size=100 name=reason>';
		echo '<br /><b>Stj�rnendur eru ekki hugsanalesarar! L�si� vandam�linu vel (en ekki of langt)</b><br />';
		echo 'Vinsamlegast ekki nota formi� til a� tilkynna anna� en alvarleg brot � reglum.<br />';
		echo 'A�rar tilkynningar eiga a� berast til <a href="mailto:torrent@torrent.is">torrent@torrent.is</a><br />';
		echo 'Stj�rnendur eiga ekki a� �urfa a� leita a� s�nnunarg�gnum, vinsamlegast �tvegi� vi�eigandi sl��ir.<br />';
		echo '<br /><input type=submit class=btn value=Sta�festa>';
		echo '</form>';  
	}
}
if (isset($torrent))
{
	$res = mysql_query("SELECT name FROM torrents WHERE id=$torrent");
 
	if (mysql_num_rows($res) == 0)
	{
		print("�gilt TorrentID");
		end_main_frame();
		stdfoot();
		die();
	}
	$arr = mysql_fetch_array($res);
	print("<h2>Ertu viss um a� �� viljir tilkynna <a href=details.php?id=$torrent><b>$arr[name]</b></a>?</h2><p></p>");
	print("<p>�etta form er ekkert gr�n. �eir sem f�flast me� �a� f� vi�v�run og m�gulega bann!</p>");
	print("<b>�st��a</b> (skylda): <form method=post action=report.php><input type=hidden name=torrent value=$torrent><input type=text size=100 name=reason><p></p><input type=submit class=btn value=Sta�festa></form>");
}

if ((!isset($user)) && (!isset($torrent)))
	print("<h1>Missing Info</h1>");

end_main_frame();
stdfoot();
?>
