<?
// Bit-Bucket, breytt af Dabbi
require "include/bittorrent.php";
dbconn();
loggedinorreturn();

$maxfilesize = 500 * 1024;

if(get_user_class() <= UC_USER && $CURUSER['donor'] !== 'yes') {
	stdhead("Senda inn mynd (A�gangur banna�ur)");
		echo "<b>Venjulegir notendur hafa ekki r�ttindi til a� senda inn myndir.<b><br>�� �arft a� vera me� st��una 'Virkur notandi' e�a h�rri.";
	stdfoot();
	die();
}
if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
	$file = $HTTP_POST_FILES["file"];
	if (!isset($file) || $file["size"] < 1)
		stderr("Innsending mist�kst", "Engin g�gn m�ttekin!");
	if ($file["size"] > $maxfilesize)
		stderr("Innsending mist�kst", "�essi mynd er of st�r.");
	$filename = $file["name"];
	if (strpos($filename, "..") !== false || strpos($filename, "/") !== false)
		stderr("Innsending mist�kst", "Nafn m� ekki innihalda '..' n� '/' takk fyrir.");
	$filename2 = md5($filename);
	$tgtfile = "innmyndir/$filename2";
	if (file_exists($tgtfile))
		stderr("Innsending mist�kst", "Skr� me� nafninu <b>" . htmlspecialchars($filename) . "</b> er n� �egar til � myndasv��inu.");

	$it = @exif_imagetype($file["tmp_name"]);
	if ($it != IMAGETYPE_GIF && $it != IMAGETYPE_JPEG && $it != IMAGETYPE_PNG)
		stderr("Innsending mist�kst", "Fyrirgef�u, skr�in sem �� sendir inn var ekki mynd.");

	$i = strrpos($filename, ".");
	if ($i !== false)
	{
		$ext = strtolower(substr($filename, $i));
		if (($it == IMAGETYPE_GIF && $ext != ".gif") || ($it == IMAGETYPE_JPEG && $ext != ".jpg") || ($it == IMAGETYPE_PNG && $ext != ".png"))
			stderr("Villa", "R�ng skr�arending: <b>$ext</b>");
	}
	else
		stderr("Villa", "Skr�in ver�ur a� hafa endingu (.jpg/.gif/.png).");
	move_uploaded_file($file["tmp_name"], $tgtfile) or stderr("Villa", "Net�j�nsvilla, vinsamlegast l�ttu umsj�narmenn vita.");
	$url = str_replace(" ", "%20", htmlspecialchars("$BASEURL/innmyndir/$filename2"));
	$rnafn = htmlspecialchars($filename);
	$rip = isset($_SERVER["HTTP_X_FORWARDED"]) ? $_SERVER["HTTP_X_FORWARDED"] : $_SERVER["REMOTE_ADDR"];
	$ruserid = $CURUSER['id'];
	$ruser = $CURUSER['username'];
	if(!$_POST['adult'])
	$adult = 'no';
	else
	$adult = 'yes';
	mysql_query("INSERT into innmyndir SET md5nafn = '$filename2', nafn = '$rnafn', url = '$url', userid = '$ruserid', adult = '$adult', username = '$ruser', ip = '$rip'") or die(mysql_error());
	$id = mysql_insert_id();
	$rurl = $BASEURL ."/myndir.php?id=". $id;
	stderr("Upload t�kst", "Nota�u eftirfarandi sl�� til a� sj� myndina: <b><a href=\"$rurl\">$rurl</a></b><p><a href=/mynda-upload.php>Senda inn a�ra mynd</a>.");
}

stdhead("Senda inn mynd");
?>
<h1>Senda inn mynd</h1>
<form method=post action="mynda-upload.php" enctype="multipart/form-data">
<p><b>St�r� myndar m� ekki vera meiri en: <?=number_format($maxfilesize, 0, ',', '.'); ?> b�ti.</b></p>
<table border=1 cellspacing=0 cellpadding=5>
<tr><td class=rowhead>Senda inn</td><td><input type=file name=file size=60></td></tr>
<tr><td colspan=2 align=center><input type=checkbox name=adult>Mynd inniheldur nekt e�a anna� s�randi efni.</td></tr>
<tr><td colspan=2 align=center><input type=submit value="Byrja" class=btn></td></tr>
</table>
</form>
<p><a href=mymyndir.php>M�nar Myndir</a><br>
	<a href=myndaalbum.php>Myndaalb�m</a>
<p>
<table class=main width=410 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
<font class=small><b>Skilm�lar:</b> Ekki senda inn myndir sem eru �l�glegar, �vi�eigandi e�a sem �i� hafi� ekki r�tt til a� birta. Notandi missir allan r�tt � mynd sinni eftir a� h�n er send hinga� inn, ekki senda inn myndir sem �� myndir ekki vilja a� almenningur hef�i a�gang a�.</font>
</td></tr></table>
<?
stdfoot();

?>
