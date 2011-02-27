<?

require_once("include/secrets.php");
//require_once("include/bittorrent.php");
require_once("include/benc.php");

// Start bittorrent.php

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

function hash_where($name, $hash) {
        $shhash = preg_replace('/ *$/s', "", $hash);
        return "($name = " . sqlesc($hash) . " OR $name = " . sqlesc($shhash) . ")";
}

function hash_pad($hash) {
        return str_pad($hash, 20);
}

// End bittorrent.php

dbconn(false);

$r = "d" . benc_str("files") . "d";

$fields = "info_hash, times_completed, seeders, leechers";

if (isset($_GET["info_hash"]))
	$query = "SELECT $fields FROM torrents WHERE " . hash_where("info_hash", $_GET["info_hash"]);
//else
//	$query = "SELECT $fields FROM torrents ORDER BY info_hash";

$r = '';
if(isset($query)) {
	$res = mysql_query($query);

	while ($row = mysql_fetch_assoc($res)) {
		$r .= "20:" . hash_pad($row["info_hash"]) . "d" .
			benc_str("complete") . "i" . $row["seeders"] . "e" .
			benc_str("downloaded") . "i" . $row["times_completed"] . "e" .
			benc_str("incomplete") . "i" . $row["leechers"] . "e" .
			"e";
	}

	$r .= "ee";
}
header("Content-Type: text/plain");
if(!empty($r))
	echo $r;

?>
