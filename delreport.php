<?php

require_once("include/bittorrent.php");

dbconn(false);

loggedinorreturn();

if (get_user_class() < UC_SYSOP)
{
stdhead("Ey�a tilkynningu");

begin_main_frame();
print("<h1>Eing�ngu stj�rnendur, fyrirgef�u</h1>");
end_main_frame();
stdfoot();
die();
}

$id = $_GET["id"];

$res = mysql_query("DELETE FROM reports WHERE id =$id") or sqlerr();


header("Refresh: 0; url=reports.php");
?>
