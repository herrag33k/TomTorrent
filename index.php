<?
ob_start("ob_gzhandler");

require "include/bittorrent.php";
//require "rconpasswords.php";
dbconn(true);
if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
  $choice = $_POST["choice"];
  if (isset($CURUSER) && $choice != "" && $choice < 256 && $choice == floor($choice))
  {
    $res = mysql_query("SELECT * FROM polls ORDER BY added DESC LIMIT 1") or sqlerr(__FILE__,__LINE__);
    $arr = mysql_fetch_assoc($res) or die("No poll");
    $pollid = $arr["id"];
    $userid = $CURUSER["id"];
    $res = mysql_query("SELECT * FROM pollanswers WHERE pollid=$pollid && userid=$userid") or sqlerr(__FILE__,__LINE__);
    $arr = mysql_fetch_assoc($res);
    if ($arr) die("Gallað atkvæði");
    mysql_query("INSERT INTO pollanswers VALUES(0, $pollid, $userid, $choice)") or sqlerr(__FILE__,__LINE__);
    if (mysql_affected_rows() != 1)
      stderr("Villa", "Villa átti sér stað. Atkvæðið þitt hefur ekki verið talið.");
    header("Location: $BASEURL/");
    die;
  }
  else
    stderr("Villa", "Gjörðu svo vel að velja möguleika.");
}


$a = @mysql_fetch_assoc(@mysql_query("SELECT id,username FROM users WHERE status='confirmed' ORDER BY id DESC LIMIT 1")) or sqlerr(__FILE__,__LINE__);
if (isset($CURUSER))
  $latestuser = '<a href="userdetails.php?id='.$a['id'].'">'.$a['username'].'</a>';
else
  $latestuser = $a['username'];

if(isset($CURUSER)) {
	$f_sql = 'SELECT friendid FROM friends WHERE userid='.$CURUSER['id'];
	$f_res = mysql_query($f_sql) or sqlerr(__FILE__,__LINE__);
	while($farr = mysql_fetch_object($f_res)) {
		$friends[] = $farr->friendid;
	}
}
$dt = gmtime() - 540;
$dt = sqlesc(get_date_time($dt));
$res = mysql_query("SELECT id, username, class FROM users WHERE last_access >= $dt ORDER BY username") or sqlerr(__FILE__,__LINE__);
while ($arr = mysql_fetch_assoc($res)) {
	if (isset($activeusers))
		$activeusers .= ','."\n";
	else
		$activeusers = '';
	switch ($arr['class']) {
		case UC_SYSOP:
		case UC_ADMINISTRATOR:
		case UC_MODERATOR:
			$arr['username'] = '<font color="#A83838">'.$arr['username'].'</font>';
		break;
	}
	if(isset($friends) && in_array($arr['id'],$friends))
		$arr['username'] = '<font color="#4040CO">'.$arr['username'].'</font>';

	if (isset($CURUSER))
		$activeusers .= '<a href="userdetails.php?id='.$arr['id'].'"><b>'. $arr['username'].'</b></a>';
	else
		$activeusers .= '<b>'.$arr['username'].'</b>';
}
$num_users = mysql_num_rows($res);
if (!isset($activeusers))
	$activeusers = 'Það hafa engir notendur verið virkir seinustu 15 mínúturnar.';

stdhead();
?><font class="small">Við bjóðum nýjasta meðlim okkar velkominn, <b><?=$latestuser?></b>!</font><p>
<table width="737px" class="main" border="0" cellspacing="0" cellpadding="0"><tr><td class="embedded">
<?
if (get_user_class() >= UC_MODERATOR) { ?>
	<font class="small">[<a class="altlink" href="/news.php"><b>Fréttasíða</b></a>]</font>
<? }
$addtime = time()-(60*24*60*60);
$time = date('Y-m-d H:i:s', $addtime);
$res = mysql_query("SELECT * FROM news WHERE added >= '$time' ORDER BY added DESC LIMIT 10") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) > 0) {
 	begin_main_frame();
	begin_frame();

	while ($arr = mysql_fetch_array($res)) {
		$newsid = $arr['id'];
		$body = str_replace("\n", '<br />', $arr['body']);
		$userid = $arr['userid'];
		$added = $arr['added'].'GMT ('.(get_elapsed_time(sql_timestamp_to_unix_timestamp($arr["added"]))).' síðan)';
		$res2 = mysql_query("SELECT username, donor FROM users WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
		$arr2 = mysql_fetch_array($res2);
		$postername = $arr2["username"];

		if ($postername == "")
			$by = "unknown[$userid]";
		else
			$by = '<a href="userdetails.php?id='.$userid.'"><b>'.$postername.'</b></a>'.($arr2['donor'] == 'yes' ? '<img src="pic/star.gif" alt="Donor">' : '');

		echo '<p class="sub"><table border="0" cellspacing="0" cellpadding="0"><tr><td class="embedded">';
		echo $added.'&nbsp;---&nbsp;eftir&nbsp;'.$by;
		if (get_user_class() >= UC_ADMINISTRATOR) {
			echo ' - [<a href="news.php?action=edit&newsid='.$newsid.'"><b>Breyta</b></a>]';
			echo ' - [<a href="news.php?action=delete&newsid='.$newsid.'"><b>Eyða</b></a>]';
		}
		echo '</td></tr></table></p>'."\n";

		begin_table(true);
		echo '<tr valign="top"><td class="comment">'.$body.'</td></tr>'."\n";
		end_table();
	}
	end_frame();
	end_main_frame();
}
?>
<h2>Hverjir eru að skoða síðuna núna (<?=$num_users?>)</h2>
<table width="100%" border="1" cellspacing="0" cellpadding="10"><tr><td class="text">
<?=$activeusers?>
</td></tr></table>

<?
if (isset($CURUSER)) {
	// Get current poll
	$res = mysql_query('SELECT * FROM polls ORDER BY added DESC LIMIT 1') or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_assoc($res);
	$pollid = $arr['id'];
	$userid = $CURUSER['id'];
	$question = $arr['question'];
	$o = array($arr["option0"], $arr["option1"], $arr["option2"], $arr["option3"], $arr["option4"], $arr["option5"], $arr["option6"], $arr["option7"], $arr["option8"], $arr["option9"], $arr["option10"], $arr["option11"], $arr["option12"], $arr["option13"], $arr["option14"], $arr["option15"], $arr["option16"], $arr["option17"], $arr["option18"], $arr["option19"]);

	// Check if user has already voted
	$res = mysql_query('SELECT * FROM pollanswers WHERE pollid='.$pollid.' AND userid='.$userid) or sqlerr(__FILE__,__LINE__);
	$arr2 = mysql_fetch_assoc($res);

	echo '<h2>Skoðanakönnun';

	if (get_user_class() >= UC_MODERATOR) {
	  	echo '<font class="small">';
		echo ' - [<a class="altlink" href="makepoll.php?returnto=main"><b>Ný könnun</b></a>]'."\n";
	  	echo ' - [<a class="altlink" href="makepoll.php?action=edit&pollid='.$arr['id'].'&returnto=main"><b>Breyta</b></a>]'."\n";
		echo ' - [<a class="altlink" href="polls.php?action=delete&pollid='.$arr['id'].'&returnto=main"><b>Eyða</b></a>]';
		echo '</font>';
	}
	echo '</h2>'."\n";
	echo '<table width="100%" border="1" cellspacing="0" cellpadding="10"><tr><td align="center">'."\n";
	echo '<table class="main" border="1" cellspacing="0" cellpadding="0"><tr><td class="text">';
	echo '<p align="center"><b>'.$question.'</b></p>'."\n";
	$voted = $arr2;
	if ($voted) { // display results
		if (isset($arr['selection']))
			$uservote = $arr['selection'];
		else
			$uservote = '-1';
		// we reserve 255 for blank vote.
		$res = mysql_query('SELECT selection FROM pollanswers WHERE pollid='.$pollid.' AND selection < 20') or sqlerr(__FILE__,__LINE__);

		$tvotes = mysql_num_rows($res);

		$vs = array(); // array of
		$os = array();

	// Count votes
	while ($arr2 = mysql_fetch_row($res))
		$vs[$arr2['0']] += '1';

	reset($o);
	for ($i = '0'; $i < count($o); ++$i)
		if ($o[$i])
			$os[$i] = array($vs[$i], $o[$i]);

	function srt($a,$b) {
		if ($a['0'] > $b['0'])
			return '-1';
		if ($a['0'] < $b['0'])
			return '1';
		return 0;
	}

	// now os is an array like this: array(array(123, "Option 1"), array(45, "Option 2"))
	if ($arr['sort'] == 'yes')
		usort($os, srt);

	echo '<table class="main" width="100%" border="0" cellspacing="0" cellpadding="0">'."\n";
	$i = 0;
	while ($a = $os[$i]) {
		if ($i == $uservote)
			$a[1] .= '&nbsp;*';
		if ($tvotes == '0')
			$p = '0';
		else
			$p = round($a['0'] / $tvotes * 100);
		if ($i % 2)
			$c = '';
		else
			$c = ' bgcolor="#ECE9D8"';
		echo '<tr><td width="1%" class="embedded"'.$c.'><nobr>'.$a['1'].'&nbsp;&nbsp;</nobr></td><td width="99%" class="embedded"'.$c.'>' .'<img src="'.$pic_base_url.'bar_left.gif" /><img src="'.$pic_base_url.'bar.gif" height="9" width="'.($p * 3).'" /><img src="'.$pic_base_url.'bar_right.gif" /> '.$p.'%</td></tr>'."\n";
		++$i;
	}
	echo '</table>'."\n";
	$tvotes = number_format($tvotes);
	echo '<p align="center">Atkvæði: '.$tvotes.'</p>'."\n";
} else {
	echo '<form method="post" action="index.php">'."\n";
	$i = '0';
	while ($a = $o[$i]) {
		echo '<input type="radio" name="choice" value="'.$i.'">'.$a.'<br />'."\n";
		++$i;
	}
	echo '<br />';
	echo '<input type="radio" name="choice" value="255">Skila auðu (Vil bara sjá niðurstöðurnar!)<br />'."\n";
	echo '<p align="center"><input type="submit" value="Senda inn!" class="btn"></p>';
}
?>
</td></tr></table>
<?
if ($voted)
	echo '<p align="center"><a href="/polls.php">Eldri kannanir</a></p>'."\n";
?>
	</td></tr></table>
<?
}

include('cache-info.txt');

if($CURUSER['class'] >= UC_MODERATOR) { ?>
	<h2>Álag á vefþjóninum</h2>
	<table width="100%" border="1" cellspacing="0" cellpadding="10"><tr><td align="left">
	<table class="main" border="0" width="402px"><tr><td style="padding: 0px; background-image: url(/pic/loadbarbg.gif); background-repeat: repeat-x">
	<? 
	$percent = min(100, round(exec('ps ax | grep -c httpd') / 256 * 100));
	if ($percent <= '70')
		$pic = 'loadbargreen.gif';
	elseif ($percent <= '90')
		$pic = 'loadbaryellow.gif';
	else
		$pic = 'loadbarred.gif';
	$width = $percent * 4;
	echo '<img height="15px" width="'.$width.'" src="/pic/'.$pic.'" alt="'.$percent.'%" />'; ?>
	</td></tr></table>
	</td></tr></table>
<? } ?>
<p><font class="small">Fyrirvari: Ekkert efni deilt með torrent skrám þessa vefs er hýst á þessum vefþjóni. 
Istorrent ber ekki ábyrgð á því efni sem notendur dreifa með notkun Istorrent en ritskoðar það þó ef 
greinileg og sannanleg merki eru um brot á reglum eða lögum.	
Stjórnendur vefsins (http://torrent.is/) geta ekki borið persónulega ábyrgð á því sem notendur 
Istorrent senda inn né þeim afleiðingum sem af því hlýst.
Þú mátt ekki nota þennan vef til að deila eða sækja efni sem þú ert lagalega bannaður að flytja.
Það er því þín ábyrgð að fara eftir því sem skilmálar og reglur vefsins nefna.</font></p>

Kóðinn á þessum vef er upprunalega byggður á <a href="http://tbdev.net">TBSource</a> kerfinu.

</td></tr></table>

<?
stdfoot();

hit_end();
?>
