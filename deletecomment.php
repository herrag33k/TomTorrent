<?
require "include/bittorrent.php";
dbconn();
if (get_user_class() < UC_MODERATOR) die;
$id = 0 + $HTTP_GET_VARS["id"];
if (!$id) die;
@mysql_query("DELETE FROM comments WHERE id=$id") or sqlerr(__FILE__, __LINE__);

$referer = $HTTP_SERVER_VARS["HTTP_REFERER"];

if ($referer)
	header("Location: $referer");
else
	header("Location: $BASEURL/");


?>