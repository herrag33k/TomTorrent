<?
require "include/bittorrent.php";
dbconn();

if (get_user_class() < UC_ADMINISTRATOR)
stderr("Error", "Permission denied.");

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
 $username = trim($_POST["username"]);
 
if (!$username)
   stderr("Error", "Please fill out the form correctly.");

 $res = mysql_query(

 "SELECT * FROM users WHERE username=" . sqlesc($username)   ) or sqlerr();
 if (mysql_num_rows($res) != 1)
   stderr("Error", "Bad user name or password. Please verify that all entered information is correct.");
 $arr = mysql_fetch_assoc($res);

 $id = $arr['id'];
 $res = mysql_query("UPDATE users SET deleted=1 WHERE id=$id") or sqlerr();
 if (mysql_affected_rows() != 1)
   stderr("Error", "Unable to delete the account.");
 stderr("Success", "The account <b>$username</b> was deleted.");
}
stdhead("Delete account");
?>
<h1>Delete account</h1>
<table border=1 cellspacing=0 cellpadding=5>
<form method=post action=delacctadmin.php>
<tr><td class=rowhead>User name</td><td><input size=40 name=username></td></tr>

<tr><td colspan=2><input type=submit class=btn value='Delete'></td></tr>
</form>
</table>
<?
stdfoot();
?>
