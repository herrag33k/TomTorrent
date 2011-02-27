<?php
require_once("include/bittorrent.php");
hit_start();
dbconn();
loggedinorreturn();

stdhead("Staðfesta");
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
		print("Notandi: $takeuser, Ástæða: $takereason<p></p>Hefur verið tilkynntur");
		end_main_frame();
		stdfoot();
		die();
	} else {
		print("Þú hefur þegar tilkynnt notandann $takeuser");
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
		print("Torrent: $taketorrent, Ástæða: $takereason<p></p>Hefur verið tilkynnt");
		end_main_frame();
		stdfoot();
		die();
	} else {
		print("Þú hefur þegar tilkynnt $taketorrent");
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
			print("Ógilt notandaauðkenni");
			end_main_frame();
			stdfoot();
			die();
	}
 
	$arr = mysql_fetch_assoc($res);
	if ($arr["class"] >= UC_MODERATOR)
	{
		print("Getur ekki tilkynnt starfsfólk.");
		end_main_frame();
		stdfoot();
		die();
	} else {
		print("<h2>Ertu viss um að þú viljir tilkynna notandan <a href=userdetails.php?id=$user><b>$arr[username]</b></a>?</h2><p></p>");
		print("<p>Taktu eftir, þetta <b>er</b> ekki til að tilkynna skráarsugur, við höfum kóða til að sjá um þær</p>");
		print("<p>Þetta form er ekkert grín. Þeir sem fíflast með það fá viðvörun og mögulega bann!</p>");
		echo '<b>Ástæða</b> (skylda): <form method=post action=report.php><input type=hidden name=user value='.$user.'><input type=text size=100 name=reason>';
		echo '<br /><b>Stjórnendur eru ekki hugsanalesarar! Lýsið vandamálinu vel (en ekki of langt)</b><br />';
		echo 'Vinsamlegast ekki nota formið til að tilkynna annað en alvarleg brot á reglum.<br />';
		echo 'Aðrar tilkynningar eiga að berast til <a href="mailto:torrent@torrent.is">torrent@torrent.is</a><br />';
		echo 'Stjórnendur eiga ekki að þurfa að leita að sönnunargögnum, vinsamlegast útvegið viðeigandi slóðir.<br />';
		echo '<br /><input type=submit class=btn value=Staðfesta>';
		echo '</form>';  
	}
}
if (isset($torrent))
{
	$res = mysql_query("SELECT name FROM torrents WHERE id=$torrent");
 
	if (mysql_num_rows($res) == 0)
	{
		print("Ógilt TorrentID");
		end_main_frame();
		stdfoot();
		die();
	}
	$arr = mysql_fetch_array($res);
	print("<h2>Ertu viss um að þú viljir tilkynna <a href=details.php?id=$torrent><b>$arr[name]</b></a>?</h2><p></p>");
	print("<p>Þetta form er ekkert grín. Þeir sem fíflast með það fá viðvörun og mögulega bann!</p>");
	print("<b>Ástæða</b> (skylda): <form method=post action=report.php><input type=hidden name=torrent value=$torrent><input type=text size=100 name=reason><p></p><input type=submit class=btn value=Staðfesta></form>");
}

if ((!isset($user)) && (!isset($torrent)))
	print("<h1>Missing Info</h1>");

end_main_frame();
stdfoot();
?>
