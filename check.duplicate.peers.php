<?
require_once("include/bittorrent.php");
require_once("foreign.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Notendur raðaðir eftir IP tölum');
if (get_user_class() >= UC_MODERATOR) {
	$res = mysql_query("SELECT id,ip,torrent,peer_id,port FROM peers ORDER by ip ASC") or sqlerr();
	$last = '';
	while ($a = mysql_fetch_assoc($res))
	{
		$now = md5($a['torrent'].'-'.$a['peer_id']);
		if($last == $now)
//			echo $now.'<br />';
			echo 'DELETE FROM peers WHERE id='.$a['id'].';<br />';	
		$last = $now;
	}
} else {
	echo 'bú!';
}
end_frame();
end_main_frame();
stdfoot();
?>
