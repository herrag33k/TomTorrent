<?
require "include/bittorrent.php";

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] != "POST")
  stderr("Error", "Metodo");

dbconn();

loggedinorreturn();                                                    

if (get_user_class() < UC_MODERATOR)
stderr("Error", "Permiso denegado");
$sender_id = ($_POST['sender'] == 'system' ? 0 : $CURUSER['id']);
$dt = sqlesc(get_date_time());
$msg = $_POST['msg'];
if (!$msg)
stderr("Error","Porfavor, ingrese algo!");

$updateset = $_POST['clases'];

$query = mysql_query("SELECT id FROM users WHERE class IN (".implode(",", $updateset).")");
while($dat=mysql_fetch_assoc($query))
{
mysql_query("INSERT INTO messages (sender, receiver, added, msg) VALUES ($sender_id, $dat[id], '" . get_date_time() . "', " . sqlesc($msg) .")") or sqlerr(__FILE__,__LINE__);
}

header("Refresh: 0; url=staffmess.php");
hit_end();

?>
