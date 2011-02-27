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
$res = mysql_query("SELECT * FROM users WHERE uploaded / downloaded <= $minratio AND downloaded >= $limit AND added <= $maxdt AND deleted = 0 AND class = 0 ORDER by enabled ASC, uploaded/downloaded DESC") or sqlerr();
if(mysql_num_rows($res) < 1) { echo "Engir notendur sem fylla kröfur um lélegt ratio."; }
while ($a = mysql_fetch_assoc($res))
{
if($a['enabled'] == 'yes') $i = get_user_class_name($a["class"]); else $i = "<font color=red>Disabled</font>";
echo '<a href="/userdetails.php?id='.$a['id'].'"><b>'.$a['username'].'</b></a> - '. number_format($a['uploaded']/$a['downloaded'],2) .' - '. $i;
if($a['vikufr'] != '0')
	echo ' - Hefur fengið vikufrest til '.substr($a['vikufr'],6,2).'-'.substr($a['vikufr'],4,2).'-'.substr($a['vikufr'],0,4);
if($a['deleted'] == '1')
	echo ' - Eyddur';
echo '<br>';
}
} else
	echo 'Þessi hluti síðunnar er eingöngu ætlaður stjórnendum!';
end_frame();
end_main_frame();
stdfoot();
?>
