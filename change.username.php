<?
ob_start('ob_gzhandler');
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

function bark($text) {
	stdmsg('Breyting mist�kst', $text);
	stdfoot();
	exit();
}

stdhead('Breyting � notandanafni');
begin_main_frame();

if(!$_POST['username']) {
	begin_frame('Breyta notandanafni');
?>
	�etta form er til �ess a� breyta notandanafni �ess notanda sem er skr��ur inn n�na.<br />
	* �a� eru dregin fr� 5 GB af �v� sem �� hefur deilt til a� framkv�ma �essa a�ger�.<br />
	* Eing�ngu stafir � �slenska stafr�finu og t�lustafir eru leyf�ir � notandanafninu.<br />
	* Allar a�rar uppl�singar munu haldast eins � gegnum breytinguna, �ar � me�al lykilor�i� og hlutf�llin.<br />
	* Breytingar � notandan�fnum eru s�rstaklega skr��ar.<br />
	<br />
	<form method="post" action="change.username.php">
	<table border="0" cellpadding="5">
	<tr><td class="rowhead">N�ja notandanafni�:</td><td align="left"><input type="text" size="40" name="username" /></td></tr>
	<tr><td class="rowhead">N�verandi lykilor�</td><td align="left"><input type="password" size="40" name="password" /></td></tr>
	<tr><td colspan="2" align="center"><input type="submit" value="Breyta!" class=btn></td></tr>
	</table>
	</form>
<?
	end_frame();
} else {
	$username = $_POST['username'];

	if($username === $CURUSER['username'])
		bark('Vinsamlegast sl��u inn notandanafni� sem �� vilt breyta � en ekki n�verandi notandanafn.');

	$sql = 'SELECT passhash,secret,uploaded FROM users WHERE id='.$CURUSER['id'];
	$row = mysql_fetch_array(mysql_query($sql));
	if($row['uploaded'] < (5*1024*1024*1024))
		bark('�� ver�ur a� hafa deilt 5GB til a� geta greitt gjaldi� til a� framkv�ma �essa breytingu');

	if($row['passhash'] != md5($row['secret'].$_POST['password'].$row['secret']))
		bark('Rangt lykilor�');

	$allowedchars = "a�bcd�e�fghi�jklmno�pqrstu�vwxy�z���A�BCD�E�FGHI�JKLMNO�PQRSTU�VWXYZ���0123456789";

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
		bark('Notandanafn �egar � notkun');

	if(!$_GET['viss']) {
		begin_frame('Viss?');
		echo 'Ertu viss um a� �� viljir breyta notandanafninu ��nu � "'.$username.'"?<br />';
		echo 'Ef j�, sl��u inn lykilor�i� aftur og �ttu � "Breyta!"';
		echo '<form method="post" action="change.username.php?viss=1">
		<input type="hidden" size="40" name="username" value="'.$username.'" />
		<input type="password" size="40" name="password" />
		<input type="submit" value="Breyta!" class="btn"></form><br /><br />
		<b>ATH: Engin endurgrei�sla er � bo�i og breytingar eru ekki afturkalla�ar eftir a� �� hefur sta�fest breytinguna. Grei�a �arf fullt ver� til a� breyta notandanafninu aftur � �a� gamla ef �ess er �ska�.</b>';
		end_frame();
	} else {
		$amount = (5*1024*1024*1024);
		$sql = 'UPDATE users SET uploaded=uploaded-'.$amount.', username=\''.$username.'\' WHERE id='.$CURUSER['id'];
		forumlog($CURUSER['id'],$username,$CURUSER['username'],'username');
		if(!mysql_query($sql))
			bark('Mist�kst a� breyta notandanafni');
		header("Refresh: 0; url=/");
	}
}

end_main_frame();
stdfoot();
ob_flush();
?>
