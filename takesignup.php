<?

require_once("include/bittorrent.php");

hit_start();

dbconn();

#$res = mysql_query("SELECT COUNT(*) FROM users") or sqlerr(__FILE__, __LINE__);
#$arr = mysql_fetch_row($res);
#if ($arr[0] >= $maxusers)
#	stderr("Villa", "Afsakið, netþjónninn er fullur. reynið aftur síðar.");

if (!mkglobal("wantusername:wantpassword:passagain:email"))
	die();

function bark($msg) {
  stdhead();
	stdmsg("Skráning mistókst!", $msg);
  stdfoot();
  exit;
}

function validusername($username)
{
	if ($username == "")
	  return false;

	// The following characters are allowed in user names
	$allowedchars = "aábcdðeéfghiíjklmnoópqrstuúvwxyýzþæöAÁBCDÐEÉFGHIÍJKLMNOÓPQRSTUÚVWXYZÞÆÖ0123456789";

	for ($i = 0; $i < strlen($username); ++$i)
	  if (strpos($allowedchars, $username[$i]) === false)
	    return false;

	return true;
}

function isportopen($port)
{
	global $HTTP_SERVER_VARS;
	$sd = @fsockopen($HTTP_SERVER_VARS["REMOTE_ADDR"], $port, $errno, $errstr, 1);
	if ($sd)
	{
		fclose($sd);
		return true;
	}
	else
		return false;
}
/*
function isproxy()
{
	$ports = array(80, 88, 1075, 1080, 1180, 1182, 2282, 3128, 3332, 5490, 6588, 7033, 7441, 8000, 8080, 8085, 8090, 8095, 8100, 8105, 8110, 8888, 22788);
	for ($i = 0; $i < count($ports); ++$i)
		if (isportopen($ports[$i])) return true;
	return false;
}
*/
if (empty($wantusername) || empty($wantpassword) || empty($email))
	bark("Ekki skilja eftir neina auða reiti.");

if (strlen($wantusername) > 20)
	bark("Notandanafn er of langt (mest má hafa 20 stafi)");

if ($wantpassword != $passagain)
	bark("Lykilorð passa ekki saman! Eflaust gert innsláttarvillu. Reyndu aftur.");

if (strlen($wantpassword) < 6)
	bark("Lykilorð er of stutt (minnst má hafa 6 stafi)");

if (strlen($wantpassword) > 40)
	bark("Lykilorð er of langt (mest má hafa 40 stafi)");

if ($wantpassword == $wantusername)
	bark("Lykilorð má ekki vera sama og notandanafn.");

if (!validemail($email))
	bark("Þetta lítur út fyrir að vera ógilt netfang.");

if (!validusername($wantusername))
	bark("Ógilt notandanafn.");

// make sure user agrees to everything...
if ($HTTP_POST_VARS["rulesverify"] != "yes" || $HTTP_POST_VARS["faqverify"] != "yes" || $HTTP_POST_VARS["ageverify"] != "yes")
	stderr("Skráning mistókst", "Því miður þá verðuru að staðfesta að þú hafir lesið reglurnar, munir lesa SOS áður en þú spyrð spurninga og sért 13 ára eða eldri.");

// check if email addy is already in use
$a = (@mysql_fetch_row(@mysql_query("select count(*) from users where email='$email'"))) or die(mysql_error());
if ($a[0] != 0)
  bark("Netfangið $email er nú þegar skráð á listann hjá okkur.");

$invid = $_POST['invite'];
if(!$invid)
	bark("Nauðsynlegt að skrá inn boðslykilinn");
$verifystring = verifystring($invid,'md5');
if($verifystring !== TRUE)
        bark('Þú slóst inn ógildan boðslykil. Þú þarft að fá boðslykil frá einhverjum sem er nú þegar meðlimur á Istorrent.');
$query = mysql_query("SELECT * FROM invites WHERE secret_hash = '$invid'") or sqlerr();
$invite = mysql_fetch_array($query);
$invitari = $invite['inviter_id'];
$sql = 'SELECT * FROM users WHERE id='.$invitari;
$res = mysql_query($sql);
if(mysql_num_rows($res) < '1')
	bark("Ekki tókst að fletta upp á bjóðanda.");
$checkinv = mysql_fetch_array($res);
if($checkinv['enabled'] === 'no' || $checkinv['deleted'] == '1' || $checkinv['warned'] === 'yes')
	bark('Bjóðandi má ekki vera óvirkur, eyddur eða hafa viðvörun.');
if($invite['email'] != $email)
	bark('Þessi boðslykill er eingöngu nothæfur til að búa til aðgang fyrir netfangið '.$invite['email']);
if(mysql_num_rows($query) < 1)
	bark("Þetta er rangur boðslykill");
mysql_query("UPDATE invites SET used=1 WHERE secret_hash = '$invid' AND email='$email'") or sqlerr();
hit_count();
$md5secret = md5(mksecret());
$secret = mksecret();
$wantpasshash = md5($secret . $wantpassword . $secret);
$editsecret = mksecret();

$ret = mysql_query("INSERT INTO users (username, passhash, secret, editsecret, email, enabled, md5secret, invitari, status, added) VALUES (" .
	implode(",", array_map("sqlesc", array($wantusername, $wantpasshash, $secret, $editsecret, $email, 'yes', $md5secret, $invitari, 'pending'))) .
		",'" . get_date_time() . "')");
$id = mysql_insert_id();

if (!$ret) {
	if (mysql_errno() == 1062) {
		bark("Notandanafn er nú þegar til!");
		}
	bark("borked");
}


//write_log("User account $id ($wantusername) was created");

$psecret = md5($editsecret);

$body = <<<EOD
Þessi tölvupóstur er sendur vegna þessa að einhver skráði þetta netfang ($email)
á torrent síðuna $SITENAME 

Ef þú skráðir þig ekki, vinsamlegast hunsaðu þennan póst.
Notandinn sem skráði þig var með ip töluna {$_SERVER["REMOTE_ADDR"]}. Ekki svara þessum pósti.

Til að staðfesta aðganginn þinn vinsamlegast farðu á eftirfarandi slóð:

$DEFAULTBASEURL/confirm.php?id=$id&secret=$psecret

Ef þú gerir þetta, getur þú byrjað að nota aðganginn þinn, ef ekki
þá verður aðgangi þínum sjálfkrafa eytt eftir nokkra daga.
EOD;
mail($email, "$SITENAME notanda staðfesting", $body, "From: $SITEEMAIL\r\nReply-To:$SITEEMAIL");

header("Refresh: 0; url=ok.php?type=signup&email=" . urlencode($email));

hit_end();

?>
