<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Notendur sem virða ekki reglur um hlutföll');
if (get_user_class() >= UC_MODERATOR) {
$minratio = 0.2;
$maxdt = sqlesc(get_date_time(gmtime() - 86400*14));
$limit = 2*1024*1024*1024;
$res = mysql_query("SELECT * FROM users ORDER BY id ASC") or sqlerr();
if(mysql_num_rows($res) < 1) { echo "Engir notendur sem fylla kröfur um lélegt ratio."; }
while ($a = mysql_fetch_assoc($res))
{
		$inv = '';
//		echo $a['username']." - ".$a['invitari'];
//		echo '<br>';
		$mysql_invite = "SELECT id FROM users WHERE username = '".$a['invitari']."'";
		$mysql_invite2 = mysql_query($mysql_invite);
//		echo $mysql_invite;
		if(mysql_num_rows($mysql_invite2) == 1) {
			$inviteid = mysql_result($mysql_invite2, 0);
//			echo $inviteid.'<br />';
			if(is_numeric($inviteid)) {
				//echo '$inviteid - '.$inviteid.'<br />';
				//echo '&nbsp;&nbsp;Boðið af: '.$a['invitari']." - ".$inviteid.'<br />';
				$mysql_update = 'UPDATE users SET invitari = \''.$inviteid.'\' WHERE invitari = \''.$a['invitari'].'\'';
				echo $mysql_update.'<br />';
//				mysql_query($mysql_update) or sqlerr();
			}
		}
//	echo '<br /><br />';
}

} else {
print("Þžessi hlu isí ununar er einöngu tlað°ur jjó³rnendum.");
}
end_frame();
end_main_frame();
stdfoot();
?>
