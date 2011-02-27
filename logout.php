<?

require_once("include/bittorrent.php");

dbconn();

$lasttorrent = mysql_result(mysql_query('SELECT id FROM torrents ORDER BY id DESC LIMIT 1'),0);
mysql_query('UPDATE users SET lasttorrent='.$lasttorrent.' WHERE id='.$CURUSER['id']);
//session_destroy();

if (isset($_COOKIE[session_name()])) {
   setcookie(session_name(), '', time()-42000, '/');
}

logoutcookie();

//header("Refresh: 0; url=./");
Header("Location: $BASEURL/");

?>
