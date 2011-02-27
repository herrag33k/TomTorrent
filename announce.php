<?
if($_SERVER['SERVER_NAME'] === 'test.torrent.is')
        error_reporting(E_ALL);
ob_start();

include('ipcheck.php');

//if($_SERVER['SCRIPT_NAME'] == '/announce.php')
//	die("Álag þessa stundina. Kemur upp einhvern tímann aftur.");

$announce_interval = 3600;
$MEMBERSONLY='true';
$BASEURL = 'http://torrent.is';
$slot_dir = '/www/torrent.is/www/cache-slots/';
$unseeded_dir = '/www/torrent.is/www/cache-unseeded/';

define ('UC_BEGINNER', 0);
define ('UC_USER', 1);
define ('UC_GOOD_USER', 2);
define ('UC_POWER_USER', 3);
define ('UC_MODERATOR', 4);
define ('UC_ADMINISTRATOR', 5);
define ('UC_SYSOP', 6);


require_once("include/secrets.php");

// Start benc.php

require_once('include/benc.php');

// End benc.php

// Start global.php

function get_date_time($timestamp = 0)
{
  if ($timestamp)
    return date("Y-m-d H:i:s", $timestamp);
  else
    return gmdate("Y-m-d H:i:s");
}

function sqlerr($file = '', $line = '') { 
	global $ip;
    $fp=fopen("/www/torrent.is/www/sqlerror.text", "a+");
    $date=date("l dS \of F Y h:i:s A");
    $error="<p>$date - " . mysql_error() ." í skránni $file, lína $line. Notandi: Announce.php. IP: $ip</p><br>\r\n";
    fwrite($fp,"$error");
    die();
}

// End global.php

function verifystring($string) {
	$chars = '0123456789';
	for($i=0;$i<strlen($string); $i++) {
		if(strpos($chars,$string[$i]) === false)
			return $string.' is not a number';
	}
	return TRUE;
}

function err($msg) {
	benc_resp(array("failure reason" => array('type' => "string", 'value' => $msg)));
	die();
}

function benc_resp($d) {
	benc_resp_raw(benc(array('type' => "dictionary", 'value' => $d)));
}

function benc_resp_raw($x)
{
	header("Content-Type: text/plain");
	header("Pragma: no-cache");
	print($x);
}

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

function sqlesc($x) {
	return "'".mysql_real_escape_string($x)."'";
}

function validip($ip) {
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
        } else
		return false;
}

function dbconn($autoclean = false) {
    global $mysql_host, $mysql_user, $mysql_pass, $mysql_db;

    if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
    {
          switch (mysql_errno())
          {
                case 1040:
                case 2002:
			die("Of margir notendur tengdir vid gagnagrunn. Reyndu aftur sidar.");
        default:
            die("[" . mysql_errno() . "] dbconn: mysql_connect: " . mysql_error());
      }
    }
    mysql_select_db($mysql_db) or die('dbconn: mysql_select_db: ' + mysql_error());

    if ($autoclean)
        register_shutdown_function("autoclean");
}

function hash_pad($hash) {
	return str_pad($hash, 20);
}

function hash_where($name, $hash) {
	$shhash = preg_replace('/ *$/s', "", $hash);
	return "($name = " . sqlesc($hash) . " OR $name = " . sqlesc($shhash) . ")";
}

function gmtime() {
	return strtotime(get_date_time());
}

function find_unseeded ($userid) {
	global $unseeded_dir;
	if(!file_exists($unseeded_dir.$userid))
		return '1';
	else
		return '0';
}

function slots($userid) {
	global $slot_dir;
	if(!file_exists($slot_dir.$userid))
		return '1';
	else
		return '0';
}

function update_topic_last_post($topicid) {
        $res = mysql_query("SELECT id FROM posts WHERE topicid=$topicid ORDER BY id DESC LIMIT 1") or sqlerr(__FILE__, __LINE__);

        $arr = mysql_fetch_row($res) or die("Enginn korkur fannst");

        $postid = $arr[0];

        mysql_query("UPDATE topics SET lastpost=$postid WHERE id=$topicid") or sqlerr(__FILE__, __LINE__);
}

function forumlog ($userid,$now,$before,$type) {
	$username = mysql_result(mysql_query('SELECT username FROM users WHERE id='.$userid),0);
	$type = 'Svindl grunur';
	$subject = 'Notandi: '.$username."\n".'Tilkynnti 2GB eða meira í deilingarmagn - grunur um svindl'."\n\n".'Deilingarmagn: '.$now."\n".'Niðurhalsmagn: '.$before;

	$sql = 'INSERT INTO topics (userid, forumid, subject) VALUES('.$userid.', 12, \''.$username.' - '.$type.'\')';
        mysql_query($sql) or sqlerr(__FILE__, __LINE__);

        $topicid = mysql_insert_id() or sqlerr(__FILE__, __LINE__);
        $added = "'".get_date_time()."'";
        $sql = 'INSERT INTO posts (topicid, userid, added, body) VALUES ('.$topicid.','.$userid.','.$added.','.sqlesc($subject).')';
        mysql_query($sql) or sqlerr (__FILE__, __LINE__);
        $postid = mysql_insert_id();
        update_topic_last_post($topicid);
        if(verifystring(mysql_insert_id()))
                return TRUE;
        else
                return FALSE;
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

foreach (array("passkey","info_hash","peer_id","ip","event") as $x) {
	if(isset($_GET["$x"]))
		$GLOBALS[$x] = $_GET[$x];
}
foreach (array("port","downloaded","uploaded","left") as $x)
	$GLOBALS[$x] = 0 + $_GET[$x];

if (strpos($passkey, "?")) {
	$tmp = substr($passkey, strpos($passkey, "?"));
	$passkey = substr($passkey, 0, strpos($passkey, "?"));
	$tmpname = substr($tmp, 1, strpos($tmp, "=")-1);
	$tmpvalue = substr($tmp, strpos($tmp, "=")+1);
	$GLOBALS[$tmpname] = $tmpvalue;
}

foreach (array("passkey","info_hash","peer_id","port","downloaded","uploaded","left") as $x)
if (!isset($x))
	err('Vantar lykil: '.$x);

if (strlen($GLOBALS['info_hash']) != '20')
	$GLOBALS['info_hash'] = stripslashes($GLOBALS['info_hash']);

if (strlen($GLOBALS['peer_id']) != '20')
	$GLOBALS['peer_id'] = stripslashes($GLOBALS['peer_id']);


foreach (array("info_hash","peer_id") as $x) {
	if (strlen($GLOBALS[$x]) != '20')
		err('Ogilt '.$x.' ('.strlen($GLOBALS[$x]).' - '.urlencode($GLOBALS[$x]).')');
}

if (strlen($passkey) != '32')
	err('Ogildur audkennislykill ('.strlen($passkey).' - '.$passkey.')');

//if (empty($ip) || !preg_match('/^(d{1,3}.){3}d{1,3}$/s', $ip))
$ip = getip(); 
$rsize = '50';
foreach(array("num want", "numwant", "num_want") as $k) {
	if (isset($_GET[$k])) {
		$rsize = $_GET[$k];
		break;
	}
}

$agent = $_SERVER['HTTP_USER_AGENT'];

// Deny access made with a browser...
//if (ereg("^Mozilla\\/", $agent) || ereg("^Opera\\/", $agent) || ereg("^Links ", $agent) || ereg("^Lynx\\/", $agent))
//	err("Ekki opna þessa skrá í gegnum vafra - heldur stimpla hana inn í nýjar torrent skrár");

if (!$port || $port > '65536')
	err('Ogild ras');

if (!isset($event))
	$event = '';

$seeder = ($left == 0) ? 'yes' : 'no';

dbconn(false);
if(!empty($passkey)) {
	$file = '/www/torrent.is/passkeys/'.md5($passkey);
	if(!file_exists($file)) {
		$res = mysql_query('SELECT COUNT(*) FROM users WHERE passkey='.sqlesc($passkey).' AND deleted=0 AND enabled=\'yes\'') or sqlerr(__FILE__,__LINE__);
		$valid_passkey = mysql_result($res,0);
		if($valid_passkey >= '1')
			file_put_contents($file,'1');
		else
			 err('Ogildur audkennislykill! Nadu aftur í .torrent skránna fra '.$BASEURL);
	}
}

$sql = 'SELECT id, owner, banned, added, seeders + leechers AS numpeers, UNIX_TIMESTAMP(added) AS ts FROM torrents WHERE '.hash_where("info_hash", $info_hash).' LIMIT 1';
$res = mysql_query($sql) or sqlerr(__FILE__,__LINE__);
$torrent = mysql_fetch_assoc($res);
if (mysql_num_rows($res) == 0)
	err('Torrent skra ekki skrad a '.$BASEURL);

$torrentid = $torrent['id'];
if(!is_numeric($torrentid))
	err($torrentid);
$fields = 'seeder,peer_id,ip,port,uploaded,downloaded,userid';
$limit = '';
//if ($torrent['numpeers'] > $rsize)
	$limit = ' ORDER BY RAND() LIMIT '.$rsize;
$sql = 'SELECT '.$fields.' FROM peers WHERE torrent='.$torrentid.' AND connectable = \'yes\' '.$limit;
$res = mysql_query($sql) or sqlerr(__FILE__,__LINE__);

$resp = 'd'.benc_str('interval').'i'.$announce_interval.'e'.benc_str('peers').'l';
unset($self);
while ($row = mysql_fetch_assoc($res)) {
	$row['peer_id'] = hash_pad($row['peer_id']);

	if ($row['peer_id'] === $peer_id) {
		$userid = $row['userid'];
		$self = $row;
		continue;
	}

	$resp .= 'd'.
		benc_str('ip').benc_str($row['ip']).
		benc_str('peer id').benc_str($row['peer_id']).
		benc_str('port').'i'.$row['port'].'e'.
		'e';
}

$resp .= 'ee';

$selfwhere = 'torrent='.$torrentid.' AND '.hash_where('peer_id',$peer_id);

if (!isset($self)) {
	$res = mysql_query('SELECT '.$fields.' FROM peers WHERE '.$selfwhere.' LIMIT 1') or sqlerr(__FILE__,__LINE__);
	$row = mysql_fetch_assoc($res);
	if ($row) {
		$userid = $row['userid'];
		$self = $row;
	}
}

//// Up/down stats ////////////////////////////////////////////////////////////

if (!isset($self)) {
	$sql = 'SELECT COUNT(*) FROM peers WHERE passkey='.sqlesc($passkey).' AND torrent='.$torrentid;
	$res = mysql_query($sql) or sqlerr(__FILE__,__LINE__);
	$valid = mysql_result($res,0);

	if ($valid >= '1' && $seeder == 'no')
		err('Tengingartakmork brotin! Matt bara nidurhala fra einni stadsetningu i einu. Leysist oftast ef thu bidur adeins.');

	if ($valid >= '3' && $seeder == 'yes')
		err('Tengingartakmork brotin!');

	$rz = mysql_query('SELECT id,uploaded,downloaded,class,warned,donor,added FROM users WHERE passkey='.sqlesc($passkey).' AND deleted=0 AND enabled = \'yes\' LIMIT 1') or sqlerr(__FILE__,__LINE__);

	if (isset($MEMBERSONLY) && mysql_num_rows($rz) === '0')
		err('Óþekktur auðkennislykill. Gjörðu svo vel að ná aftur í torrent skránna frá '.$BASEURL); 

	$az = mysql_fetch_assoc($rz);
	$userid = $az['id'];

	if($az['id'] != $torrent['owner'] && $seeder === 'no' && find_unseeded($userid) < '1')
		err('Byrjadu fyrst a ad deila thvi sem thu hefur sent inn');

	if($az["id"] != $torrent["owner"]) {
		$gigs = $az["uploaded"] / (1024*1024*1024);
		$elapsed = floor((gmtime() - $torrent["ts"]) / 3600);
		$ratio = (($az["downloaded"] > 2147483648) ? ($az["uploaded"] / $az["downloaded"]) : 1);
	$space = date('YmdHis');
	//	if($space < '20060804180000' || $space > '20060807235959') {
			if ($ratio < 0.75 && $ratio >= 0.5)
				$wait = 12;
			else if ($ratio < 0.5)
				$wait = 24;
			else
				$wait = 0;
	//	} else {
	//		$wait = 0;
	//		$elapsed = 1;
	//	}
		$t1 = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 1209600)));
		$t2 = str_replace(array(' ',':','-'),'',$az['added']);
		if($az['donor'] === 'yes' || $t2 >= $t1)
			$wait = 0;
		if($az['warned'] == 'yes')
			$wait = '24';
		if ($elapsed < $wait && $az['id'] != $torrent['owner'])
			err('Adgangur bannadur ad thessu torrenti vegna hlutfalls eða vidvorunar i ('.($wait-$elapsed).'klst) - LESTU SOS!');
	}
	// Hólfaviðbót
	$t_added = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400*2)));
	$t_added2 = str_replace(array(' ',':','-'),'',$torrent['added']);
	if($t_added2 <= $t_added && slots($userid) < '1')
		err("Ekki naeg holf til ad hefja nidurhal eda deilingu");

} else {
	$upthis = max(0, $uploaded - $self['uploaded']);
	$downthis = max(0, $downloaded - $self['downloaded']);

	if ($upthis > '0' || $downthis > '0') {
		mysql_query('UPDATE users SET uploaded=uploaded+'.$upthis.',downloaded=downloaded+'.$downthis.' WHERE id='.$userid) or sqlerr(__FILE__,__LINE__);
		$date_upthis = date('YmdHis');
		mysql_query('INSERT DELAYED INTO uploads SET userid='.$userid.',date='.$date_upthis.',upload='.$upthis.',download='.$downthis) or sqlerr(__FILE__,__LINE__);
		if($upthis >= '2147483648')
			forumlog($userid,mksize($upthis),mksize($downthis),'cheat');
	}
}

////////////////////////////////////////////

if(substr_count($_SERVER['HTTP_USER_AGENT'],'Transmission') >= 1)
	err('Transmission hefur verid bannad vegna galla i forritinu');

$updateset = array();

if ($event == 'stopped') {
	if (isset($self))
	{
		mysql_query('DELETE FROM peers WHERE '.$selfwhere) or sqlerr(__FILE__,__LINE__);
		if (mysql_affected_rows()) {
			if ($self['seeder'] == 'yes')
				$updateset[] = 'seeders=seeders-1';
			else
				$updateset[] = 'leechers=leechers-1';
		}
	}
} else {
	if ($event == 'completed') {
		$updateset[] = 'times_completed=times_completed+1';
		$date_snatched = date('YmdHis');
		mysql_query('INSERT INTO snatched (torrentid,userid,date) VALUES ('.$torrentid.','.$userid.','.$date_snatched.')') or sqlerr(__FILE__,__LINE__);
	}
	if (isset($self)) {
		mysql_query('UPDATE peers SET uploaded='.$uploaded.',downloaded='.$downloaded.',to_go='.$left.',last_action=NOW(),seeder=\''.$seeder.'\''
		. ($seeder == 'yes' && $self['seeder'] != $seeder ? ', finishedat = '.time() : '').' WHERE '.$selfwhere) or sqlerr(__FILE__,__LINE__);
		if (mysql_affected_rows() && $self['seeder'] != $seeder) {
			if ($seeder == 'yes') {
				$updateset[] = 'seeders=seeders+1';
				$updateset[] = 'leechers=leechers-1';
			} else {
				$updateset[] = 'seeders=seeders-1';
				$updateset[] = 'leechers=leechers+1';
			}
		}
	} else {
//		if ($event != 'started')
//			err('Jafnoki fannst ekki. Endurræstu torrentið.');

		$sockres = @fsockopen($ip, $port, $errno, $errstr, 5);
		if (!$sockres)
			$connectable = 'no';
		else {
			$connectable = 'yes';
			@fclose($sockres);
		}
	}

	if(!isset($connectable))
		$connectable = 'yes';
	$ret = mysql_query('INSERT INTO peers (connectable, torrent, peer_id, ip, port, uploaded, downloaded, to_go, started, last_action, seeder, userid, agent, passkey) VALUES (\''.$connectable.'\','.$torrentid.','.sqlesc($peer_id).','.sqlesc($ip).','.$port.','.$uploaded.', '.$downloaded.', '.$left.', NOW(), NOW(), '.sqlesc($seeder).','.$userid.', '.sqlesc($agent).', '.sqlesc($passkey).')');
	if ($ret) {
		if ($seeder == 'yes')
			$updateset[] = 'seeders=seeders+1';
		else
			$updateset[] = 'leechers=leechers+1';
	}
}

if ($seeder == 'yes') {
	if ($torrent['banned'] != 'yes')
		$updateset[] = 'visible=\'yes\'';
	$updateset[] = 'last_action=NOW()';
}

if (count($updateset))
	mysql_query('UPDATE torrents SET '.join(',', $updateset).' WHERE id = '.$torrentid) or sqlerr(__FILE__,__LINE__);

benc_resp_raw($resp);

?>
