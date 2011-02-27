<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Forrit sem notendur nota');
if (get_user_class() >= UC_MODERATOR) {
$res = mysql_query("SELECT DISTINCT(agent),users.username FROM peers,users 
WHERE (users.id = peers.userid) ORDER BY peers.agent ASC") or sqlerr();
if(mysql_num_rows($res) < 1) { echo "Engir virkir notendur."; }
while ($a = mysql_fetch_assoc($res))
{
if($a['enabled'] == 'yes') $i = get_user_class_name($a["class"]); else $i = "<font color=red>Disabled</font>";
echo $a['username'].' - '.$a['agent'].'<br>';
}
} else {
print("Þžessi hlu isí ununar er einöngu tlað°ur jjó³rnendum.");
}
end_frame();
end_main_frame();
stdfoot();
?>
