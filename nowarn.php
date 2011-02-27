<?
require_once("include/bittorrent.php");
function bark($msg) {
 stdhead();
   stdmsg("Su intento ha fallado...", $msg);
 stdfoot();
 exit;
}
dbconn();
loggedinorreturn();
if(isset($_POST["nowarned"])&&($_POST["nowarned"]=="nowarned")){
if (get_user_class() >= UC_MODERATOR) {

if (empty($_POST["usernw"]) && empty($_POST["desact"]) && empty($_POST["delete"]))
    bark("Seleccione por lo menos un usuario.");

if (!empty($_POST["usernw"]))
{
 $msg = sqlesc("Se le ha removido su advertencia por parte de: " . $CURUSER['username'] . ".");
 $added = sqlesc(get_date_time());
 $userid = implode(", ", $_POST['usernw']);
 mysql_query("INSERT INTO messages (id, sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);

 $r = mysql_query("SELECT modcomment FROM users WHERE id IN (" . implode(", ", $_POST['usernw']) . ")")or sqlerr(__FILE__, __LINE__);
 $user = mysql_fetch_array($r);
 $exmodcomment = $user["modcomment"];
 $modcomment = gmdate("Y-m-d") . " - Advertencia quitada por " . $CURUSER['username'] . ".\n". $modcomment . $exmodcomment;
 mysql_query("UPDATE users SET modcomment=" . sqlesc($modcomment) . " WHERE id IN (" . implode(", ", $_POST['usernw']) . ")") or sqlerr(__FILE__, __LINE__);

 $do="UPDATE users SET warned='no', warneduntil='0000-00-00 00:00:00' WHERE id IN (" . implode(", ", $_POST['usernw']) . ")";
 $res=mysql_query($do);}

 if (!empty($_POST["desact"])){
 $do="UPDATE users SET enabled='no' WHERE id IN (" . implode(", ", $_POST['desact']) . ")";
 $res=mysql_query($do);}
}
}
header("Refresh: 0; url=warned.php");
?>
