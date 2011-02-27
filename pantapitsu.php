<?
require "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead("Panta pítsu");
begin_frame("Panta pítsu:", true);
begin_table();
echo 'Já, hver myndi ekki vilja panta pítsu á Istorrent?';
end_table();
end_frame();
stdfoot();
?>
