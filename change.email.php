<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Breyta netfangi notanda');
if (get_user_class() >= UC_MODERATOR) {
	if($_POST['email'] && $_POST['notandi'])
		$sql = 'UPDATE users SET email=\''.$_POST['email'].
'\' WHERE username=\''.$_POST['notandi']."'";
//		echo $sql;
		$res = mysql_query($sql);

echo '
	<form method="post" action="change.email.php">
	Notandi: <input type="text" name="notandi" value="'.$_POST['notandi'].'" \><br />
	Netfang: <input type="text" name="email" value="'.$_POST['email'].'" \> - Netfang sem á að breyta í<br />
	<input type="submit" value="Senda">
';
} else {
	echo 'Þessi hluti síðunnar er eingöngu ætlaður stjórnendum';
}
end_frame();
end_main_frame();
stdfoot();
?>
