<?
  require "include/bittorrent.php";
  dbconn(false);
  loggedinorreturn();
if (isset($_GET['out'])) {		// Sentbox
	$out = $_GET['out'];
	stdhead("Sentbox", false);
	echo '<table class="main" width="750px" border="0" cellspacing="0" cellpadding="10"><tr><td class="embedded">'."\n";
	echo '<h1 align="center">Sentbox</h1>'."\n";
	echo '<div align="center">(<a href="'.$_SERVER['PHP_SELF'].'">Inbox</a>)</div>'."\n";
	$res = mysql_query('SELECT messages.*,users.username AS username FROM messages,users WHERE sender='.$CURUSER['id'].' AND location IN (\'out\',\'both\') AND messages.receiver = users.id ORDER BY added DESC') or die("gubb!");
	if (mysql_num_rows($res) === '0')
		stdmsg("Uppl�singar","Engin send skilabo� skr��!");
	else
		while ($arr = mysql_fetch_assoc($res)) {
			$receiver = '<a href="userdetails.php?id='.$arr['receiver'].'">'.$arr['username'].'</a>';
			$elapsed = get_elapsed_time(sql_timestamp_to_unix_timestamp($arr['added']));
			echo '<p><table width="100%" border="1" cellspacing="0" cellpadding="10"><tr><td class="text">'."\n";
			echo 'Til <b>'.$receiver.'</b> �ann '."\n".$arr['added'].' ('.$elapsed.' s��an) GMT'."\n";
			if (get_user_class() >= UC_MODERATOR && $arr['unread'] === 'yes')
				echo '<b>(<font color="red">�lesi�!</font>)</b>';
			echo '<p><table class="main" width="100%" border="1" cellspacing="0" cellpadding="10"><tr><td class="text">'."\n";
			echo format_comment($arr['msg']);
			echo '</td></tr></table></p>'."\n".'<p>';
			echo '<table width="100%" border="0"><tr><td class="embedded">'."\n";
			echo '<a href="deletemessage.php?id='.$arr['id'].'&type=out"><b>Ey�a</b></a></td>'."\n";
			echo '</tr></table></tr></table></p>'."\n";
		}
} else {		// Inbox
	stdhead("Inbox", false);
	echo '<table class="main" width="750px" border="0" cellspacing="0" cellpadding="10"><tr><td class="embedded">'."\n";
	echo '<h1 align="center">Inbox</h1>'."\n";
	echo '<div align="center">(<a href="'.$_SERVER['PHP_SELF'].'?out=1">Sentbox</a>)</div>'."\n";
	$res = mysql_query('SELECT messages.*,users.username AS username FROM messages LEFT JOIN users ON messages.sender=users.id WHERE receiver='.$CURUSER['id'].' AND location IN (\'in\',\'both\') ORDER BY added DESC') or die('gubb!');
	if (mysql_num_rows($res) === '0')
		stdmsg('Uppl�singar','Skilabo�askj��an ��n er t�m!');
	else
		while ($arr = mysql_fetch_assoc($res)) {
			if (verifystring($arr['sender'],'num') === TRUE && $arr['sender'] !== '0') {
				$sender = '<a href="userdetails.php?id='.$arr['sender'].'">'. ($arr['username'] ? $arr['username'] : '[Eytt]').'</a>';
			} else
				$sender = 'Istorrent kerfi�';
			$elapsed = get_elapsed_time(sql_timestamp_to_unix_timestamp($arr["added"]));
			echo '<p><table width="100%" border="1" cellspacing="0" cellpadding="10"><tr><td class="text">'."\n";
			echo 'Fr� <b>'.$sender.'</b> �ann '."\n".$arr['added'].' ('.$elapsed.' s��an) GMT'."\n";
			if ($arr['unread'] === 'yes') {
				echo '<b>(<font color="red">N�tt!</font>)</b>';
				mysql_query('UPDATE messages SET unread=\'false\' WHERE id='.$arr['id']) or die('arghh');
			}
			echo '<p><table class="main" width="100%" border="1" cellspacing="0" cellpadding="10"><tr><td class="text">'."\n";
			echo format_comment($arr['msg']);
			echo '</td></tr></table></p>'."\n".'<p>';
			echo '<table width="100%" border="0"><tr><td class="embedded">'."\n";
			echo ($arr['sender'] ? '<a href="sendmessage.php?receiver='.$arr['sender'].'&replyto='.$arr['id'] .'"><b>Svara</b></a>' : '<font class="gray"><b>Svara</b></font>').' | <a href="deletemessage.php?id='.$arr['id'].'&type=in"><b>Ey�a</b></a></td>'."\n";

			if (get_user_class() >= UC_MODERATOR) {
				echo '<td class="embedded"><div align="right">Sni�: &nbsp; ';
				if(!empty($arr["sender"])) {
					echo '<a href="sendmessage.php?receiver='.$arr['sender'].'&replyto='.$arr['id'].'&auto=3'.'"><b>L�leg torrent l�sing</b></a> | ';
					echo '<a href="sendmessage.php?receiver='.$arr['sender'].'&replyto='.$arr['id'].'&auto=1'.'"><b>SOS</b></a> | ';
					echo '<a href="sendmessage.php?receiver='.$arr['sender'].'&replyto='.$arr['id'].'&auto=2'.'"><b>Rangt svi�</b></a>';
				} else
					echo '<font class="gray"><b>L�leg torrent l�sing - SOS</b></font> | Hva� anna�?</div></td>'."\n";
			}
			echo '</tr></table></tr></table></p>'."\n";
		}
}
echo '</td></tr></table>'."\n";
echo '<p align="center">�arftu a� <a href="users.php">finna</a> einhvern?</p>'."\n";

stdfoot();
?>
