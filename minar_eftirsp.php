<?
ob_start();
require_once("include/bittorrent.php");
dbconn();
stdhead("Mínar eftirspurnir");
begin_main_frame();
loggedinorreturn();
$header = 'Refresh: 0; url='.$BASEURL.'/minar_eftirsp.php?uid='.$_GET['uid'];
// Eyða eftirspurn
if(!empty($_GET['eyda'])) {
	begin_frame('Eyða eftirspurn');
	$rid = $_GET['rid'];
	if(!is_numeric($rid))
		die();
	$sql = 'SELECT filledby,userid,(SELECT COUNT(*) FROM addedrequests WHERE requestid = requests.id) AS count FROM requests WHERE requests.id = '.$rid;
//	echo $sql;
	$res = mysql_query($sql);
	while($res2 = mysql_fetch_assoc($res)) {
		if($res2['filledby'] > '0')
			$filled = '1';
		else
			$filled = '0';
		if(($res2['userid'] === $CURUSER['id'] && $res2['count'] === '0' && $filled !== '1') || $CURUSER['class'] > UC_MODERATOR) {
			mysql_query("DELETE FROM requests WHERE id = $rid");
			header($header);
//			echo 'Eftirspurn '.$rid.' eytt';
		} else
			echo 'Þú hefur ekki réttindin til að eyða þessari færslu';
	}
	end_frame();
}
if(!empty($_GET['draga'])) {
	begin_frame('Draga atkvæði til baka');
	$vid = $_GET['vid'];
	if(!is_numeric($vid))
		die();
	$res = mysql_query("SELECT addedrequests.userid,requests.request FROM addedrequests,requests WHERE addedrequests.id=$vid AND (addedrequests.requestid = requests.id)");
	while($res2 = mysql_fetch_assoc($res)) {
		if($CURUSER['id'] === $res2['userid'] || $CURUSER['class'] > UC_MODERATOR) {
			mysql_query("DELETE FROM addedrequests WHERE id = $vid");
			header($header);
//			echo 'Atkvæði með eftirspurninni "'.$res2['request'].'" dregið til baka';
		} else
			echo 'Þú hefur ekki réttindin til að draga þetta atkvæði til baka';
	}
	end_frame();
}
ob_flush();
begin_frame('Mínar eftirspurnir');
if($CURUSER['class'] > UC_MODERATOR && is_numeric($_GET['uid']))
	$uid = $_GET['uid'];
else
	$uid = $CURUSER['id'];

$sql = 'SELECT *,(SELECT COUNT(*) FROM addedrequests WHERE addedrequests.requestid = requests.id) AS count FROM requests WHERE userid='.$uid.' ORDER BY requests.id DESC';
//echo $sql.'<br />';
$res = mysql_query($sql) or sqlerr();
echo '<table>';
echo '<tr><td colspan="4">Fjöldi beiðna: '.mysql_num_rows($res).'</td></tr>';
echo '<tr><td><b>Auðkenni</b></td><td><b>Stutt lýsing</b></td><td><b>Eyða/uppfyllt</b></td><td><b>Kjósendur</b></td></tr>';

if(mysql_num_rows($res) > '0') {
	while($t = mysql_fetch_assoc($res)) {
		$filled = '';
		if($t['filledby'] >= '1')
			$filled = '1';
		else
			$filled = '0';
		echo '<tr><td>'.$t['id'].'</td><td><a href="/reqdetails.php?id='.$t['id'].'">'.$t['request'].'</a></td>';
		$uline = '&uid='.$uid;
		if($filled !== '1' && $t['count'] === '0')
			echo '<td><a href="/minar_eftirsp.php?rid='.$t['id'].$uline.'&eyda=1">Eyða eftirspurn</a></td>';
		elseif($t['filled'])
			echo '<td><a href="'.$t['filled'].'">Uppfyllt</a></td>';
		else
			echo '<td>Ekki hægt að eyða</td>';
		echo '<td>'.$t['count'].'</td>';
		echo '</tr>';
	}
} else
	echo '<tr><td colspan="4">Engar skráðar eftirspurnir</td></tr>';
echo '<tr><td colspan="4">Sé ekki hægt að eyða eftirspurn er annað hvort búið að uppfylla hana eða greiða henni atkvæði.</td></tr>';
echo '</table>';
end_frame();

begin_frame('Eftirspurnir sem hafa fengið atkvæði frá mér');
	echo '<table>';
	$sql = 'SELECT addedrequests.*,requests.request,requests.filled,requests.filledby FROM addedrequests,requests WHERE addedrequests.userid='.$uid.' AND (requests.id = addedrequests.requestid) ORDER BY requests.id DESC';
//	echo $sql;
	$res = mysql_query($sql) or sqlerr();
	$num_rows = mysql_num_rows($res);
	echo '<tr><td colspan="3">Fjöldi atkvæða: '.$num_rows.'</td></tr>';
	echo '<tr><td><b>Atkvæði með:</b></td><td><b>Draga til baka atkvæðið?</b></td><td><b>Uppfyllt?</b></td></tr>';
if($num_rows > '0') {
	$uline = '&uid='.$uid;
	while($s = mysql_fetch_assoc($res)) {
		echo '<tr>';
		echo '<td><a href="/reqdetails.php?id='.$s['requestid'].'">'.$s['request'].'</td>';
		echo '<td><a href="/minar_eftirsp.php?vid='.$s['id'].$uline.'&draga=1">Draga til baka</a></td>';
		if($s['filledby'] != '0')
			echo '<td><a href="'.$s['filled'].'">Uppfyllt</a></td>';
		else
			echo '<td>Óuppfyllt</td>';
		echo '</tr>';
	}
} else
	echo '<tr><td colspan="3">Engin skráð atkvæði</td></tr>';
echo '</table>';

end_frame();
end_main_frame();
stdfoot();
?>
