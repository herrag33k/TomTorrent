<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Notendur ra�a�ir eftir IP t�lum');
if (get_user_class() >= UC_MODERATOR) {
$res = mysql_query("SELECT id,username,enabled,status,ip,invitari FROM users ORDER by ip DESC") or sqlerr();
while ($a = mysql_fetch_assoc($res))
{
	if($a['enabled'] == 'yes') { 
		$i = get_user_class_name($a["class"]); 
	} else {
		$i = "<font color=red>Disabled</font>";
	}
	if($a['ip'] != $ip)
		echo '<hr>';
	echo $a['ip']. ' - <a href=userdetails.php?id=' . $a['id'] . '><b>' . 
$a['username'] .'</b></a> - '. $i . ' - Bj��andi: ' . $a['invitari'] . '<br>';
	$ip = $a['ip'];
}
} else {
	print("ޞessi hlut i �nnunar er ein�ngu �lt�ur sjt�rnendum.");
}
end_frame();
end_main_frame();
stdfoot();
?>
