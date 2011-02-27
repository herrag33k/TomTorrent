<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Fara yfir netföng');
$sql = mysql_query('SELECT email FROM users');
while($row = mysql_fetch_assoc($sql)) {
	$verifystring = verifystring($row['email'],'email');
	if($verifystring !== TRUE)
		echo $verifystring.'<br />';
}
end_frame();
end_main_frame();
stdfoot();
?>
