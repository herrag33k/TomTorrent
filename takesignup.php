<?

require_once("include/bittorrent.php");

hit_start();

dbconn();

#$res = mysql_query("SELECT COUNT(*) FROM users") or sqlerr(__FILE__, __LINE__);
#$arr = mysql_fetch_row($res);
#if ($arr[0] >= $maxusers)
#	stderr("Villa", "Afsaki�, net�j�nninn er fullur. reyni� aftur s��ar.");

if (!mkglobal("wantusername:wantpassword:passagain:email"))
	die();

function bark($msg) {
  stdhead();
	stdmsg("Skr�ning mist�kst!", $msg);
  stdfoot();
  exit;
}

function validusername($username)
{
	if ($username == "")
	  return false;

	// The following characters are allowed in user names
	$allowedchars = "a�bcd�e�fghi�jklmno�pqrstu�vwxy�z���A�BCD�E�FGHI�JKLMNO�PQRSTU�VWXYZ���0123456789";

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
	bark("Ekki skilja eftir neina au�a reiti.");

if (strlen($wantusername) > 20)
	bark("Notandanafn er of langt (mest m� hafa 20 stafi)");

if ($wantpassword != $passagain)
	bark("Lykilor� passa ekki saman! Eflaust gert innsl�ttarvillu. Reyndu aftur.");

if (strlen($wantpassword) < 6)
	bark("Lykilor� er of stutt (minnst m� hafa 6 stafi)");

if (strlen($wantpassword) > 40)
	bark("Lykilor� er of langt (mest m� hafa 40 stafi)");

if ($wantpassword == $wantusername)
	bark("Lykilor� m� ekki vera sama og notandanafn.");

if (!validemail($email))
	bark("�etta l�tur �t fyrir a� vera �gilt netfang.");

if (!validusername($wantusername))
	bark("�gilt notandanafn.");

// make sure user agrees to everything...
if ($HTTP_POST_VARS["rulesverify"] != "yes" || $HTTP_POST_VARS["faqverify"] != "yes" || $HTTP_POST_VARS["ageverify"] != "yes")
	stderr("Skr�ning mist�kst", "�v� mi�ur �� ver�uru a� sta�festa a� �� hafir lesi� reglurnar, munir lesa SOS ��ur en �� spyr� spurninga og s�rt 13 �ra e�a eldri.");

// check if email addy is already in use
$a = (@mysql_fetch_row(@mysql_query("select count(*) from users where email='$email'"))) or die(mysql_error());
if ($a[0] != 0)
  bark("Netfangi� $email er n� �egar skr�� � listann hj� okkur.");

$invid = $_POST['invite'];
if(!$invid)
	bark("Nau�synlegt a� skr� inn bo�slykilinn");
$verifystring = verifystring($invid,'md5');
if($verifystring !== TRUE)
        bark('�� sl�st inn �gildan bo�slykil. �� �arft a� f� bo�slykil fr� einhverjum sem er n� �egar me�limur � Istorrent.');
$query = mysql_query("SELECT * FROM invites WHERE secret_hash = '$invid'") or sqlerr();
$invite = mysql_fetch_array($query);
$invitari = $invite['inviter_id'];
$sql = 'SELECT * FROM users WHERE id='.$invitari;
$res = mysql_query($sql);
if(mysql_num_rows($res) < '1')
	bark("Ekki t�kst a� fletta upp � bj��anda.");
$checkinv = mysql_fetch_array($res);
if($checkinv['enabled'] === 'no' || $checkinv['deleted'] == '1' || $checkinv['warned'] === 'yes')
	bark('Bj��andi m� ekki vera �virkur, eyddur e�a hafa vi�v�run.');
if($invite['email'] != $email)
	bark('�essi bo�slykill er eing�ngu noth�fur til a� b�a til a�gang fyrir netfangi� '.$invite['email']);
if(mysql_num_rows($query) < 1)
	bark("�etta er rangur bo�slykill");
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
		bark("Notandanafn er n� �egar til!");
		}
	bark("borked");
}


//write_log("User account $id ($wantusername) was created");

$psecret = md5($editsecret);

$body = <<<EOD
�essi t�lvup�stur er sendur vegna �essa a� einhver skr��i �etta netfang ($email)
� torrent s��una $SITENAME 

Ef �� skr��ir �ig ekki, vinsamlegast hunsa�u �ennan p�st.
Notandinn sem skr��i �ig var me� ip t�luna {$_SERVER["REMOTE_ADDR"]}. Ekki svara �essum p�sti.

Til a� sta�festa a�ganginn �inn vinsamlegast far�u � eftirfarandi sl��:

$DEFAULTBASEURL/confirm.php?id=$id&secret=$psecret

Ef �� gerir �etta, getur �� byrja� a� nota a�ganginn �inn, ef ekki
�� ver�ur a�gangi ��num sj�lfkrafa eytt eftir nokkra daga.
EOD;
mail($email, "$SITENAME notanda sta�festing", $body, "From: $SITEEMAIL\r\nReply-To:$SITEEMAIL");

header("Refresh: 0; url=ok.php?type=signup&email=" . urlencode($email));

hit_end();

?>
