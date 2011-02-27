<?

/*
$verifystring = verifystring($string_to_verify,$type);
if($verifystring !== TRUE)
        die($verifystring);
 Use:
 1. Change $string_to_verify for the variable you are going to verify.
 2. Change $type to one of the types supported in this function. Wrong ones will result in an error.
 3. Put this code as close above the variable to be verified as possible. Without breaking the existing code though.
 If the function returns anything else than TRUE (case sensitive), an error message will display.
 The message is not too informative but should provide some clues for the debugger.
*/

function verifystring($string,$type) {
	if(!isset($string) || (empty($string) && $string !== '0'))
		return '$string not defined';
	switch($type) {
		case 'num':
			$chars = '0123456789';
			for($i=0;$i<strlen($string); $i++) {
				if(strpos($chars,$string[$i]) === false)
					return $string.' is not a number';
			}
		break;
		case 'md5':
			$chars = 'abcdef1234567890';
			if(strlen($string) != '32')
				return('md5 length is not 32');
			for($i=0;$i < '32';$i++) {
				if(strpos($chars,$string[$i]) === false)
					return 'invalid md5 string';
			}
		break;
		case 'email':
			// Check if an e-mail address has allowed symbols according to RFC

			// spares some characters in $localchars
			$string = strtolower($string);

			$email = explode('@',$string);
			$domain = explode('.',$email['1']);
			if(strlen($email['0']) > '64')
				return 'local-part is too long';
			if(strlen($email['1']) > '255')
				return 'domain-part is too long';
			// Characters allowed to be in the mailbox name
			$localchars = 'abcdefghijklmnopqrstuvwxyz1234567890,!#$%&*+-/=?^_`{|}~.\'';
			// Characters allowed in TLDs
			$tldchars = 'abcdefghijklmnopqrstuvwxyz';
			$tld = $domain[count($domain)-1];
			for($i=0;$i < strlen($tld);$i++) {
				if(strpos($tldchars,$tld[$i]) === false)
					return 'Invalid TLD - '.$tld;
			}
			// The character . is not allowed as the first or last of the local-part
			if($email['0']['0'] === '.' || $string[(strlen($email['0'])-1)] === '.')
				return 'Invalid "." character in e-mail address';
			for($i=0;$i < strlen($email['0']);$i++) {
				if(strpos($localchars,$string[$i]) === false)
					return 'Invalid e-mail';
			}
			// Check if the domain exists
				// RFC allows the lack of MX records so an extra function is done if FALSE on first one
				// Functions do not work on Windows platforms
				if(checkdnsrr($email['1'],'MX') === FALSE) {
					if(checkdnsrr($email['1'].'.','A') === FALSE)
						return 'No valid mail server records found for domain '.$email['1'];
				}
		break;
		default:
			return 'Type not specified';
		break;
	}
	// If the script sees no reason for error, return TRUE.
	return TRUE;
}

function update_topic_last_post($topicid) {
	$res = mysql_query("SELECT id FROM posts WHERE topicid=$topicid ORDER BY id DESC LIMIT 1") or sqlerr(__FILE__, __LINE__);

	$arr = mysql_fetch_row($res) or die("Enginn korkur fannst");

	$postid = $arr[0];

	mysql_query("UPDATE topics SET lastpost=$postid WHERE id=$topicid") or sqlerr(__FILE__, __LINE__);
}

function forumlog ($userid,$now,$before,$type) {
	global $CURUSER;
	if(!$CURUSER)
		$username = mysql_result(mysql_query('SELECT username FROM users WHERE id='.$userid),0);
	else
		$username = $CURUSER['username'];
	switch($type) {
		case title:
			$type = 'Titill';
			$subject = 'Notandi: '.$username."\n".'Tegund breytingar: '.$type."\n".'Núna: '.$now."\n".'Áður: '.$before;
		break;
		case signiture:
			$type = 'Undirskrift';
			if(empty($now))
				$now = 'Undirskrift eytt úr kerfinu';
			$subject = 'Notandi: '.$username."\n".'Tegund breytingar: '.$type."\n\n".'Núna: '."\n".$now."\n\n".'Áður: '."\n".$before;
		break;
		case cheat:
			$type = 'Svindl grunur';
			$subject = 'Notandi: '.$username."\n".'Tilkynnti 2GB eða meira í deilingarmagn - grunur um svindl'."\n\n".'Deilingarmagn: '.$now."\n".'Niðurhalsmagn: '.$before;
		break;
		case username:
			$type = 'Notandanafn';
			$subject = 'Breyting á notandanafni'."\n".'Núna: '.$now."\n".'Áður: '.$before;
		break;
	}

	$sql = 'INSERT INTO topics (userid, forumid, subject) VALUES('.$userid.', 12, \''.$username.' - '.$type.'\')'; 

	mysql_query($sql) or sqlerr(__FILE__, __LINE__);

	$topicid = mysql_insert_id() or sqlerr(__FILE__, __LINE__);

	$added = "'".get_date_time()."'";

	$sql = 'INSERT INTO posts (topicid, userid, added, body) VALUES ('.$topicid.','.$userid.','.$added.','.sqlesc($subject).')';

	mysql_query($sql) or sqlerr (__FILE__, __LINE__);

	$postid = mysql_insert_id();

	update_topic_last_post($topicid);

	if(verifystring(mysql_insert_id(),'num'))
		return TRUE;
	else
		return FALSE;
}

function slots($userid,$do = 'disp') {
	global $CURUSER;
	if($CURUSER['id'] !== $userid) {
		$user_sql = 'SELECT class,warned,donor,24rule AS rule24 FROM users WHERE id='.$userid;
		$user_results = mysql_query($user_sql);
		$row = mysql_fetch_object($user_results);
		$class = $row->class;
		$warned = $row->warned;
		$donor = $row->donor;
		$rule24 = $row->rule24;
	} else {
		$class = $CURUSER['class'];
		$warned = $CURUSER['warned'];
		$donor = $CURUSER['donor'];
		$rule24 = $CURUSER['24rule'];
	}

	if($rule24 === '1') {
		if($do === 'disp')
			return 'Ótakmarkaður fjöldi';
		elseif($do === 'free')
			return '1';
	}
	$addtime = time()-(2*24*60*60);
	$time = date('Y-m-d H:i:s', $addtime);
	$slots_sql = 'SELECT COUNT(DISTINCT(peers.torrent)) FROM peers,torrents WHERE peers.userid='.$userid.' AND torrents.added>=\''.$time.'\' AND (peers.torrent = torrents.id)';
	$slots = mysql_result(mysql_query($slots_sql),0);

	if($class == UC_BEGINNER)
		$slots_limit = '4';
	elseif($class == UC_USER)
		$slots_limit = '6';
	elseif($class == UC_GOOD_USER)
		$slots_limit = '8';
	if($class >= UC_POWER_USER || $donor === 'yes')
		$slots_limit = '12';
	// Warned users always have 2 slots
	if($warned == 'yes')
		$slots_limit = '2';

	if($do === 'disp')
		return $slots.' / '.$slots_limit;
	elseif($do === 'free')
		return ($slots_limit-$slots);
}

function requests_free ($userid) {
	global $CURUSER;
	if($CURUSER['class'] == UC_POWER_USER)
		return '1';
	elseif($CURUSER['class'] >= UC_GOOD_USER || $CURUSER['donor'] === 'yes') {
		if($CURUSER['id'] === $userid)
			$uploaded = $CURUSER['uploaded'];
		else
			$uploaded = mysql_result(mysql_query('SELECT uploaded FROM users WHERE id='.$userid),0);
		$request_sql = 'SELECT COUNT(*) FROM requests WHERE userid='.$userid;
		return floor($uploaded/(10*1024*1024*1024)-mysql_result(mysql_query($request_sql),0));
	} else
		return '0';
}

function find_unseeded ($userid, $type = 'all') {
	$addtime = time()-(1*24*60*60);
	$time = date('Y-m-d H:i:s', $addtime);
	$sql = 'SELECT torrents.id,torrents.name,torrents.added,users.24rule AS rule24,(SELECT COUNT(*) FROM peers WHERE torrent=torrents.id AND seeder=\'yes\') AS seeds FROM torrents,users WHERE torrents.owner='.$userid.' AND (users.id = torrents.owner) ORDER BY id DESC';
	$res = mysql_query($sql);
	if(mysql_num_rows($res)>'0') {
		$t1 = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400)));
		$output = '';
		while($row = mysql_fetch_array($res)) {
			$t2 = str_replace(array(' ',':','-'),'',$row['added']);
			if($row['seeds'] === '0' && $t1>$t2) {
				if($type === 'list') {
					$output .= '<a href="/details.php?id='.$row['id'].'">';
					if(!empty($row['name']))
						$output .= $row['name'];
					else
						$output .= '[Óskýrt torrent]';
					$output .= '</a>- <b>Þú ert ekki að deila þessari skrá.</b><br />';
				} else
					return '1';
			} elseif($row['seeds'] < '1' && $t2>$t1 && ($type == 'dl' || $type == 'list')) {
				if($type === 'list') {
					$output .= '<a href="/details.php?id='.$row['id'].'">';
					if(!empty($row['name']))
						$output .= $row['name'];
					else
						$output .= '[Óskýrt torrent]';
					$output .= '</a> - <b>Þú getur ekki náð í aðrar skrár fyrr en þú byrjar að deila þessari.</b><br />';
				} else
					return '1';
			} elseif($row['seeds'] < '2' && $t2>$t1 && $row['rule24'] !== '1' && ($type == 'all' || $type === 'list')) {
				if($type === 'list') {
					$output .= '<a href="/details.php?id='.$row['id'].'">';
					if(!empty($row['name']))
						$output .= $row['name'];
					else
						$output .= '[Óskýrt torrent]';
					$output .= '</a> - <b>Þú getur ekki sent inn aðra skrá fyrr en einhver annar er að hjálpa þér að deila þessari.</b><br />';
				} else
					return '1';
			}
		}
	return $output;
	} else
		return '0';
}

function find_AS ($ip,$tengi = 'nei') {
	$ranges = file('/www/antilink/is-net.txt');
	$ip = ip2long($ip);

	for($i=0;$i<count($ranges);$i++) {
		list($range) = explode('/', $ranges[$i]);
		$range = ip2long($range);
		if($ip >= $range)
			$iprange = $range;
	}
	switch($iprange) {
		case '1049722880':
			$AS = '15605';
		break;
		case '1358434304':
			$AS = '39418';
		break;
	        case '1359937536':
	                $AS = '15605';
	        break;
	        case '1383088128':
	                $AS = '31236';
	        break;
	        case '1385447424':
	                $AS = '29348';
	        break;
	        case '1390215168':
	                $AS = '30818';
	        break;
	        case '1403846656':
	                $AS = '31441';
	        break;
	        case '1433681920':
	                $AS = '34678';
	        break;
	        case '1439023104':
	                $AS = '34464';
	        break;
	        case '1440481280':
	                $AS = '6677';
	        break;
	        case '1475158016':
	                $AS = '35834';
	        break;
	        case '1486159872':
	                $AS = '34464';
	        break;
	        case '1486303232':
	                $AS = '39472';
	        break;
	        case '1503690752':
	                $AS = '15605';
	        break;
	        case '2194669568':
	                $AS = '15474';
	        break;
	        case '2606451200':
	                $AS = '6677';
	        break;
	        case '2644312064':
	                $AS = '6677';
	        break;
	        case '3230867968':
	                $AS = '6677';
	        break;
	        case '3238264832':
	                $AS = '12969';
	        break;
	        case '3245150208':
	                $AS = '24743';
	        break;
	        case '3261718528':
	                $AS = '6677';
	        break;
	        case '3264217088':
	                $AS = '12969';
	        break;
	        case '3436950528':
	                $AS = '6677';
	        break;
	        case '3436960768':
	                $AS = '6677';
	        break;
	        case '3558785024':
	                $AS = '6677';
	        break;
	        case '3565084672':
	                $AS = '25244';
	        break;
	        case '3584524288':
	                $AS = '6677';
	        break;
	        case '3585114112':
	                $AS = '12969';
	        break;
	        case '3585433600':
	                $AS = '31410';
	        break;
	        case '3586023424':
	                $AS = '25509';
	        break;
	        case '3587538944':
	                $AS = '12969';
	        break;
	        case '3587981312':
	                $AS = '15605';
	        break;
	        case '3641278464':
	                $AS = '12969';
	        break;
	        case '3642535936':
	                $AS = '29689';
	        break;
	        case '3650592768':
	                $AS = '12969';
	        break;
	        case '3651915776':
	                $AS = '15605';
	        break;
	        default:
	                return '0';
	        break;
	}

	if($tengi = 'nei')
		return $AS;
	else {
		switch($AS) {
			case '6677': // Landsíminn
				$AS[] = '24743';
				$AS[] = '29348';
				$AS[] = '35834';
				$AS[] = '12969';
				$AS[] = '15605';
				$AS[] = '29689';
			break;
			case '12969': // Og Vodafone
				$AS[] = '15605';
				$AS[] = '29689';
				$AS[] = '6677';
			break;
			case '15474': // RHnet
				$AS[] = '';
			break;
			case '15605': // Lína.net
				$AS[] = '12969';
				$AS[] = '29689';
				$AS[] = '6677';
			break;
			case '24743': // Snerpa
				$AS[] = '6677';
			break;
			case '25244': // DeCode
				$AS[] = '12969';
				$AS[] = '29689';
				$AS[] = '15605';
				$AS[] = '6677';
			break;
			case '25509': // Hringiðan (Vortex)
				$AS[] = '34678';
			break;
			case '29348': // FSnet
				$AS[] = '30818';
			break;
			case '29689': // TM Software
				$AS[] = '12969';
				$AS[] = '15605';
				$AS[] = '6677';
			break;
			case '30818': // Skýrr
				$AS[] = '29348';
				$AS[] = '15605';
				$AS[] = '12969';
				$AS[] = '6677';
				$AS[] = '29689';
			break;
			case '31236': // Reykjavíkurborg
				$AS[] = '12969';
				$AS[] = '15605';
			break;
			case '31410': // Netsamskipti
				$AS[] = '12969';
				$AS[] = '15605';
			break;
			case '31441': // Orkuveitan (4v)
				$AS[] = '12969';
				$AS[] = '15605';
			break;
			case '34464': // IP Fjarskipti (Hive)
				$AS[] = '';
			break;
			case '34678': // Tölvun - Vestmannaeyjum
				$AS[] = '25509';
			break;
			case '35834': // CCP
				$AS[] = '6677';
			break;
			case '39418': // Nýherji hýsingar
				$AS[] = '15605';
				$AS[] = '29689';
				$AS[] = '6677';
			break;
			case '39472': // KB banki
				$AS[] = '12969';
				$AS[] = '15605';
			break;
		}
		return $AS;
	}
}

// PHP5 with register_long_arrays off?
if (!isset($HTTP_POST_VARS) && isset($_POST))
{
$HTTP_POST_VARS = $_POST;
$HTTP_GET_VARS = $_GET;
$HTTP_SERVER_VARS = $_SERVER;
$HTTP_COOKIE_VARS = $_COOKIE;
$HTTP_ENV_VARS = $_ENV;
$HTTP_POST_FILES = $_FILES;
}

function strip_magic_quotes($arr)
{
foreach ($arr as $k => $v)
{
if (is_array($v))
{ $arr[$k] = strip_magic_quotes($v); }
else
{ $arr[$k] = stripslashes($v); }
}

return $arr;
}

if (get_magic_quotes_gpc())
{
if (!empty($_GET)) { $_GET = strip_magic_quotes($_GET); }
if (!empty($_POST)) { $_POST = strip_magic_quotes($_POST); }
if (!empty($_COOKIE)) { $_COOKIE = strip_magic_quotes($_COOKIE); }
}


// addslashes to vars if magic_quotes_gpc is off
// this is a security precaution to prevent someone
// trying to break out of a SQL statement.
//

if( !get_magic_quotes_gpc() )
{
if( is_array($HTTP_GET_VARS) )
{
while( list($k, $v) = each($HTTP_GET_VARS) )
{
if( is_array($HTTP_GET_VARS[$k]) )
{
while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
{
$HTTP_GET_VARS[$k][$k2] = addslashes($v2);
}
@reset($HTTP_GET_VARS[$k]);
}
else
{
$HTTP_GET_VARS[$k] = addslashes($v);
}
}
@reset($HTTP_GET_VARS);
}

if( is_array($HTTP_POST_VARS) )
{
while( list($k, $v) = each($HTTP_POST_VARS) )
{
if( is_array($HTTP_POST_VARS[$k]) )
{
while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
{
$HTTP_POST_VARS[$k][$k2] = addslashes($v2);
}
@reset($HTTP_POST_VARS[$k]);
}
else
{
$HTTP_POST_VARS[$k] = addslashes($v);
}
}
@reset($HTTP_POST_VARS);
}

if( is_array($HTTP_COOKIE_VARS) )
{
while( list($k, $v) = each($HTTP_COOKIE_VARS) )
{
if( is_array($HTTP_COOKIE_VARS[$k]) )
{
while( list($k2, $v2) = each($HTTP_COOKIE_VARS[$k]) )
{
$HTTP_COOKIE_VARS[$k][$k2] = addslashes($v2);
}
@reset($HTTP_COOKIE_VARS[$k]);
}
else
{
$HTTP_COOKIE_VARS[$k] = addslashes($v);
}
}
@reset($HTTP_COOKIE_VARS);
}
}
function local_user()
{
  global $HTTP_SERVER_VARS;

  return $HTTP_SERVER_VARS["SERVER_ADDR"] == $HTTP_SERVER_VARS["REMOTE_ADDR"];
}
$FUNDS = "$2,610.31";

$SITE_ONLINE = true;
//$SITE_ONLINE = local_user();
//$SITE_ONLINE = false;

$TESTING_SITE = 'test.torrent.is';

if($_SERVER['SERVER_NAME'] === $TESTING_SITE)
	error_reporting(E_ALL);
$max_torrent_size = 10485760;
$announce_interval = 3600;
$signup_timeout = 86400 * 3;
$minvotes = 1;
$max_dead_torrent_time = 6 * 3600;
$sign_dir = '/www/torrent.is/www/undirskr';

// Max users on site
$maxusers = 50000;

$torrent_dir = $_SERVER['DOCUMENT_ROOT'].'/download';    # must be writable for httpd user


# the first one will be displayed on the pages
$announce_urls = array();
if($_SERVER['SERVER_NAME'] == $TESTING_SITE)
	$announce_urls[] = 'http://'.$TESTING_SITE.'/announce.php';
$announce_urls[] = "http://torrent.is/announce.php";
$announce_urls[] = "http://torrent.stuff.is/announce.php";

if ($HTTP_SERVER_VARS["HTTP_HOST"] == "")
  $HTTP_SERVER_VARS["HTTP_HOST"] = $HTTP_SERVER_VARS["SERVER_NAME"];
$BASEURL = "http://" . $HTTP_SERVER_VARS["HTTP_HOST"] . "";

// Set this to your site URL... No ending slash!
if($_SERVER['SERVER_NAME'] != $TESTING_SITE)
	$DEFAULTBASEURL = 'http://torrent.is';
else
	$DEFAULTBASEURL = 'http://'.$TESTING_SITE;;

//set this to true to make this a tracker that only registered users may use
$MEMBERSONLY = true;

//maximum number of peers (seeders+leechers) allowed before torrents starts to be deleted to make room...
//set this to something high if you don't require this feature
$PEERLIMIT = 50000;

// Email for sender/return path.
$SITEEMAIL = "torrent@torrent.is";

$SITENAME = "Istorrent";

$autoclean_interval = 900;

$pic_base_url = "/pic/";
$table_cat = "categories";
$forum_pics = "/pic";

require_once("secrets.php");
require_once("cleanup.php");


/**** validip/getip courtesy of manolete <manolete@myway.com> ****/

// IP Validation
function checkinvite($invitekey) {
 $invitesleft = mysql_result(mysql_query("SELECT invites from users WHERE md5nafn = $invitekey"));
 if($invitesleft > 0)
    {
      mysql_query("UPDATE users SET invites = invites - 1 WHERE md5nafn = $invitekey");
      return true;
    }
 else
    {
      return false;
    }
}
function validip($ip)
{
	if (!empty($ip) && ip2long($ip)!=-1)
	{
		// reserved IANA IPv4 addresses
		// http://www.iana.org/assignments/ipv4-address-space
		$reserved_ips = array (
				array('0.0.0.0','2.255.255.255'),
				array('10.0.0.0','10.255.255.255'),
				array('127.0.0.0','127.255.255.255'),
				array('169.254.0.0','169.254.255.255'),
				array('172.16.0.0','172.31.255.255'),
				array('192.0.2.0','192.0.2.255'),
				array('192.168.0.0','192.168.255.255'),
				array('255.255.255.0','255.255.255.255')
		);

		foreach ($reserved_ips as $r)
		{
				$min = ip2long($r['0']);
				$max = ip2long($r['1']);
				if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
		}
		return true;
	}
	else return false;
}

// Patched function to detect REAL IP address if it's valid
function getip() {
	if (isset($_SERVER['HTTP_CLIENT_IP']) && validip($_SERVER['HTTP_CLIENT_IP']))
		return $_SERVER['HTTP_CLIENT_IP'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$forwarded=str_replace(",","",$_SERVER['HTTP_X_FORWARDED_FOR']);
		$forwarded_array=split(" ",$forwarded);
		foreach($forwarded_array as $value)
			if (validip($value))
				return $value;
	}
	return $_SERVER['REMOTE_ADDR'];
}

function dbconn($autoclean = false)
{
    global $mysql_host, $mysql_user, $mysql_pass, $mysql_db;

    if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
    {
	  switch (mysql_errno())
	  {
		case 1040:
		case 2002:
			if ($_SERVER[REQUEST_METHOD] == "GET")
				die('<html><head><meta http-equiv="refresh" content="10" '.$_SERVER[REQUEST_URI].'"></head><body><table border="0" width="100%" height="100%"><tr><td><h3 align="center">Álag á vefþjóninum. Reyni aftur, gjörðu svo vel að bíða aðeins...</h3></td></tr></table></body></html>');
			else
				die("Of margir notendur. Vinsamlegast ýttu á Refresh takkann til þess að reyna aftur.");
        default:
    	    die("[" . mysql_errno() . "] dbconn: mysql_connect: " . mysql_error());
      }
    }
    mysql_select_db($mysql_db)
        or die('dbconn: mysql_select_db: ' + mysql_error());

    userlogin();

    if ($autoclean)
        register_shutdown_function("autoclean");
}

function inviteleft($id,$uploaded,$downloaded,$warned,$added) {
	global $CURUSER;
	if($CURUSER['class'] >= UC_USER || $CURUSER['donor'] === 'yes') {
		@$ratio = $uploaded/$downloaded;
		$t_medlimur = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400*14)));
		$t_medlimur2 = str_replace(array(' ',':','-'),'',$added);
		if($warned == 'yes' || $ratio <= '0.85' || $t_medlimur2 > $t_medlimur)
			return '0';
		$s = $uploaded-(25*1024*1024*1024);
	        if($s > 0) {
	                $s = floor($s/(5*1024*1024*1024));
	                $sql_inv = 'SELECT COUNT(*) FROM users WHERE invitari = '.$id.' AND deleted=0';
	                $s2 = mysql_result(mysql_query($sql_inv),0);
			$sql_inv2 = 'SELECT COUNT(*) FROM invites WHERE inviter_id='.$id.' AND used=0';
			$s3 = mysql_result(mysql_query($sql_inv2),0);
	                $inviteleft = ($s-$s2-$s3);
			if($inviteleft < 0)
				$inviteleft = '0';
		} else
			$inviteleft = '0';
		return $inviteleft;
	} else
		return '0';
}

function userlogin() {
    global $HTTP_SERVER_VARS, $SITE_ONLINE;
    unset($GLOBALS["CURUSER"]);

    $ip = getip();
//	$nip = ip2long($ip);
//    $res = mysql_query("SELECT * FROM bans WHERE first <= $nip AND last >=$nip") or sqlerr(__FILE__, __LINE__);
//    if (mysql_num_rows($res) > 0)
//    {
//      header("HTTP/1.0 403 Forbidden");
//      print("<html><body><h1>Ip talan þín hefur verið bönnuð!</h1>Þú getur haft samband við okkur á -> <a href=\"mailto:torrent@torrent.is\">torrent@torrent.is</a>.</body></html>\n");
//      die;
//    }

    if (!$SITE_ONLINE || empty($_COOKIE["uid"]) || empty($_COOKIE["pass"]))
        return;
    $id = 0 + $_COOKIE["uid"];
    if (!$id || strlen($_COOKIE["pass"]) != 32)
        return;
    $res = mysql_query("SELECT * FROM users WHERE id = $id AND enabled='yes' AND status = 'confirmed'");// or die(mysql_error());
    $row = mysql_fetch_array($res);
    if (!$row)
        return;
	if($row['deleted'] == '1')
		return;
    $sec = hash_pad($row["secret"]);
    if ($_COOKIE["pass"] !== $row["passhash"])
        return;
if (($ip != $row["ip"]) && $row["ip"])
	mysql_query("INSERT INTO iplog (ip, userid, access) VALUES (" . sqlesc($row["ip"]) . ", " . $row["id"] . ", '" . $row["last_access"] . "')");
    mysql_query("UPDATE users SET last_access='" . get_date_time() . "', ip='$ip' WHERE id=" . $row["id"]);// or die(mysql_error());
    $row['ip'] = $ip;
    $GLOBALS["CURUSER"] = $row;
	if(!isset($_SESSION['lasttorrent']))
		$_SESSION['lasttorrent'] = $row['lasttorrent'];
}

function autoclean() {
    global $autoclean_interval;

    $now = time();
    $docleanup = 0;

    $res = mysql_query("SELECT value_u FROM avps WHERE arg = 'lastcleantime'");
    $row = mysql_fetch_array($res);
    if (!$row) {
        mysql_query("INSERT INTO avps (arg, value_u) VALUES ('lastcleantime',$now)");
        return;
    }
    $ts = $row[0];
    if ($ts + $autoclean_interval > $now)
        return;
    mysql_query("UPDATE avps SET value_u=$now WHERE arg='lastcleantime' AND value_u = $ts");
    if (!mysql_affected_rows())
        return;

    docleanup();
}

function unesc($x) {
    if (get_magic_quotes_gpc())
        return stripslashes($x);
    return $x;
}

function mksize($bytes)
{
	if ($bytes < 1000 * 1024)
		return number_format($bytes / 1024, 2) . " kB";
	elseif ($bytes < 1000 * 1048576)
		return number_format($bytes / 1048576, 2) . " MB";
	elseif ($bytes < 1000 * 1073741824)
		return number_format($bytes / 1073741824, 2) . " GB";
	else
		return number_format($bytes / 1099511627776, 2) . " TB";
}

function mksizekb($bytes)
{
  return number_format($bytes / 1024) . " KB";
}

function mksizemb($bytes)
{
  return number_format($bytes / 1048576,2) . " MB";
}

function mksizegb($bytes)
{
  return number_format($bytes / 1073741824,2) . " GB";
}

function deadtime() {
    global $announce_interval;
    return time() - floor($announce_interval * 1.3);
}

function mkprettytime($s) {
    if ($s < 0)
        $s = 0;
    $t = array();
    foreach (array("60:sec","60:min","24:hour","0:day") as $x) {
        $y = explode(":", $x);
        if ($y[0] > 1) {
            $v = $s % $y[0];
            $s = floor($s / $y[0]);
        }
        else
            $v = $s;
        $t[$y[1]] = $v;
    }

    if ($t["day"])
        return $t["day"] . "d " . sprintf("%02d:%02d:%02d", $t["hour"], $t["min"], $t["sec"]);
    if ($t["hour"])
        return sprintf("%d:%02d:%02d", $t["hour"], $t["min"], $t["sec"]);
//    if ($t["min"])
        return sprintf("%d:%02d", $t["min"], $t["sec"]);
//    return $t["sec"] . " secs";
}

function mkglobal($vars) {
    if (!is_array($vars))
        $vars = explode(":", $vars);
    foreach ($vars as $v) {
        if (isset($_GET[$v]))
            $GLOBALS[$v] = unesc($_GET[$v]);
        elseif (isset($_POST[$v]))
            $GLOBALS[$v] = unesc($_POST[$v]);
        else
            return 0;
    }
    return 1;
}

function tr($x,$y,$noesc=0) {
    if ($noesc)
        $a = $y;
    else {
        $a = htmlspecialchars($y);
        $a = str_replace("\n", "<br />\n", $a);
    }
    print("<tr><td class=\"heading\" valign=\"top\" align=\"right\">$x</td><td valign=\"top\" align=left>$a</td></tr>\n");
}

function validfilename($name) {
    return preg_match('/^[^\0-\x1f:\\\\\/?*\xff#<>|]+$/si', $name);
}

function validemail($email) {
    return preg_match('/^[\w.-]+@([\w.-]+\.)+[a-z]{2,6}$/is', $email);
}

function sqlesc($x) {
    return "'".mysql_real_escape_string($x)."'";
}

function sqlwildcardesc($x) {
    return str_replace(array("%","_"), array("\\%","\\_"), mysql_real_escape_string($x));
}

function urlparse($m) {
    $t = $m[0];
    if (preg_match(',^\w+://,', $t))
        return "<a href=\"$t\">$t</a>";
    return "<a href=\"http://$t\">$t</a>";
}

function parsedescr($d, $html) {
    if (!$html)
    {
      $d = htmlspecialchars($d);
      $d = str_replace("\n", "\n<br />", $d);
    }
    return $d;
}

function stdhead($title = "", $msgalert = true) {
    global $CURUSER, $HTTP_SERVER_VARS, $PHP_SELF, $SITE_ONLINE, $FUNDS, $SITENAME, $TESTING_SITE;

$SRV_NAME = $_SERVER['SERVER_NAME'];
  if($SRV_NAME != 'torrent.is' && $SRV_NAME != $TESTING_SITE && $SRV_NAME != 'torrent.stuff.is') {
	$url = $_SERVER['REQUEST_URI'];
	header("location: http://torrent.is$url");
	}
  if (!$SITE_ONLINE)
    die("Vefurinn er niðri vegna viðhalds, hann kemur upp von bráðar...takk<br />");

    //header("Content-Type: text/html; charset=iso-8859-1");
    //header("Pragma: No-cache");
    if ($title == "")
        $title = $SITENAME;
    else
        $title = "$SITENAME :: " . htmlspecialchars($title);
	if (isset($CURUSER)) {
		$ss_a = @mysql_fetch_array(@mysql_query("select uri from stylesheets where id=" . $CURUSER["stylesheet"]));
		if ($ss_a)
			$ss_uri = $ss_a['uri'];
	}
	if (!isset($ss_uri)) {
		($r = mysql_query("SELECT uri FROM stylesheets WHERE id=1")) or die(mysql_error());
		($a = mysql_fetch_array($r)) or die(mysql_error());
		$ss_uri = $a["uri"];
	}
  if ($msgalert && $CURUSER)
  {
    $res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " && unread='yes'") or die("OopppsY!");
    $arr = mysql_fetch_row($res);
    $unread = $arr[0];
  }

include('ipcheck.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2002/REC-xhtml1-20020801/DTD/xhtml1-transitional.dtd"> 
<html>
<head>
	<title><?= $title ?></title>
	<link rel="stylesheet" href="/<?=$ss_uri?>" type="text/css" />
	<link rel="alternate" type="application/rss+xml" title="IsTorrent" href="/rss.xml" />
	<link rel="alternate" type="application/rss+xml" title="IsTorrent - Beint Niðurhal" href="/rssdd.xml" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
</head>
<body>

<table width="100%" cellspacing="0" cellpadding="0" style="background: transparent">
<tr>
<td class="clear" width="100%">

<table border="0px" cellspacing="0" cellpadding="0" width="100%" style="background:transparent">
<tr style="width:100%">

<td class="clear" align="left">
<div align="center">
<?
if(substr_count($_SERVER['HTTP_USER_AGENT'], 'MSIE') == '0') { ?>
<img src="/pic/logo2.png" align="left" alt="Istorrent merkið" />
<? } else {?>
<img src="/pic/logo2.gif" align="left" alt="Istorrent merkið" />
<? } ?>
</div>
</td>
<td class="clear" align="right" valign="bottom">
<a href="http://www.utorrent.com" target="_new"><img border="0px" alt="Náðu í µtorrent!" src="/pic/utorrent
<?
if(substr_count($_SERVER['HTTP_USER_AGENT'], 'MSIE') == '0')
	echo '.png';
else
	echo '.gif';
?>
" /></a></td>

</tr>
</table>


</td>
<td class="clear">
</td>
<td class="clear">
</td>
<td class="clear" width="49%" align="right"></td>
</tr></table>
<?php

$w = 'width="100%"';
//if ($HTTP_SERVER_VARS["REMOTE_ADDR"] == $HTTP_SERVER_VARS["SERVER_ADDR"]) $w = "width=984";

?>
<? if (isset($CURUSER)) { 
$uid = $CURUSER['id'];
$nidur_sql = 'SELECT torrent,uploaded,downloaded FROM peers WHERE userid='.$uid.' AND seeder=\'no\'';
$nidur = mysql_num_rows(mysql_query($nidur_sql));
$upp_sql = 'SELECT torrent,uploaded,downloaded FROM peers WHERE userid='.$uid.' AND seeder=\'yes\'';
$upp = mysql_num_rows(mysql_query($upp_sql));
?>
  <br /><table class="main" border="0" width="100%">
  <tr>
    <td class="vinstri" valign="top"><? echo 'Þú ert skráður inn sem: <a href="userdetails.php?id='.$CURUSER['id'] . '">' . $CURUSER['username'].'</a> ';
	echo '[<a href="/minar_eftirsp.php">Mínar eftirspurnir</a>] ';
	echo '[<a href="/mytorrents.php">Mín torrent</a>] ';
	echo '[<a href="/logout.php">Skr&aacute; &uacute;t</a>]';
	?><br />
    <? 
    echo 'Niðurhal: '.mksize($CURUSER['downloaded']).'<br />'."\n";
    echo 'Deilimagn: '.mksize($CURUSER['uploaded']).'<br />'."\n";
if(@is_numeric($CURUSER['uploaded']/$CURUSER['downloaded']))
	$reikn_ratio = round($CURUSER['uploaded']/$CURUSER['downloaded'],2);
else
	$reikn_ratio = 'Þú verður að ná í eitthvað til að talan verði reiknuð út';
    echo 'Hlutfall: '.number_format($reikn_ratio,2);
echo ' - ';
if($reikn_ratio >= '200.0' && $CURUSER['uploaded'] >= (50*1024*1024*1024))
	echo 'Það er dáldið sem kallast "úti"...';
elseif($reikn_ratio >= '100.0' && $CURUSER['uploaded'] >= (50*1024*1024*1024))
	echo 'Núna ertu "deil-a-holic"!';
elseif($reikn_ratio >= '10.0')
	echo 'ro-ro-ro-ro-rooosa hlutfall!';
elseif($reikn_ratio >= '3.0')
	echo 'Últra-hlutfall!';
elseif($reikn_ratio >= '1.0')
	echo 'Mjög gott hjá þér!';
elseif($reikn_ratio < '1' && $reikn_ratio >= '0.75')
	echo 'Góður árangur en má gera betur!';
elseif($reikn_ratio < '0.75' && $reikn_ratio >= '0.5')
	echo 'Deilir rétt yfir helmingnum af því gagnamagni sem þú sækir...þarft að bæta þig!';
elseif($reikn_ratio < '0.5' && $reikn_ratio > '0.2')
	echo 'Hræðilegt...eitthvað neðar og þú gætir lent í banni!';
elseif($reikn_ratio < '0.2')
	echo 'Ef þú heldur þessu hlutfalli 2 vikum eftir að þú nýskráðir þig, verðurðu örugglega bannaður!';
echo '- <a href="/hlutfoll.php">Um hlutföll</a><br />'."\n";
if($reikn_ratio <= '0.5')
	echo 'Það er samt ekki heilbrigt að deila bara einhverju. Deildu bara 2-3 skrám í einu og ekki einhverju sem er þegar hérna!<br />'."\n";
	echo 'Hólf: '.slots($CURUSER['id'], 'disp').' - Eingöngu 48 klst. og yngri torrent teljast sem hólf<br />';
    print("Færslur í gangi: <img border=0 src=ismod/upp.gif>:$upp\n");
    print("<img border=0 src=ismod/nidur.gif>:$nidur\n");?></td>
    <td align=right class=hagri valign=top>
<?
$dagur = date('w');
switch($dagur) {
	case '0':
	$dagur = 'Sunnu';
	break;
	case '1':
	$dagur = 'Mánu';
	break;
	case '2':
	$dagur = 'Þriðju';
	break;
	case '3':
	$dagur = 'Miðviku';
	break;
	case '4':
	$dagur = 'Fimmtu';
	break;
	case '5':
	$dagur = 'Föstu';
	break;
	case '6':
	$dagur = 'Laugar';
	break;
}

$man = date('n');
switch($man) {
	case '1': $man = 'janúar'; break;
	case '2': $man = 'febrúar'; break;
	case '3': $man = 'mars'; break;
	case '4': $man = 'apríl'; break;
	case '5': $man = 'maí'; break;
	case '6': $man = 'júní'; break;
	case '7': $man = 'júlí'; break;
	case '8': $man = 'ágúst'; break;
	case '9': $man = 'september'; break;
	case '10': $man = 'október'; break;
	case '11': $man = 'nóvember'; break;
	case '12': $man = 'desember'; break;
}
echo $dagur.'dagurinn '.date('j. ').$man.' '.date('Y').'<br />';
echo 'Klukkan er: ' . date('H'.':'.'i'.':'.'s');?><br />
    <?$res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " AND location IN ('in', 'both')") or print(mysql_error());
$arr = mysql_fetch_row($res);
$messages = $arr[0];
$res = mysql_query("SELECT COUNT(*) FROM messages WHERE receiver=" . $CURUSER["id"] . " AND location IN ('in', 'both') AND unread='yes'") or print(mysql_error());
$arr = mysql_fetch_row($res);
$unread = $arr[0];
$res = mysql_query("SELECT COUNT(*) FROM messages WHERE sender=" . $CURUSER["id"] . " AND location IN ('out', 'both')") or print(mysql_error());
$arr = mysql_fetch_row($res);
$outmessages = $arr['0'];
print("<a href=inbox.php><img border=0 src=ismod/inn.gif></a>: $messages\n");
print("<a href=inbox.php?out=1><img border=0 src=ismod/ut.gif></a>: $outmessages<br />\n");
echo '<a href="invites.php">Boðslyklar eftir</a>: '.inviteleft($CURUSER['id'],$CURUSER['uploaded'],$CURUSER['downloaded'],$CURUSER['warned'],$CURUSER['added']).'<br />'."\n";
if($CURUSER['class'] == UC_POWER_USER)
	echo '<a href="requests.php">Ótakmarkaður eftirspurnarfjöldi</a><br />';
else if($CURUSER['class'] >= UC_GOOD_USER || $CURUSER['donor'] === 'yes')
	echo '<a href="/requests.php">Ónotaðar eftirspurnir: '.requests_free($CURUSER['id']).'</a><br />';
?></td>
  </tr>
  </table>
  <br />
<? } ?>
<table class="mainouter" <?=$w; ?> border="1" cellspacing="0" cellpadding="10">

<?
// Menu starts

 $fn = substr($PHP_SELF, strrpos($PHP_SELF, "/") + 1); ?>
<tr><td class="outer" align="center">
<table align="center" class="main" cellspacing="0" cellpadding="5" border="0px" width="100%">
<tr>
<td class="navigation" align="center">
<a href="/">Aðalsíða</a> - 
<a href="/browse.php">Skrár</a> - 
<a href="/upload.php">Deila</a> - 
<? if (!$CURUSER) { ?>
<a href="/login.php">Innskráning</a> / <a href="/signup.php">Nýskráning</a> - 
<? } else { ?>
	<a href="/my.php">Prófíll</a> - 
<? } ?>
<a href="/viewrequests.php">Eftirspurnir</a> - 
<a href="/forums.php">Spjallborð</a> - 
<a href="/staff.php">Stjórnendur</a> - 
<a href="/veftre.php">Veftré</a>
</td>
</tr>
<?
if($CURUSER['menuhide'] != '1' && $CURUSER['menuhide'] != '3') {
?>
<tr>
<td align="center" class="navigation">
Upplýsingar: 
<a href="/um.php">Um Istorrent</a> - 
<a href="/styrkir.php">Styrkja</a> - 
<a href="/topten.php">Topp 10</a> - 
<?
if($CURUSER['class'] >= UC_MODERATOR)
	echo '<a href="/log.php">Aðgerðaskrá</a> - ';
?>
<a href="irc://irc.simnet.is:6667/istorrent">Spjall (IRC)</a> - 
<a href="/rules.php">Reglur</a> - 
<a href="/disclaimer.php?form=nei">Skilmálar</a>
</td>
</tr>
<?
}
if($CURUSER['menuhide'] != '2' && $CURUSER['menuhide'] != '3') {
?>
<tr>
<td align="center" class="navigation">
Hjálp:
<a class="navigation" href="/utorrent.php">µtorrent leiðbeiningar</a> - 
<a href="/faq.php">Spurt og svarað</a> - 
<a href="/hjalp.php">Hjálparkerfi</a> - 
<a href="/vandamal.php">Vandamál?</a> - 
<a href="/1skipti.php">Fyrsta skiptið hér?</a>
</td>
</tr>
<?
}

if($CURUSER['class'] >= UC_MODERATOR)
	echo '<tr><td style="text-align:center">Matarfundur kl. 18:00 þann 22. október - <a href="/forums.php?action=viewtopic&topicid=6285">Fundayfirlit</a></td></tr>';
$t1 = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - (14*24*60*60))));
$t2 = str_replace(array(' ',':','-'),'',$CURUSER['added']);
if($t2>=$t1)
	echo '<tr><td style="text-align:center">Góð upphafslesning til að koma þér af stað - <a href="/1skipti.php">Leiðbeiningar fyrir fyrsta skiptið</a></td></tr>';
// Setja inn afmæli dagsins frá cache
$afmaeli = file_get_contents('cache-birthday.txt');
echo '<tr><td style="text-align:center">'.$afmaeli.'</td></tr>';
//echo '<tr><td style="text-align:center"><b>Úrslit í fríhelgarkeppninni verða tilkynnt kl. 18 í dag á spjallrásinni!</b></td></tr>';

$upphaf = '20060804180000'; // Upphaf fríhelgar
$endir = '20060807235959'; // Lok fríhelgar
$today = date('YmdHis');
if($today >= $upphaf && $today <= $endir)
	echo '<tr><td style="text-align:center"><a href="/forums.php?action=viewtopic&topicid=6205">Fríhelgi er í gangi</a> og það er <a href="/forums.php?action=viewtopic&topicid=6311">keppni í gangi</a> á meðan hún er! Verðlaun í boði fyrir vinningshafana.</td></tr>';

$warneduntil = $CURUSER['warneduntil'];
if(($warneduntil != '0000-00-00 00:00:00' || $CURUSER['warned'] == 'yes') && $CURUSER['username'] != '') {
	echo '<tr><td colspan="14">Þú hefur gilda viðvörun sem rennur út klukkan '.substr($warneduntil,11).' þann '.substr($warneduntil,8,2).'/'.substr($warneduntil,5,2).' '.substr($warneduntil,0,4).'</td></tr>';
}
?>
</table>
</td>
</tr>
<tr><td align="center" class="outer" style="padding-top:20px;padding-bottom:20px">
<?
if (!empty($_POST['samtyk'])) {
	mysql_query('UPDATE users SET skilmalar = 1 WHERE id = '. $CURUSER['id']);
	echo '<meta http-equiv="refresh" content="0;url=/">';
}
if (isset($CURUSER) && $CURUSER['skilmalar'] === '0') {
	include("skilmalar.php");
	exit();
}

if(isset($CURUSER)) {
	$email_split = explode('@', $CURUSER['email']);

	if ($email_split['1'] == 'hotmail.com' || $email_split['1'] == 'msn.com') {
		echo '<p><table border="0" cellspacing="0" cellpadding="10" bgcolor="white"><tr><td style="padding:10px;background:white">';
		echo '<b>Vegna vandræða með staðfestingarpósta á hotmail.com og msn.com netföngum eru notendur hvattir til að skipta um netfang.<br />Hægt er að gera þetta í <a href="http://torrent.torrent.is/my.php">Prófíl</a>. Þessi skilaboð munu birtast þangað til þú hefur breytt.</b>';
		echo '</td></tr></table></p>'."\n";
	}
}
if($CURUSER['kennitala'] && substr($CURUSER['kennitala'], 0, 4) === date('dm'))
	echo '<table border="0" cellspacing="0" cellpadding="10" bgcolor="white"><tr><td>Til hamingju með afmælið! Í tilefni af því mun niðurhal þitt fyrir daginn í dag vera dregið frá eftir miðnætti á eftir!</td></tr></table>';

if (!empty($unread))
{
	echo '<p><table border="0" cellspacing="0" cellpadding="10" bgcolor="red"><tr><td style="padding: 10px;background:red">'."\n";
	echo '<b><a href="'.$BASEURL.'/inbox.php"><font color="white">Þú átt '.$unread.' '.($unread > 1 ? 'ólesin' : 'ólesið').' bréf!</font></a></b>';
	echo '</td></tr></table></p>'."\n";
}

if(!isset($_GET['uploaded']) && $CURUSER && find_unseeded($CURUSER['id'],'dl') === '1') {
	echo '<p><table border="0" cellspacing="0" cellpadding="10" bgcolor="red"><tr><td style="padding:10px;background:red">'."\n";
	echo '<b><a href="/hjalp.php?cat=6&ansid=15" style="color:white">Eitthvað torrent sem þú hefur sent inn hefur engan deilanda.<br />
		Nánari skýring á þessum villuboðum fæst með því að klikka á textann í þessum kassa.<br />
		Sértu nýbúin(n) að senda inn torrent, þá hverfur þessi rauði kassi þegar þú byrjar að deila því.</a></b>';
	echo '</td></tr></table></p>'."\n";
}

} // stdhead

function stdfoot() {
	echo '</td></tr></table>'."\n";
	echo '<table class="bottom" width="100%" border="0" cellspacing="0" cellpadding="0"><tr valign="top">'."\n";
	echo '<td class="bottom" align="center" width="100%"><br /></td>'."\n";
	echo '</tr></table>'."\n";
	echo '</body></html>'."\n";
}

function genbark($x,$y) {
	stdhead($y);
	echo '<h2>'.htmlspecialchars($y).'</h2>'."\n";
	echo '<p>'.htmlspecialchars($x).'</p>'."\n";
	stdfoot();
	exit();
}

function mksecret($len = 20) {
    $ret = "";
    for ($i = 0; $i < $len; $i++)
        $ret .= chr(mt_rand(33, 126));
    return $ret;
}

function httperr($code = 404) {
	header("HTTP/1.0 404 Not found");
	print("<h1>Umbeðin Síða Fannst Ekki</h1>\n");
	print("<p>Afsakið óþægindin</p>\n");
	exit();
}

function gmtime() {
	return strtotime(get_date_time());
}

function logincookie($id, $passhash, $updatedb = 1, $expires = 0x7fffffff)
{
	setcookie('uid', $id, $expires, '/');
	setcookie('pass', $passhash, $expires, '/');

	if ($updatedb)
		mysql_query('UPDATE users SET last_login = NOW() WHERE id = '.$id);
}


function logoutcookie() {
	setcookie("id", "", 0x7fffffff, "/");
	setcookie("pass", "", 0x7fffffff, "/");
}

function loggedinorreturn() {
	global $CURUSER,$BASEURL;
	if (!$CURUSER) {
		header("Location: $BASEURL/login.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]));
	exit();
	}
}

function deletetorrent($id) {
	global $torrent_dir;
	mysql_query("DELETE FROM torrents WHERE id = $id");
	mysql_query("DELETE FROM snatched WHERE torrentid = $id");
	foreach(explode(".","peers.files.comments.ratings") as $x)
		mysql_query("DELETE FROM $x WHERE torrent = $id");
	unlink("$torrent_dir/$id.torrent");
}

function pager($rpp, $count, $href, $opts = array()) {
	$pages = ceil($count / $rpp);

	if (!isset($opts['lastpagedefault']))
		$pagedefault = '0';
	else {
		$pagedefault = floor(($count - 1) / $rpp);
		if ($pagedefault < '0')
			$pagedefault = '0';
	}

	if (isset($_GET['page'])) {
		$page = 0 + $_GET['page'];
		if ($page < 0)
			$page = $pagedefault;
	} else
		$page = $pagedefault;

	$pager = '';

	$mp = $pages - 1;
	$as = '<b>&lt;&lt;&nbsp;Fyrri</b>';
	if ($page >= 1) {
		$pager .= '<a href="'.$href.'page='.($page - 1).'&';
		if(isset($_GET['sort']) && isset($_GET['d']))
			$pager .= 'sort='.$_GET['sort'].'&d='.$_GET['d'];
	$pager .= '">';
        $pager .= $as;
        $pager .= '</a>';
	} else
		$pager .= $as;
	$pager .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	$as = '<b>Næsta&nbsp;&gt;&gt;</b>';
	if ($page < $mp && $mp >= '0') {
		$pager .= '<a href="'.$href.'page='.($page + 1);
		if(isset($_GET['sort']) && isset($_GET['d']))
			$pager .= '&amp;sort='.$_GET['sort'].'&d='.$_GET['d'];
		if(isset($_GET['requestorid']))
			$pager .= '&amp;requestorid='.$_GET['requestorid'];
		$pager .= '">';
		$pager .= $as;
		$pager .= '</a>';
	} else
		$pager .= $as;

	if (!empty($count)) {
		$pagerarr = array();
		$dotted = '0';
		$dotspace = '3';
		$dotend = $pages - $dotspace;
		$curdotend = $page - $dotspace;
		$curdotstart = $page + $dotspace;
		for ($i = '0'; $i < $pages; $i++) {
			if (($i >= $dotspace && $i <= $curdotend) || ($i >= $curdotstart && $i < $dotend)) {
				if (!$dotted)
					$pagerarr[] = "...";
				$dotted = '1';
				continue;
			}
			$dotted = '0';
			$start = $i * $rpp + 1;
			$end = $start + $rpp - 1;
			if ($end > $count)
				$end = $count;
			$text = $start.'&nbsp;-&nbsp;'.$end;
			if(isset($_GET['sort']) && isset($_GET['d']))
				$pager_extra = 'sort='.$_GET['sort'].'&d='.$_GET['d'];
			else
				$pager_extra = '';
			if(isset($_GET['requestorid']))
				$pager_extra = 'requestorid='.$_GET['requestorid'];
			if ($i != $page)
				$pagerarr[] = '<a href="'.$href.'page='.$i.'&'.$pager_extra.'"><b>'.$text.'</b></a>';
			else
				$pagerarr[] = '<b>'.$text.'</b>';
		}
		$pagerstr = join(" | ", $pagerarr);
		$pagertop = '<p align="center">'.$pager.'<br />'.$pagerstr.'</p>'."\n";
		$pagerbottom = '<p align="center">'.$pagerstr.'<br />'.$pager.'</p>'."\n";
	} else {
	        $pagertop = '<p align="center">'.$pager.'</p>'."\n";
	        $pagerbottom = $pagertop;
	}

	$start = $page * $rpp;

	return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");
}

function downloaderdata($res) {
	$rows = array();
	$ids = array();
	$peerdata = array();
	while ($row = mysql_fetch_assoc($res)) {
		$rows[] = $row;
		$id = $row['id'];
		$ids[] = $id;
		$peerdata[$id] = array(downloaders => 0, seeders => 0, comments => 0);
	}
	if (count($ids)) {
		$allids = implode(',', $ids);
		$res = mysql_query('SELECT COUNT(*) AS c, torrent, seeder FROM peers WHERE torrent IN ('.$allids.') GROUP BY torrent, seeder');
		while ($row = mysql_fetch_assoc($res)) {
			if ($row['seeder'] == 'yes')
				$key = 'seeders';
			else
				$key = 'downloaders';
			$peerdata[$row['torrent']][$key] = $row['c'];
		}
		$res = mysql_query('SELECT COUNT(*) AS c, torrent FROM comments WHERE torrent IN ('.$allids.') GROUP BY torrent');
		while ($row = mysql_fetch_assoc($res)) {
			$peerdata[$row["torrent"]]["comments"] = $row["c"];
		}
	}

	return array($rows, $peerdata);
}

function signiture($id) {
	global $sign_dir;
	if(file_exists($sign_dir.'/'.$id) && !empty($id) && verifystring($id,'num') === TRUE)
                return '<br /><br />-----Undirskrift-----<br/>'.format_comment(substr(file_get_contents($sign_dir.'/'.$id), 0, 200));
}

function commenttable($rows) {
	global $CURUSER, $HTTP_SERVER_VARS, $sign_dir;
	begin_main_frame();
	begin_frame();
	$count = '0';
	foreach ($rows as $row) {
		echo '<p class="sub">#'.$row['id'].' eftir ';
		if (isset($row['username'])) {
			$title = $row['title'];
			if (empty($title))
				$title = get_user_class_name($row['class']);
			else
				$title = htmlspecialchars($title);
		echo '<a name="comm"'.$row['id'].' href="userdetails.php?id='.$row['user'].'"><b>'.htmlspecialchars($row['username']).'</b></a>'. ($row['donor'] == 'yes' ? '<img src="/pic/star.gif" alt="Donor">' : '').($row['warned'] == 'yes' ? '<img src="'.'/pic/warned.gif" alt="Viðvörun" />' : '').' ('.$title.')'."\n";
		} else
			echo '<a name="comm'.$row['id'].'"><i>(munaðarlaus)</i></a>'."\n";
		echo ' þann '.$row['added'].' GMT'.(get_user_class() >= UC_MODERATOR ? '- [<a href="deletecomment.php?id='.$row['id'].'">Eyða</a>]' : '') . '</p>'."\n";
		$avatar = ($CURUSER['avatars'] == 'yes' ? htmlspecialchars($row['avatar']) : '');
		if (empty($avatar))
			$avatar = '/pic/default_avatar.gif';
		begin_table(true);
		echo '<tr valign="top">'."\n";
		echo '<td align="center" width="150px" style="padding:0px"><img width="150px" src="'.$avatar.'"></td>'."\n";
		echo '<td class="text">'.format_comment($row['text']);
		if($CURUSER['undirskrift'] === '1')
			echo signiture($row['user']);
		echo '</td>'."\n".'</tr>'."\n";
		end_table();
	}
	end_frame();
	end_main_frame();
}

function searchfield($s) {
	return preg_replace(array('/[^a-z0-9]/si', '/^\s*/s', '/\s*$/s', '/\s+/s'), array(" ", "", "", " "), $s);
}

function genrelist() {
	$ret = array();
	$res = mysql_query('SELECT id, name FROM categories ORDER BY name');
	while ($row = mysql_fetch_array($res))
		$ret[] = $row;
	return $ret;
}

function linkcolor($num) {
	if (empty($num))
		return 'red';
	return 'green';
}

function ratingpic($num) {
	global $pic_base_url;
	$r = round($num * 2) / 2;
	if ($r < 1 || $r > 5)
		return;
	return '<img src="'.$pic_base_url.$r.'.gif" border="0" alt="rating:'.$num.' / 5" />';
}

function torrenttable($res, $variant = "index") {
	global $pic_base_url, $CURUSER, $BASEURL;

	if ($CURUSER['class'] <= UC_POWER_USER && $CURUSER['donor'] === 'no') {
		$gigs = $CURUSER['uploaded'] / (1024*1024*1024);
		$ratio = (($CURUSER['downloaded'] > '2147483648') ? ($CURUSER['uploaded'] / $CURUSER['downloaded']) : '1');
		$space = date('YmdHis');
		if($space < '20060804180000' || $space > '20060807235959') {
			if ($ratio < '0.75' && $ratio >= '0.5')
				$wait = '12';
			elseif ($ratio < '0.5')
				$wait = '24';
			else
				$wait = '0';
		} else
			$wait = '0';
		$t1 = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 1209600)));
		$t2 = str_replace(array(' ',':','-'),'',$CURUSER['added']);
		if($CURUSER['donor'] === 'yes' || $t2 >= $t1)
			$wait = '0';
		if($CURUSER['warned'] == 'yes')
			$wait = '24';
	}
	if(isset($_GET['sort'])) {
		$order = $_GET['d'];
		if($order == 'ASC')
			$sort = '&d=DESC';
		elseif($order == 'DESC')
			$sort = '&d=ASC';
	} else
		$sort = '&d=DESC';

	if($_GET['search'])
		$search = '&search='.$_GET['search'];
	else
		$search = '';

	$verifystring = verifystring($_GET['cat'],'num');
	if($verifystring === TRUE)
	        $catsort = '&amp;cat='.$_GET['cat'];

	$s_catz = $_REQUEST['s_catz'];

	$verifystring = verifystring($_GET['incldead'],'num');
	if($verifystring === TRUE)
	        $incldead = '&amp;incldead='.$_GET['incldead'];

	$extrapar = $catsort.$search.$sort.$s_catz.$incldead;

	if($_POST['new_renew'] === '1') {
		$lasttorrent = mysql_result(mysql_query('SELECT id FROM torrents ORDER BY id DESC LIMIT 1'),0);
		mysql_query('UPDATE users SET lasttorrent='.$lasttorrent.' WHERE id='.$CURUSER['id']);
		$_SESSION['lasttorrent'] = $lasttorrent;
		$header = 'Refresh: url='.$BASEURL.$_SERVER['REQUEST_URI'];
		header($header);
	}
	if($CURUSER['birta_nytt'] === '1') {
		echo '
		<form action="'.$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'].'" method="post">
		<input type="hidden" name="new_renew" value="1">
		<input type="submit" value="Endursetja \'ný torrent\' merkinguna">
		</form><br />';
	}
?>

<table border="1" cellspacing="0" cellpadding="5">
<tr>
<td class="colhead" align="center"><a style="text-decoration:none;color:white;" href="browse.php?sort=type<?=$extrapar ?>">#</a></td>

<td class="colhead" align="left"><a style="text-decoration:none;color:white;" href="browse.php?sort=name<?=$extrapar; ?>">Nafn</a></td>

<?

if ($wait > '0')
	echo '<td class="colhead" align="center">Bið</td>'."\n";
if ($variant == 'mytorrents') {
	echo '<td class="colhead" align="center">Breyta</td>'."\n";
	echo '<td class="colhead" align="center">Sýnilegt</td>'."\n";
}
?>

<td class="colhead" align="right"><a style="text-decoration:none;color:white;" href="browse.php?sort=numfiles<?=$extrapar; ?>">Skrár</a></td>
<td class="colhead" align="right"><a style="text-decoration:none;color:white;" href="browse.php?sort=comments<?=$extrapar ?>">Umsagnir</a></td>
<td class="colhead" align="center"><a style="text-decoration:none;color:white;" href="browse.php?sort=ratingsum<?=$extrapar ?>">Einkunn</a></td>
<td class="colhead" align="center"><a style="text-decoration:none;color:white;" href="browse.php?sort=added<?=$extrapar ?>">Bætt inn</a></td>
<td class="colhead" align="center"><a style="text-decoration:none;color:white;" href="browse.php?sort=added<?=$extrapar ?>">TTL</a></td>
<td class="colhead" align="center">Samtals hraði</td>
<td class="colhead" align="center"><a style="text-decoration:none;color:white;" href="browse.php?sort=size<?=$extrapar ?>">Stærð</a></td>
<td class="colhead" align="center"><a style="text-decoration: none; color: white;" href="browse.php?sort=times_completed<?=$extrapar ?>">Sótt</a></td>
<td class="colhead" align="right"><a style="text-decoration:none;color:white;" href="browse.php?sort=seeders<?=$extrapar; ?>">Að deila</a></td>
<td class="colhead" align="right"><a style="text-decoration:none;color:white;" href="browse.php?sort=leechers<?=$extrapar ?>">Að sækja</a></td>
<?

if ($variant == "index")
	echo '<td class="colhead" align="center">Sent inn af</td>'."\n";

echo '</tr>'."\n";

while ($row = mysql_fetch_assoc($res)) {
	$id = $row['id'];
	if($row['reviewed'] > '0' && $CURUSER['class'] >= UC_MODERATOR)
		echo '<tr style="background-color:DarkGray">'."\n";
	else
		echo '<tr>'."\n";

	echo '<td align="center" style="padding:0px">';
	if (isset($row['cat_name'])) {
		echo '<a href="browse.php?c'.$row['category'].'=1">';
		if (!empty($row['cat_pic']))
			echo '<img border="0" src="'.$pic_base_url.$row['cat_pic'].'" alt="'. $row['cat_name'].'" />';
		else
			echo $row['cat_name'];
		echo '</a>';
	} else
		echo '-';
	echo '</td>'."\n";

	$dispname = htmlspecialchars($row['name']);
	echo '<td align="left"><a href="details.php?';
	if ($variant == 'mytorrents')
		echo 'returnto='. urlencode($_SERVER['REQUEST_URI']).'&amp;';
	echo 'id='.$id;
	if ($variant == 'index')
		echo '&amp;hit=1';
	if(strlen($dispname) > '65')
		$dispname = substr($dispname, '0','60') . '...';
	echo '"><b>'.$dispname.'</b></a>'."\n";
	if($id > $_SESSION['lasttorrent'] && $CURUSER['birta_nytt'] === '1')
		echo '<img src="/pic/new.png" /> ';
	if($row['nuked'] == 'yes')
		echo '<b>[SPRENGT:</b>'.$row['nukedr'].'<b>]</b> <img src="ismod/nuked.gif" border="0" valign="bottom" alt="'.$row['nukedr'].'">'."\n";
	if($row['scene'] == 'y')
		echo '<b>[Scene útgáfa]:</b>'."\n";
	if ($variant == "index" && $wait == 0)
		echo '<a href="download.php/'.$id.'/'.rawurlencode($row['filename']).'"><img src="ismod/nidur.gif" border="0" alt="Sækja"><img src="ismod/nidur.gif" border="0" alt="Sækja"></a>'."\n";
	if ($wait > '0') {
		$elapsed = floor((gmtime() - strtotime($row["added"])) / 3600);
		if ($elapsed < $wait) {
			$color = dechex(floor(127*($wait - $elapsed)/48 + 128)*65536);
			echo '<td align="center"><nobr><a href="/faq.php#dl8"><font color="'.$color.'">'.number_format($wait - $elapsed).' klst</font></a></nobr></td>'."\n";
		} else {
			if ($variant == 'index')
				echo '<a href="download.php/'.$id.'/'.rawurlencode($row['filename']). '"><img src="ismod/nidur.gif" border="0" alt="Sækja"><img src="ismod/nidur.gif" border="0" alt="Sækja" /></a>'."\n";
			echo '<td align="center"><nobr>Engin</nobr></td>'."\n";
		}
        }

	if ($variant == 'mytorrents')
		echo '<td align="center"><a href="edit.php?returnto='.urlencode($_SERVER['REQUEST_URI']).'&amp;id='.$row['id'].'">Breyta</a>'."\n";
	echo '</td>'."\n";
	if ($variant == 'mytorrents') {
		echo '<td align="right">';
		if ($row['visible'] == 'no')
			echo '<b>Nei</b>';
		else
			echo 'Já';
		echo '</td>'."\n";
	}

	if ($row['type'] == 'single')
		echo '<td align="right">'.$row['numfiles'].'</td>'."\n";
	else {
		if ($variant == 'index')
			echo '<td align="right"><b><a href="details.php?id='.$id.'&amp;hit=1&amp;filelist=1">'.$row['numfiles'].'</a></b></td>'."\n";
		else
			echo '<td align="right"><b><a href="details.php?id='.$id.'&amp;filelist=1#filelist">'.$row['numfiles'].'</a></b></td>'."\n";
	}

	if ($row['comments'] === '0')
		echo '<td align="right">'.$row['comments'].'</td>'."\n";
	else {
		if ($variant == 'index')
			echo '<td align="right"><b><a href="details.php?id='.$id.'&amp;hit=1&amp;tocomm=1">'.$row['comments'].'</a></b></td>'."\n";
		else
			echo '<td align="right"><b><a href="details.php?id='.$id.'&amp;page=0#startcomments">'.$row['comments'].'</a></b></td>'."\n";
	}

	echo '<td align="center">';
	if (!isset($row['rating']))
		echo '---';
	else {
		$rating = round($row['rating'] * 2) / 2;
		$rating = ratingpic($row['rating']);
		if (!isset($rating))
			echo '---';
		else
			echo $rating;
	}
        echo '</td>'."\n";
        echo '<td align="center"><nobr>'.str_replace(' ', '<br />', $row['added']).'</nobr></td>'."\n";
	$ttl = (28*24) - floor((gmtime() - sql_timestamp_to_unix_timestamp($row["added"])) / 3600);
	if ($ttl == '1')
		$ttl .= '<br />klst';
	else
		$ttl .= '<br />klst';
	echo '<td align="center">'.$ttl.'</td>'."\n";
	// Totalspeed mod
	$resSpeed = mysql_query('SELECT seeders,leechers FROM torrents WHERE id='.$id.' AND visible=\'yes\' ORDER BY added DESC LIMIT 15') or sqlerr(__FILE__, __LINE__);
	if ($rowTmp = mysql_fetch_row($resSpeed))
		list($seedersTmp,$leechersTmp) = $rowTmp; 
	if ($seedersTmp >= '1' && $leechersTmp >= '1'){
		$speedQ = mysql_query('SELECT (t.size * t.times_completed + SUM(p.downloaded)) / (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(added)) AS totalspeed FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = \'no\' AND p.torrent = \''.$id.'\' GROUP BY t.id ORDER BY added ASC LIMIT 15') or sqlerr(__FILE__, __LINE__);
		$a = mysql_fetch_assoc($speedQ);
		$totalspeed = mksize($a['totalspeed']) . '/s';
	}
	echo '<td align="center">'.$totalspeed.'</td>'."\n";
	echo '<td align="center">'.str_replace(' ', '<br />', mksize($row['size'])).'</td>'."\n";
	$_s = '';
	if ($row['times_completed'] != '1')
		$_s = 's';
	echo '<td align="center"><a href="viewsnatches.php?id='.$row[id].'">'.number_format($row['times_completed']).'<br />time'.$_s.'</a></td>'."\n";

	if ($row["seeders"]) {
		if ($variant == "index") {
			if ($row["leechers"]) $ratio = $row["seeders"] / $row["leechers"]; else $ratio = 1;
				print("<td align=right><b><a href=details.php?id=$id&amp;hit=1&amp;toseeders=1><font color=" .
			get_slr_color($ratio) . ">" . $row["seeders"] . "</font></a></b></td>\n");
		} else
			echo '<td align="right"><b><a class="'.linkcolor($row['seeders']).'" href="details.php?id='.$id.'&amp;dllist=1#seeders\">'.$row['seeders'].'</a></b></td>'."\n";
	} else
		echo '<td align="right"><span class="'.linkcolor($row['seeders']).'">'.$row['seeders'].'</span></td>'."\n";

	if ($row["leechers"]) {
		if ($variant == "index")
			echo '<td align="right"><b><a href="details.php?id='.$id.'&amp;hit=1&amp;todlers=1">'.number_format($row['leechers']).($peerlink ? '</a>' : '').'</b></td>'."\n";
		else
			echo '<td align="right"><b><a class="'.linkcolor($row['leechers']).'" href="details.php?id='.$id.'&amp;dllist=1#leechers">'.$row['leechers'].'</a></b></td>'."\n";
	} else
		echo '<td align="right">0</td>'."\n";

	if ($variant == 'index') {
		echo '<td align="center">';
		if($row['anonymous'] === '1')
			echo '<i>(Nafnleynd)</i>';
		else
			echo '<a href=userdetails.php?id='.$row['owner'].'><b>'.htmlspecialchars($row['username']).'</b></a>'; 
		echo '</td>'."\n";
	}
        echo '</tr>'."\n";
}
	echo '</table>'."\n";
	return $rows;
}

function hit_start() {
	return;
	global $RUNTIME_START, $RUNTIME_TIMES;
	$RUNTIME_TIMES = posix_times();
	$RUNTIME_START = gettimeofday();
}

function hit_count() {
	return;
	global $RUNTIME_CLAUSE;
	if (preg_match(',([^/]+)$,', $_SERVER["SCRIPT_NAME"], $matches))
		$path = $matches[1];
	else
		$path= "(unknown)";
	$period = date("Y-m-d H") . ":00:00";
	$RUNTIME_CLAUSE = "page = " . sqlesc($path) . " AND period = '$period'";
	$update = "UPDATE hits SET count = count + 1 WHERE $RUNTIME_CLAUSE";
	mysql_query($update);
	if (mysql_affected_rows())
		return;
	$ret = mysql_query("INSERT INTO hits (page, period, count) VALUES (" . sqlesc($path) . ", '$period', 1)");
	if (!$ret)
		mysql_query($update);
}

function hit_end() {
    return;
    global $RUNTIME_START, $RUNTIME_CLAUSE, $RUNTIME_TIMES;
    if (empty($RUNTIME_CLAUSE))
        return;
    $now = gettimeofday();
    $runtime = ($now["sec"] - $RUNTIME_START["sec"]) + ($now["usec"] - $RUNTIME_START["usec"]) / 1000000;
    $ts = posix_times();
    $sys = ($ts["stime"] - $RUNTIME_TIMES["stime"]) / 100;
    $user = ($ts["utime"] - $RUNTIME_TIMES["utime"]) / 100;
    mysql_query("UPDATE hits SET runs = runs + 1, runtime = runtime + $runtime, user_cpu = user_cpu + $user, sys_cpu = sys_cpu + $sys WHERE $RUNTIME_CLAUSE");
}

function hash_pad($hash) {
	return str_pad($hash, 20);
}

function hash_where($name, $hash) {
	$shhash = preg_replace('/ *$/s', "", $hash);
	return '('.$name.' = '.sqlesc($hash).' OR '.$name.' = '.sqlesc($shhash).')';
}

function get_user_icons($arr, $big = false) {
	if ($big) {
		$donorpic = 'starbig.gif';
		$warnedpic = 'warnedbig.gif';
		$disabledpic = 'disabledbig.gif';
		$parkedpic = 'parked.gif';
		$style = 'style="margin-left: 4pt"';
	} else {
		$donorpic = 'star.gif';
		$warnedpic = 'warned.gif';
		$disabledpic = 'disabled.gif';
		$parkedpic = 'parked.gif';
		$style = 'style="margin-left:2pt"';
	}

}
require "global.php";

?>
