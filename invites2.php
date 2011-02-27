<?

ob_start("ob_gzhandler");

require "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead("Boð");

begin_main_frame();

?>

<?
$eg = $CURUSER["username"];
begin_frame("Þú tekur ábyrgð á þeim sem þú býður");
begin_frame("Boðslykilorðið þitt");
$upl_limit = 1024*1024*1024*5;
@$ratio = $CURUSER['uploaded'] / $CURUSER['downloaded'];
$t_medlimur = str_replace(array(' ',':','-'),'',sqlesc(get_date_time(gmtime() - 86400*14)));
if($CURUSER['uploaded'] >= $upl_limit && $ratio >= '0.85' && str_replace(array(' ',':','-'),'',$CURUSER['added']) < $t_medlimur) {
	echo 'Þú tekur ábyrgðina á því að kenna þeim sem þú býður á kerfið og svara auðveldustu spurningum sem þeir kunna að hafa.<br />'."\n";
	echo "Lykilorðið er: <b>". $CURUSER['md5secret'] ."</b><p>";
	echo "Bein slóð er :<b> $BASEURL/signup.php?invite=". $CURUSER['md5secret'] ."</b><p>";
	if($CURUSER['invites'] == 1)
		$ending = "sinni";
	else
		$ending = "sinnum";
	echo "Þú getur notað þetta lykilorð ". $CURUSER['invites'] .". $ending í viðbót, eftir það færðu nýtt.";
} else
	echo 'Samkvæmt stefnu Istorrent er ekki hægt að bjóða inn nýjum meðlimum nema vera með 0.85 eða meira í hlutföll, hafa dreift 5 gígabætum af gögnum og hafa verið meðlimur í 2 vikur. ';
end_frame();
begin_frame("Þú hefur boðið inn...");
$query = mysql_query("SELECT * from users where invitari LIKE '$eg'");
if(mysql_num_rows($query) < 1) {
	echo "Engum.";
} else {
	while($a = mysql_fetch_array($query)){
		$id = $a['id'];
		$username = $a['username'];
		echo "<a href=userdetails.php?id=$id>$username</a><br>";
	}
?>

<? end_frame(); ?>
<? end_frame(); ?>
<? }
end_main_frame();
stdfoot(); ?>
