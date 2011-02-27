<?
mysql_connect('localhost', 'istorrent', 'torrenter1') or die(msqyl_error());
mysql_select_db('istorrent') or die(msqyl_error());
mysql_query("select * from innmyndir") or die(mysqk_error());
?>