<?

ob_start("ob_gzhandler");

require "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead("Bo�");

begin_main_frame();

?>

<?
$eg = $CURUSER["username"];
begin_frame("�� tekur �byrg� � �eim sem �� b��ur");
begin_frame("Bo�slykilor�i� �itt");
$upl_limit = 1024*1024*1024*5;
@$ratio = $CURUSER['uploaded'] / $CURUSER['downloaded'];
$t_medlimur = str_replace(array(' ',':','-'),'',sqlesc(get_date_time(gmtime() - 86400*14)));
if($CURUSER['uploaded'] >= $upl_limit && $ratio >= '0.85' && str_replace(array(' ',':','-'),'',$CURUSER['added']) < $t_medlimur) {
	echo '�� tekur �byrg�ina � �v� a� kenna �eim sem �� b��ur � kerfi� og svara au�veldustu spurningum sem �eir kunna a� hafa.<br />'."\n";
	echo "Lykilor�i� er: <b>". $CURUSER['md5secret'] ."</b><p>";
	echo "Bein sl�� er :<b> $BASEURL/signup.php?invite=". $CURUSER['md5secret'] ."</b><p>";
	if($CURUSER['invites'] == 1)
		$ending = "sinni";
	else
		$ending = "sinnum";
	echo "�� getur nota� �etta lykilor� ". $CURUSER['invites'] .". $ending � vi�b�t, eftir �a� f�r�u n�tt.";
} else
	echo 'Samkv�mt stefnu Istorrent er ekki h�gt a� bj��a inn n�jum me�limum nema vera me� 0.85 e�a meira � hlutf�ll, hafa dreift 5 g�gab�tum af g�gnum og hafa veri� me�limur � 2 vikur. ';
end_frame();
begin_frame("�� hefur bo�i� inn...");
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
