<?
ob_start();
require_once("include/bittorrent.php");
dbconn();
stdhead("Vikufrestur");
begin_main_frame();
//loggedinorreturn();
function bark($text) {
        stdmsg('Breyting mistókst', $text);
        stdfoot();
        exit();
}
if(!empty($_POST['notandi']) && $_POST['notandi'] !== '0') {
	$sql = 'SELECT vikufr,uploaded,downloaded,passhash,secret,deleted,enabled FROM users WHERE username=\''.mysql_real_escape_string($_POST['notandi']).'\'';
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	if(mysql_num_rows($res) != '1')
		bark('Notandanafnið sem þú slóst inn er ekki inn á gagnagrunninum.');
	if($CURUSER && $CURUSER['class'] >= UC_MODERATOR)
		$moderator = '1';
	if($row['passhash'] != md5($row['secret'].$_POST['password'].$row['secret']) && $moderator !== '1')
		bark('Rangt lykilorð');
	if($row['deleted'] !== '1' && $row['enabled'] === 'yes')
		bark('Þú þarft ekki á vikufrestinum að halda þessa stundina, þú ert nú þegar með virkan aðgang.');
	if($row['uploaded']/$row['downloaded'] > '0.20')
		bark('Eingöngu notendur með 0,20 eða lægra í <a href="/hlutfoll.php">hlutföll</a> geta fengið vikufrest.');
	if($row['vikufr'] > '0')
		bark('Notandinn "'.$_POST['notandi'].'" hefur nú þegar fengið vikufrest. Eins og tilkynnt hefur verið, þá er vikufresturinn ekki framlengjanlegir né endurnýjanlegir.');

	$addtime = time()+(7*24*60*60);
        $time = date('Ymd', $addtime);
	$sql = 'UPDATE users SET vikufr=\''.$time.'\', deleted = \'0\', enabled = \'yes\' WHERE username=\''.mysql_real_escape_string($_POST['notandi']).'\'';
	mysql_query($sql);
	begin_frame();
	echo 'Notandinn "'.$_POST['notandi'].'" hefur fengið vikufrest.';
	end_frame();
}
	begin_frame('Fá vikufrest');
	echo '<form method="post" action="vikufrestur.php">';
	echo 'Notandi: <input type="text" name="notandi" value="'.$_POST['notandi'].'" \><br />';
	if($CURUSER['class'] < UC_MODERATOR)
		echo 'Lykilorð: <input type="password" name="password" \><br />';
	echo '<input type="submit" value="Fá vikufrest"> Vikufresturinn hefst um leið og skilaboð koma um að notandinn hafi fengið hann.<br /><br />';
	echo 'Þetta form er til að sækja um vikufrest vegna óvirkingar á aðgangi til að laga hlutföllin.<br />';
	echo '<b>Vikufresturinn er hvorki endurnýjanlegur né framlengjanlegur.</b><br />';
	echo 'Vertu því viss um að þú hafir kunnáttuna og tækifærið til að laga hlutföllin á 7 dögum.<br />';
	echo 'Frá því að þú sækir um frestinn, færðu til loka seinasta dags hans til að laga hlutföllin.<br />';
	echo 'Hægt er að sækja um frestinn hvenær sem er eftir að aðgangurinn hafði verið gerður óvirkur.';
	end_frame();
end_main_frame();
stdfoot();
ob_flush();
?>
