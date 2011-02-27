<?
// Bit-Bucket, breytt af Dabbi
require "include/bittorrent.php";
dbconn();
loggedinorreturn();

$maxfilesize = 500 * 1024;

if(get_user_class() <= UC_USER && $CURUSER['donor'] !== 'yes') {
	stdhead("Senda inn mynd (Aðgangur bannaður)");
		echo "<b>Venjulegir notendur hafa ekki réttindi til að senda inn myndir.<b><br>Þú þarft að vera með stöðuna 'Virkur notandi' eða hærri.";
	stdfoot();
	die();
}
if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
	$file = $HTTP_POST_FILES["file"];
	if (!isset($file) || $file["size"] < 1)
		stderr("Innsending mistókst", "Engin gögn móttekin!");
	if ($file["size"] > $maxfilesize)
		stderr("Innsending mistókst", "Þessi mynd er of stór.");
	$filename = $file["name"];
	if (strpos($filename, "..") !== false || strpos($filename, "/") !== false)
		stderr("Innsending mistókst", "Nafn má ekki innihalda '..' né '/' takk fyrir.");
	$filename2 = md5($filename);
	$tgtfile = "innmyndir/$filename2";
	if (file_exists($tgtfile))
		stderr("Innsending mistókst", "Skrá með nafninu <b>" . htmlspecialchars($filename) . "</b> er nú þegar til á myndasvæðinu.");

	$it = @exif_imagetype($file["tmp_name"]);
	if ($it != IMAGETYPE_GIF && $it != IMAGETYPE_JPEG && $it != IMAGETYPE_PNG)
		stderr("Innsending mistókst", "Fyrirgefðu, skráin sem þú sendir inn var ekki mynd.");

	$i = strrpos($filename, ".");
	if ($i !== false)
	{
		$ext = strtolower(substr($filename, $i));
		if (($it == IMAGETYPE_GIF && $ext != ".gif") || ($it == IMAGETYPE_JPEG && $ext != ".jpg") || ($it == IMAGETYPE_PNG && $ext != ".png"))
			stderr("Villa", "Röng skráarending: <b>$ext</b>");
	}
	else
		stderr("Villa", "Skráin verður að hafa endingu (.jpg/.gif/.png).");
	move_uploaded_file($file["tmp_name"], $tgtfile) or stderr("Villa", "Netþjónsvilla, vinsamlegast láttu umsjónarmenn vita.");
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
	stderr("Upload tókst", "Notaðu eftirfarandi slóð til að sjá myndina: <b><a href=\"$rurl\">$rurl</a></b><p><a href=/mynda-upload.php>Senda inn aðra mynd</a>.");
}

stdhead("Senda inn mynd");
?>
<h1>Senda inn mynd</h1>
<form method=post action="mynda-upload.php" enctype="multipart/form-data">
<p><b>Stærð myndar má ekki vera meiri en: <?=number_format($maxfilesize, 0, ',', '.'); ?> bæti.</b></p>
<table border=1 cellspacing=0 cellpadding=5>
<tr><td class=rowhead>Senda inn</td><td><input type=file name=file size=60></td></tr>
<tr><td colspan=2 align=center><input type=checkbox name=adult>Mynd inniheldur nekt eða annað særandi efni.</td></tr>
<tr><td colspan=2 align=center><input type=submit value="Byrja" class=btn></td></tr>
</table>
</form>
<p><a href=mymyndir.php>Mínar Myndir</a><br>
	<a href=myndaalbum.php>Myndaalbúm</a>
<p>
<table class=main width=410 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
<font class=small><b>Skilmálar:</b> Ekki senda inn myndir sem eru ólöglegar, óviðeigandi eða sem þið hafið ekki rétt til að birta. Notandi missir allan rétt á mynd sinni eftir að hún er send hingað inn, ekki senda inn myndir sem þú myndir ekki vilja að almenningur hefði aðgang að.</font>
</td></tr></table>
<?
stdfoot();

?>
