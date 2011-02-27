<?

ob_start("ob_gzhandler");

require "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead("Boðslyklar");

begin_main_frame();

begin_frame("Þú tekur ábyrgð á þeim sem þú býður");
begin_frame("Bjóða inn notanda");
@$ratio = $CURUSER['uploaded'] / $CURUSER['downloaded'];
$t_medlimur = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400*14)));
$t_medlimur2 = str_replace(array(' ',':','-'),'',$CURUSER['added']);
if(!empty($_POST['email']))
	$email = $_POST['email'];
if(inviteleft($CURUSER['id'],$CURUSER['uploaded'],$CURUSER['downloaded'],$CURUSER['warned'],$CURUSER['added']) > '0') 
{
	echo 'Þú tekur ábyrgðina á því að kenna þeim sem þú býður á kerfið og svara auðveldustu spurningum sem þeir kunna að hafa.<br />'."\n".'<br />';
	echo 'Ef þú býður inn notanda aftur til þess að hjálpa honum við að fara framhjá banni eða óvirkingu, munt þú verða bannaður ef það varst þú sem bauðst honum áður.<br />';
	echo '<form method="post" action="/invites.php">';
	echo 'Netfang: <input type="text" name="email"><br />';
	if(!empty($email)) {
		$sql = 'SELECT COUNT(*) FROM users WHERE email=\''.mysql_real_escape_string(trim($email))."'";
		$sqla = 'SELECT COUNT(*) FROM invites WHERE inviter_id='.$CURUSER['id'].' AND email='.sqlesc(trim($email));
		$email_split = explode('@', $email);
		if(verifystring($email,'email') !== TRUE)
			echo '<b>Þú þarft að slá inn gilt netfang.</b>';
		elseif(mysql_result(mysql_query($sql),0) > 0)
			echo '<b>Þetta netfang er þegar á skrá á Istorrent.</b><br />';
		elseif(mysql_result(mysql_query($sqla),0) > 0)
			echo '<b>Þetta netfang er nú þegar á boðslistanum þínum (sjá neðar á síðunni).</b><br />';
		elseif ($email_split[1] == 'solidshadow.net')
			echo '<b>Eigandi lénsins er að reyna að komast framhjá banni. Ekki bjóða þessum einstaklingi inn, jafnvel undir öðru netfangi.</b><br />';
		elseif ($email_split[1] == 'hotmail.com' || $email_split[1] == 'msn.com' || $email_split[1] == 'verslo.is')
			echo '<b>Vinsamlegast ekki skrá netföng hjá hotmail.com, msn.com eða verslo.is vegna erfiðleika við tölvupóstsendingar á þessi lén.</b><br />';
		elseif ($email_split[1] == 'smais.is' || $email_split[1] == 'skifan.is' || $email_split[1] == 'stef.is' || $email_split[1] == 'police.is' || $email_split[1] == 'logreglan.is')
			echo '<b>Bannað er að bjóða inn aðilum með netfang á þessu léni.</b><br />';
		else {
			$invitesalt = rand(1000000,99999999);
			$secret_hash = md5($invitesalt.trim($email));
			$sql = 'INSERT INTO invites (timestamp,inviter_id,secret_hash,email,used) VALUES ('.date('YmdHis').','.$CURUSER['id'].',\''.$secret_hash.'\',\''.mysql_real_escape_string(trim($email)).'\',0)';
			mysql_query($sql) OR sqlerr();
			echo '<b>Boðslykill fyrir notanda með netfangið '.trim($email).' er</b> '.$secret_hash.'<br />';
			echo '<a href="/invites.php">Bjóða fleirum inn</a><br />';
			$header = 'Refresh: 0; url='.$BASEURL.'/invites.php';
			header($header);
		}
	}
	echo '<input type="submit" value="Bjóða inn"><br />';
	echo 'Þú munt fá boðslykil sem þú lætur bjóðanda fá. Eingöngu er hægt að nota hann til að skrá aðgang á netfangið sem þú slóst inn.<br />';
	echo 'Istorrent sér ekki um að senda boðslykilinn fyrir þig á netfangið. Þú verður sjálf(ur) að afhenda boðslykilinn til þess sem á að nota hann.<br />';
} else
	echo '<a href="/faq.php#25">Af hverju get ég ekki boðið inn fólki?</a>';
end_frame();
if(mysql_result(mysql_query('SELECT COUNT(*) FROM invites WHERE inviter_id='.$CURUSER['id'].' AND used=0'),0) > '0') {
	begin_frame("Ónotaðir boðslyklar");
	echo '<b>Eftirfarandi boðslykla hefur þú búið til en hafa ekki verið nýttir:</b><br />';
	if(!empty($_GET['hash']) && !empty($_GET['email'])) {
		$sql = 'DELETE FROM invites WHERE secret_hash=\''.mysql_real_escape_string($_GET['hash']).'\' AND email=\''.mysql_real_escape_string($_GET['email']).'\' AND inviter_id='.$CURUSER['id'];
		mysql_query($sql);
		if(mysql_affected_rows() > '0')
			echo 'Boðslykli fyrir netfangið '.$_GET['email'].' eytt<br />';
		else
			echo 'Mistókt að eyða boðslykli úr kerfinu<br />';
	}
	$sql = mysql_query('SELECT timestamp,secret_hash,email FROM invites WHERE inviter_id='.$CURUSER['id'].' AND used=0 ORDER BY id DESC');
	while($a = mysql_fetch_array($sql)) {
		echo $a['email'].' - Slóð: '.$BASEURL.'/signup.php?invite='.$a['secret_hash'].' [<a href="/invites.php?hash='.$a['secret_hash'].'&amp;email='.$a['email'].'">Eyða boðslykli</a>]<br />';
	}
	end_frame();
}

begin_frame("Þú hefur boðið inn...");
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
