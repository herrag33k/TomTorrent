<?

ob_start("ob_gzhandler");

require "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead("Bo�slyklar");

begin_main_frame();

begin_frame("�� tekur �byrg� � �eim sem �� b��ur");
begin_frame("Bj��a inn notanda");
@$ratio = $CURUSER['uploaded'] / $CURUSER['downloaded'];
$t_medlimur = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400*14)));
$t_medlimur2 = str_replace(array(' ',':','-'),'',$CURUSER['added']);
if(!empty($_POST['email']))
	$email = $_POST['email'];
if(inviteleft($CURUSER['id'],$CURUSER['uploaded'],$CURUSER['downloaded'],$CURUSER['warned'],$CURUSER['added']) > '0') 
{
	echo '�� tekur �byrg�ina � �v� a� kenna �eim sem �� b��ur � kerfi� og svara au�veldustu spurningum sem �eir kunna a� hafa.<br />'."\n".'<br />';
	echo 'Ef �� b��ur inn notanda aftur til �ess a� hj�lpa honum vi� a� fara framhj� banni e�a �virkingu, munt �� ver�a banna�ur ef �a� varst �� sem bau�st honum ��ur.<br />';
	echo '<form method="post" action="/invites.php">';
	echo 'Netfang: <input type="text" name="email"><br />';
	if(!empty($email)) {
		$sql = 'SELECT COUNT(*) FROM users WHERE email=\''.mysql_real_escape_string(trim($email))."'";
		$sqla = 'SELECT COUNT(*) FROM invites WHERE inviter_id='.$CURUSER['id'].' AND email='.sqlesc(trim($email));
		$email_split = explode('@', $email);
		if(verifystring($email,'email') !== TRUE)
			echo '<b>�� �arft a� sl� inn gilt netfang.</b>';
		elseif(mysql_result(mysql_query($sql),0) > 0)
			echo '<b>�etta netfang er �egar � skr� � Istorrent.</b><br />';
		elseif(mysql_result(mysql_query($sqla),0) > 0)
			echo '<b>�etta netfang er n� �egar � bo�slistanum ��num (sj� ne�ar � s��unni).</b><br />';
		elseif ($email_split[1] == 'solidshadow.net')
			echo '<b>Eigandi l�nsins er a� reyna a� komast framhj� banni. Ekki bj��a �essum einstaklingi inn, jafnvel undir ��ru netfangi.</b><br />';
		elseif ($email_split[1] == 'hotmail.com' || $email_split[1] == 'msn.com' || $email_split[1] == 'verslo.is')
			echo '<b>Vinsamlegast ekki skr� netf�ng hj� hotmail.com, msn.com e�a verslo.is vegna erfi�leika vi� t�lvup�stsendingar � �essi l�n.</b><br />';
		elseif ($email_split[1] == 'smais.is' || $email_split[1] == 'skifan.is' || $email_split[1] == 'stef.is' || $email_split[1] == 'police.is' || $email_split[1] == 'logreglan.is')
			echo '<b>Banna� er a� bj��a inn a�ilum me� netfang � �essu l�ni.</b><br />';
		else {
			$invitesalt = rand(1000000,99999999);
			$secret_hash = md5($invitesalt.trim($email));
			$sql = 'INSERT INTO invites (timestamp,inviter_id,secret_hash,email,used) VALUES ('.date('YmdHis').','.$CURUSER['id'].',\''.$secret_hash.'\',\''.mysql_real_escape_string(trim($email)).'\',0)';
			mysql_query($sql) OR sqlerr();
			echo '<b>Bo�slykill fyrir notanda me� netfangi� '.trim($email).' er</b> '.$secret_hash.'<br />';
			echo '<a href="/invites.php">Bj��a fleirum inn</a><br />';
			$header = 'Refresh: 0; url='.$BASEURL.'/invites.php';
			header($header);
		}
	}
	echo '<input type="submit" value="Bj��a inn"><br />';
	echo '�� munt f� bo�slykil sem �� l�tur bj��anda f�. Eing�ngu er h�gt a� nota hann til a� skr� a�gang � netfangi� sem �� sl�st inn.<br />';
	echo 'Istorrent s�r ekki um a� senda bo�slykilinn fyrir �ig � netfangi�. �� ver�ur sj�lf(ur) a� afhenda bo�slykilinn til �ess sem � a� nota hann.<br />';
} else
	echo '<a href="/faq.php#25">Af hverju get �g ekki bo�i� inn f�lki?</a>';
end_frame();
if(mysql_result(mysql_query('SELECT COUNT(*) FROM invites WHERE inviter_id='.$CURUSER['id'].' AND used=0'),0) > '0') {
	begin_frame("�nota�ir bo�slyklar");
	echo '<b>Eftirfarandi bo�slykla hefur �� b�i� til en hafa ekki veri� n�ttir:</b><br />';
	if(!empty($_GET['hash']) && !empty($_GET['email'])) {
		$sql = 'DELETE FROM invites WHERE secret_hash=\''.mysql_real_escape_string($_GET['hash']).'\' AND email=\''.mysql_real_escape_string($_GET['email']).'\' AND inviter_id='.$CURUSER['id'];
		mysql_query($sql);
		if(mysql_affected_rows() > '0')
			echo 'Bo�slykli fyrir netfangi� '.$_GET['email'].' eytt<br />';
		else
			echo 'Mist�kt a� ey�a bo�slykli �r kerfinu<br />';
	}
	$sql = mysql_query('SELECT timestamp,secret_hash,email FROM invites WHERE inviter_id='.$CURUSER['id'].' AND used=0 ORDER BY id DESC');
	while($a = mysql_fetch_array($sql)) {
		echo $a['email'].' - Sl��: '.$BASEURL.'/signup.php?invite='.$a['secret_hash'].' [<a href="/invites.php?hash='.$a['secret_hash'].'&amp;email='.$a['email'].'">Ey�a bo�slykli</a>]<br />';
	}
	end_frame();
}

begin_frame("�� hefur bo�i� inn...");
$sql = 'SELECT * FROM users WHERE invitari='.$CURUSER['id'];
$query = mysql_query($sql);
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
