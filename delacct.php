<?
require "include/bittorrent.php";
dbconn();
if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  if (!$username || !$password)
    stderr("Villa", "Gjörðu svo vel að fylla út formið rétt.");
  $res = mysql_query(

  "SELECT * FROM users WHERE username=" . sqlesc($username) .
  " && passhash=md5(concat(secret,concat(" . sqlesc($password) . ",secret)))") or sqlerr();
  if (mysql_num_rows($res) != 1)
    stderr("Villa", "Rangt notandanafn eða lykilorð. Gjörðu svo vel að staðfesta að upplýsingarnar sem þú slóst inn séu réttar.");
  $arr = mysql_fetch_assoc($res);

  $id = $arr['id'];
  $res = mysql_query("UPDATE users SET deleted=1 WHERE id=$id") or sqlerr();
  if (mysql_affected_rows() != 1)
    stderr("Villa", "Get ekki eytt út aðgangi.");
  stderr("Árangur!", "Aðgangnum <b>$username</b> var eytt út.");
}
stdhead("Eyða aðgangi");
?>
<h1>Eyða aðgangi</h1>
<table border=1 cellspacing=0 cellpadding=5>
<form method=post action=delacct.php>
<tr><td class=rowhead>Notandi</td><td><input size=40 name=username></td></tr>
<tr><td class=rowhead>Lykilorð</td><td><input type=password size=40 name=password></td></tr>
<tr><td colspan=2><input type=submit class=btn value='Eyða'></td></tr>
</form>
</table>
<span style="font-weight:bold;font-size:16">Athugið! Með því að nota þetta form er aðgangnum eytt út 
varanlega!<br />
Notandanafnið og netfangið bundið við aðganginn er ónothæft í framtíðarskráningum.</span>
<?
stdfoot();
?>
