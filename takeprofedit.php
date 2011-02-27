<?

require_once("include/bittorrent.php");

hit_start();

function bark($msg) {
	genbark($msg, "Update failed!");
}

dbconn();

hit_count();

loggedinorreturn();

if (!mkglobal("email:chpassword:passagain"))
	bark("missing form data");

// $set = array();

$updateset = array();
$changedemail = 0;
$chpassword = $_POST["chpassword"];
$passagain = $_POST["passagain"];
if ($chpassword != "") {
	if (strlen($chpassword) > 40)
		bark("Sorry, password is too long (max is 40 chars)");
	if ($chpassword != $passagain)
		bark("The passwords didn't match. Try again.");

	$sec = mksecret();

  $passhash = md5($sec . $chpassword . $sec);

	$updateset[] = "secret = " . sqlesc($sec);
	$updateset[] = "passhash = " . sqlesc($passhash);
	logincookie($CURUSER["id"], $passhash);
}

$signiture = $_POST['signiture'];
if(!is_numeric($CURUSER['id']))
	die('Slæmt! Slæmt! Slæmt!');
if($signiture || empty($signiture)) {
//	$sign_before = file_get_contents($sign_dir.'/'.$CURUSER['id']);
	$sign_now = $signiture;
	if(empty($sign_now)) {
		$file = $sign_dir.'/'.$CURUSER['id'];
		@unlink($file);
	}
//	if($sign_before !== $sign_now) {
		if(!empty($sign_now))
			file_put_contents($sign_dir.'/'.$CURUSER['id'], substr($signiture, 0, 200));
		//forumlog($CURUSER['id'],$signiture,$sign_before,'signiture');
//	}
}

if($email != $CURUSER["email"]) {
	if (!validemail($email))
		bark("þetta lítur út fyrir að vera rangt netfang.");
  $r = mysql_query("SELECT id FROM users WHERE email=" . sqlesc($email)) or sqlerr(__FILE__,__LINE__);
	if (mysql_num_rows($r) > 0)
		bark("Netfangið $email er nú þegar í notkun.");
	$changedemail = 1;
}

$kennitala = $_POST['kennitala'];
$kt_rett = TRUE;
// Kennitala athuguð
if(strlen($kennitala) !== 10 || verifystring($kennitala, 'num') !== TRUE || substr($kennitala, 0, 2) >= '32' || substr($kennitala, 2, 2) >= '13' || substr($kennitala, -1, 1) !== '9')
	$kt_rett = false;
$summa = ($kennitala[0] * 3 + $kennitala[1] * 2 + $kennitala[2] * 7 + $kennitala[3] * 6 + $kennitala[4] * 5 + $kennitala[5] * 4 + $kennitala[6] * 3 + $kennitala[7] * 2);
$summa = 11-($summa % 11);
if($summa === 11)
	$summa = '0';
if($kt_rett != false)
	$kt_rett = (substr($summa, -1, 1) === substr($kennitala, -2, 1));

$notoplist = $_POST["notoplist"];
$birta_afm = $_POST["birta_afm"];
$birta_nytt = $_POST["birta_nytt"];
$undirskrift = $_POST["undirskrift"];
$avadult = $_POST["avadult"];
$hideadult = $_POST["hideadult"];
$acceptpms = $_POST["acceptpms"];
$deletepms = ($_POST["deletepms"] != "" ? "yes" : "no");
$savepms = ($_POST["savepms"] != "" ? "yes" : "no");
$pmnotif = $_POST["pmnotif"];
$emailnotif = $_POST["emailnotif"];
$notifs = ($pmnotif == 'yes' ? "[pm]" : "");
$notifs .= ($emailnotif == 'yes' ? "[email]" : "");
$r = mysql_query("SELECT id FROM categories") or sqlerr();
$rows = mysql_num_rows($r);
for ($i = 0; $i < $rows; ++$i)
{
	$a = mysql_fetch_assoc($r);
	if ($HTTP_POST_VARS["cat$a[id]"] == 'yes')
	  $notifs .= "[cat$a[id]]";
}
$avatar = $_POST["avatar"];
$avatars = ($_POST["avatars"] != "" ? "yes" : "no");
// $ircnick = $_POST["ircnick"];
// $ircpass = $_POST["ircpass"];
$info = $_POST["info"];
$titlechange = $_POST["titlechange"];
$stylesheet = $_POST["stylesheet"];
$country = $_POST["country"];
//$timezone = 0 + $_POST["timezone"];
//$dst = ($_POST["dst"] != "" ? "yes" : "no");
$privacy = $_POST["privacy"];
if (get_user_class() >= UC_MODERATOR) {
if ($privacy != "normal" && $privacy != "low" && $privacy != "strong")
	bark("Privacy stilling röng $privacy");

$updateset[] = "privacy = '$privacy'";
}

$updateset[] = "torrentsperpage = " . min(100, 0 + $_POST["torrentsperpage"]);
$updateset[] = "topicsperpage = " . min(100, 0 + $_POST["topicsperpage"]);
$updateset[] = "postsperpage = " . min(100, 0 + $_POST["postsperpage"]);
if($avadult == 'yes')
	$updateset[] = "avadult = 'yes'";
else
	$updateset[] = "avadult = 'no'";

if($notoplist == '1')
	$updateset[] = "notoplist = '1'";
else
	$updateset[] = "notoplist = '0'";

if($birta_afm == '1')
	$updateset[] = 'birta_afm = \'1\'';
else
	$updateset[] = 'birta_afm = \'0\'';

if($birta_nytt == '1')
	$updateset[] = 'birta_nytt = \'1\'';
else
	$updateset[] = 'birta_nytt = \'0\'';

if($kt_rett==true)
	$updateset[] = "kennitala = $kennitala";
$menuhide1 = $_POST['menuhide1'];
$menuhide2 = $_POST['menuhide2'];

if($menuhide1 == '1' && $menuhide2 == '2')
	$menuhide = '3';
elseif($menuhide1 == '1' && !is_string($menuhide2))
	$menuhide = '1';
elseif(!is_string($menuhide1) && $menuhide2 == '2')
	$menuhide = '2';
else
	$menuhide = '0';

$verifystring = verifystring("$menuhide",'num');
if($verifystring !== TRUE)
         die($verifystring);

$updateset[] = "menuhide = $menuhide";

if($undirskrift == '1')
$updateset[] = "undirskrift = '1'";
else
$updateset[] = "undirskrift = '0'";

if($hideadult == 'yes')
$updateset[] = "hideadult = 'yes'";
else
$updateset[] = "hideadult = 'no'";

if (is_valid_id($stylesheet))
  $updateset[] = "stylesheet = '$stylesheet'";
if (is_valid_id($country))
  $updateset[] = "country = $country";

//$updateset[] = "timezone = $timezone";
//$updateset[] = "dst = '$dst'";
$updateset[] = "info = " . sqlesc($info);
if($titlechange !== $CURUSER['title'] && ($CURUSER['class'] >= UC_POWER_USER || $CURUSER['donor'] === 'yes')) {
	forumlog($CURUSER['id'],$titlechange,$CURUSER['title'],title);
	$updateset[] = "title = " . sqlesc($titlechange);
	$CURUSER['title'] = $titlechange;
}
$updateset[] = "acceptpms = " . sqlesc($acceptpms);
$updateset[] = "deletepms = '$deletepms'";
$updateset[] = "savepms = '$savepms'";
$updateset[] = "notifs = '$notifs'";
$updateset[] = "avatar = " . sqlesc($avatar);
$updateset[] = "avatars = '$avatars'";
if ($_POST['resetpasskey']) $updateset[] = "passkey=''";
/* ****** */

$urladd = "";

if ($changedemail) {
	$sec = mksecret();
	$hash = md5($sec . $email . $sec);
	$obemail = urlencode($email);
	$updateset[] = "editsecret = " . sqlesc($sec);
	$thishost = $_SERVER["HTTP_HOST"];
	$thisdomain = preg_replace('/^www\./is', "", $thishost);
	$body = <<<EOD
Þú hefur beðið um að láta breyta netfangi fyrir notandanafnið {$CURUSER["username"]}
á $thisdomain í $email.

Ef þú gerði það ekki, vinsamlegast hunsaðu þennan póst.
Notandinn sem skráði þetta póstfang var með ip töluna {$_SERVER["REMOTE_ADDR"]}. Ekki svara þessum pósti

Til að staðfesta netfangið þitt vinsamlegast farðu á eftirfarandi hlekk:

http://$thishost/confirmemail.php/{$CURUSER["id"]}/$hash/$obemail

Nýja póstfangið þitt mun breytast eftir að þú hefur gert þetta,
annars mun það halda óbreytt.
EOD;

	mail($email, "$thisdomain póstfangs staðfesting", $body, "From: $SITEEMAIL\r\nReply-To:$SITEEMAIL");
	$urladd .= "&mailsent=1";
}

mysql_query("UPDATE users SET " . implode(",", $updateset) . " WHERE id = " . $CURUSER["id"]) or sqlerr(__FILE__,__LINE__);

echo "<meta http-equiv=\"refresh\" content=\"0;url=/my.php\">";

hit_end();

?>
