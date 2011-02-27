<?
// Bit-Bucket, breytt af Dabbi
require "include/bittorrent.php";
dbconn();
$getid = $_GET['id'];
if(!empty($getid) && !is_numeric($getid))
	die('id is not a number');
$query = mysql_query("SELECT * from innmyndir where id = '$getid' limit 1");
//if(isset($CURUSER)) {
	if(mysql_num_rows($query) > 0) {
		$res = mysql_fetch_array($query);
		$nafn = $res['md5nafn'];
		$banned = $res['banned'];
		$mynd = $_SERVER['DOCUMENT_ROOT']."/innmyndir/$nafn";
		if($banned == 'yes')
		$mynd = $_SERVER['DOCUMENT_ROOT']."/ismod/mynd_banned.gif";
		if($CURUSER["hideadult"] == 'yes' && $res["adult"] == 'yes')
		$mynd = $_SERVER['DOCUMENT_ROOT']."/ismod/disable_18.jpg";
		
		$gerd = @exif_imagetype($mynd);
		if($gerd == IMAGETYPE_GIF) {
			header("Content-type: image/gif");
		}
		else if($gerd == IMAGETYPE_JPEG) {
			header("Content-type: image/jpeg");
		}
		else {
			header("Content-type: image/png");
		}
	} else {
		$mynd = $_SERVER['DOCUMENT_ROOT']."/ismod/ekki_til.gif";
		header("Content-type: image/gif");
	}
//} else {
//	$mynd = $_SERVER['DOCUMENT_ROOT']."/ismod/ekki_skradur.gif";
//	header("Content-type: image/gif");
//}
readfile($mynd);
?>
