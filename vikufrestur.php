<?
ob_start();
require_once("include/bittorrent.php");
dbconn();
stdhead("Vikufrestur");
begin_main_frame();
//loggedinorreturn();
function bark($text) {
        stdmsg('Breyting mist�kst', $text);
        stdfoot();
        exit();
}
if(!empty($_POST['notandi']) && $_POST['notandi'] !== '0') {
	$sql = 'SELECT vikufr,uploaded,downloaded,passhash,secret,deleted,enabled FROM users WHERE username=\''.mysql_real_escape_string($_POST['notandi']).'\'';
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	if(mysql_num_rows($res) != '1')
		bark('Notandanafni� sem �� sl�st inn er ekki inn � gagnagrunninum.');
	if($CURUSER && $CURUSER['class'] >= UC_MODERATOR)
		$moderator = '1';
	if($row['passhash'] != md5($row['secret'].$_POST['password'].$row['secret']) && $moderator !== '1')
		bark('Rangt lykilor�');
	if($row['deleted'] !== '1' && $row['enabled'] === 'yes')
		bark('�� �arft ekki � vikufrestinum a� halda �essa stundina, �� ert n� �egar me� virkan a�gang.');
	if($row['uploaded']/$row['downloaded'] > '0.20')
		bark('Eing�ngu notendur me� 0,20 e�a l�gra � <a href="/hlutfoll.php">hlutf�ll</a> geta fengi� vikufrest.');
	if($row['vikufr'] > '0')
		bark('Notandinn "'.$_POST['notandi'].'" hefur n� �egar fengi� vikufrest. Eins og tilkynnt hefur veri�, �� er vikufresturinn ekki framlengjanlegir n� endurn�janlegir.');

	$addtime = time()+(7*24*60*60);
        $time = date('Ymd', $addtime);
	$sql = 'UPDATE users SET vikufr=\''.$time.'\', deleted = \'0\', enabled = \'yes\' WHERE username=\''.mysql_real_escape_string($_POST['notandi']).'\'';
	mysql_query($sql);
	begin_frame();
	echo 'Notandinn "'.$_POST['notandi'].'" hefur fengi� vikufrest.';
	end_frame();
}
	begin_frame('F� vikufrest');
	echo '<form method="post" action="vikufrestur.php">';
	echo 'Notandi: <input type="text" name="notandi" value="'.$_POST['notandi'].'" \><br />';
	if($CURUSER['class'] < UC_MODERATOR)
		echo 'Lykilor�: <input type="password" name="password" \><br />';
	echo '<input type="submit" value="F� vikufrest"> Vikufresturinn hefst um lei� og skilabo� koma um a� notandinn hafi fengi� hann.<br /><br />';
	echo '�etta form er til a� s�kja um vikufrest vegna �virkingar � a�gangi til a� laga hlutf�llin.<br />';
	echo '<b>Vikufresturinn er hvorki endurn�janlegur n� framlengjanlegur.</b><br />';
	echo 'Vertu �v� viss um a� �� hafir kunn�ttuna og t�kif�ri� til a� laga hlutf�llin � 7 d�gum.<br />';
	echo 'Fr� �v� a� �� s�kir um frestinn, f�r�u til loka seinasta dags hans til a� laga hlutf�llin.<br />';
	echo 'H�gt er a� s�kja um frestinn hven�r sem er eftir a� a�gangurinn haf�i veri� ger�ur �virkur.';
	end_frame();
end_main_frame();
stdfoot();
ob_flush();
?>
