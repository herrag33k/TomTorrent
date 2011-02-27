<?
ob_start('ob_gzhandler');
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

function bark($text) {
	stdmsg('Breyting mistókst', $text);
	stdfoot();
	exit();
}

stdhead('Breyting á notandanafni');
begin_main_frame();

if(!$_POST['username']) {
	begin_frame('Breyta notandanafni');
?>
	Þetta form er til þess að breyta notandanafni þess notanda sem er skráður inn núna.<br />
	* Það eru dregin frá 5 GB af því sem þú hefur deilt til að framkvæma þessa aðgerð.<br />
	* Eingöngu stafir í íslenska stafrófinu og tölustafir eru leyfðir í notandanafninu.<br />
	* Allar aðrar upplýsingar munu haldast eins í gegnum breytinguna, þar á meðal lykilorðið og hlutföllin.<br />
	* Breytingar á notandanöfnum eru sérstaklega skráðar.<br />
	<br />
	<form method="post" action="change.username.php">
	<table border="0" cellpadding="5">
	<tr><td class="rowhead">Nýja notandanafnið:</td><td align="left"><input type="text" size="40" name="username" /></td></tr>
	<tr><td class="rowhead">Núverandi lykilorð</td><td align="left"><input type="password" size="40" name="password" /></td></tr>
	<tr><td colspan="2" align="center"><input type="submit" value="Breyta!" class=btn></td></tr>
	</table>
	</form>
<?
	end_frame();
} else {
	$username = $_POST['username'];

	if($username === $CURUSER['username'])
		bark('Vinsamlegast sláðu inn notandanafnið sem þú vilt breyta í en ekki núverandi notandanafn.');

	$sql = 'SELECT passhash,secret,uploaded FROM users WHERE id='.$CURUSER['id'];
	$row = mysql_fetch_array(mysql_query($sql));
	if($row['uploaded'] < (5*1024*1024*1024))
		bark('Þú verður að hafa deilt 5GB til að geta greitt gjaldið til að framkvæma þessa breytingu');

	if($row['passhash'] != md5($row['secret'].$_POST['password'].$row['secret']))
		bark('Rangt lykilorð');

	$allowedchars = "aábcdðeéfghiíjklmnoópqrstuúvwxyýzþæöAÁBCDÐEÉFGHIÍJKLMNOÓPQRSTUÚVWXYZÞÆÖ0123456789";

	for ($i = 0; $i < strlen($username); ++$i) {
		if (strpos($allowedchars, $username[$i]) === false)
			$invalid = '1';
	}

	if(empty($username) || $username == '0')
		$invalid = '1';

	if($invalid == '1')
		bark('Notandanafn ekki leyft');

	$sql = 'SELECT COUNT(*) FROM users WHERE username=\''.$username.'\'';
	$res = mysql_query($sql);
	if(mysql_result(($res),0) > '0')
		bark('Notandanafn þegar í notkun');

	if(!$_GET['viss']) {
		begin_frame('Viss?');
		echo 'Ertu viss um að þú viljir breyta notandanafninu þínu í "'.$username.'"?<br />';
		echo 'Ef já, sláðu inn lykilorðið aftur og ýttu á "Breyta!"';
		echo '<form method="post" action="change.username.php?viss=1">
		<input type="hidden" size="40" name="username" value="'.$username.'" />
		<input type="password" size="40" name="password" />
		<input type="submit" value="Breyta!" class="btn"></form><br /><br />
		<b>ATH: Engin endurgreiðsla er í boði og breytingar eru ekki afturkallaðar eftir að þú hefur staðfest breytinguna. Greiða þarf fullt verð til að breyta notandanafninu aftur í það gamla ef þess er óskað.</b>';
		end_frame();
	} else {
		$amount = (5*1024*1024*1024);
		$sql = 'UPDATE users SET uploaded=uploaded-'.$amount.', username=\''.$username.'\' WHERE id='.$CURUSER['id'];
		forumlog($CURUSER['id'],$username,$CURUSER['username'],'username');
		if(!mysql_query($sql))
			bark('Mistókst að breyta notandanafni');
		header("Refresh: 0; url=/");
	}
}

end_main_frame();
stdfoot();
ob_flush();
?>
