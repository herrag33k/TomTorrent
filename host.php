<?
if($_SERVER['SERVER_NAME'] != 'torrent.is' || $_SERVER['SERVER_NAME'] != www2.torrent.is) {
$url = $_SERVER['REQUEST_URI'];
header("location: http://torrent.is$url");
}
?>
