<?
require "include/bittorrent.php";
dbconn();
if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
  $username = trim($_POST["username"]);
  $password = trim($_POST["password"]);
  if (!$username || !$password)
    stderr("Villa", "Gj�r�u svo vel a� fylla �t formi� r�tt.");
  $res = mysql_query(

  "SELECT * FROM users WHERE username=" . sqlesc($username) .
  " && passhash=md5(concat(secret,concat(" . sqlesc($password) . ",secret)))") or sqlerr();
  if (mysql_num_rows($res) != 1)
    stderr("Villa", "Rangt notandanafn e�a lykilor�. Gj�r�u svo vel a� sta�festa a� uppl�singarnar sem �� sl�st inn s�u r�ttar.");
  $arr = mysql_fetch_assoc($res);

  $id = $arr['id'];
  $res = mysql_query("UPDATE users SET deleted=1 WHERE id=$id") or sqlerr();
  if (mysql_affected_rows() != 1)
    stderr("Villa", "Get ekki eytt �t a�gangi.");
  stderr("�rangur!", "A�gangnum <b>$username</b> var eytt �t.");
}
stdhead("Ey�a a�gangi");
?>
<h1>Ey�a a�gangi</h1>
<table border=1 cellspacing=0 cellpadding=5>
<form method=post action=delacct.php>
<tr><td class=rowhead>Notandi</td><td><input size=40 name=username></td></tr>
<tr><td class=rowhead>Lykilor�</td><td><input type=password size=40 name=password></td></tr>
<tr><td colspan=2><input type=submit class=btn value='Ey�a'></td></tr>
</form>
</table>
<span style="font-weight:bold;font-size:16">Athugi�! Me� �v� a� nota �etta form er a�gangnum eytt �t 
varanlega!<br />
Notandanafni� og netfangi� bundi� vi� a�ganginn er �noth�ft � framt��arskr�ningum.</span>
<?
stdfoot();
?>
