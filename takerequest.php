<?

require_once("include/bittorrent.php");

hit_start();

dbconn();

function bark($msg) {
 stdhead();
stdmsg("Skráning mistókst!", $msg);
 stdfoot();
 exit;
}

hit_count();

$userid = $_POST["userid"];
$requestartist = $_POST["requestartist"];
//$requesttitle = $_POST["requesttitle"];
$request = $requestartist;
$descr = $_POST["descr"];
$cat = $_POST["category"];

$userid = sqlesc($userid);
$request = sqlesc($request);
$descr = sqlesc($descr);
$cat = sqlesc($cat);

if(requests_free($CURUSER['id']) > 0) {
	mysql_query("INSERT INTO requests (hits,userid, cat, request, descr, added) VALUES(1,$CURUSER[id], $cat, $request, $descr, '" . get_date_time() . "')") or sqlerr(__FILE__,__LINE__);

$id = mysql_insert_id();
}

//@mysql_query("INSERT INTO addedrequests VALUES(0, $id, $CURUSER[id])") or sqlerr();


//write_log("$request was added to the Request section");

header("Refresh: 0; url=requests.php");

hit_end();

?>
