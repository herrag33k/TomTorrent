<?
require "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead("Panta p�tsu");
begin_frame("Panta p�tsu:", true);
begin_table();
echo 'J�, hver myndi ekki vilja panta p�tsu � Istorrent?';
end_table();
end_frame();
stdfoot();
?>
