<?
require_once("include/bittorrent.php");
require_once("foreign.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Notendur raðaðir eftir IP tölum');
if (get_user_class() >= UC_MODERATOR) {
	$cidr = file("/www/antilink/is-net.txt");
	$res = mysql_query("SELECT torrent,ip,userid,agent FROM peers ORDER by userid ASC") or sqlerr();
	while ($a = mysql_fetch_assoc($res))
	{
	// Check IP
	if(!matchCIDR2($a['ip'], $cidr))
		echo $a['torrent'].' - '.$a['userid'].' - '.$a['agent'].' - '.$a['ip'].'<br />';
	
	}
} else {
	print("Þžessi hlut i ðnnunar er einöngu æ¦ltð°ur sjt³rnendum.");
}
end_frame();
end_main_frame();
stdfoot();
?>
