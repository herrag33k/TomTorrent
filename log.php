<?
  require "include/bittorrent.php";
  dbconn(false);

  loggedinorreturn();

  // delete items older than a week
  $secs = 24 * 60 * 60;
  stdhead("A�ger�askr�");
if($CURUSER['class'] < UC_MODERATOR)
	exit('�� m�tt ekki sko�a a�ger�askr�na');
mysql_query("DELETE FROM sitelog WHERE " . gmtime() . " - UNIX_TIMESTAMP(added) > $secs") or sqlerr(__FILE__, __LINE__);
  $res = mysql_query("SELECT added, txt FROM sitelog ORDER BY added DESC") or sqlerr(__FILE__, __LINE__);
  print("<h1>A�ger�askr�</h1>\n");
  if (mysql_num_rows($res) == 0)
    print("<b>Skr�in er t�m</b>\n");
  else
  {
    print("<table border=1 cellspacing=0 cellpadding=5>\n");
    print("<tr><td class=colhead align=left>Dagsetning</td><td class=colhead align=left>T�mi</td><td class=colhead align=left>A�ger�</td></tr>\n");
    while ($arr = mysql_fetch_assoc($res))
    {
      $date = substr($arr['added'], 0, strpos($arr['added'], " "));
      $time = substr($arr['added'], strpos($arr['added'], " ") + 1);
      print("<tr><td>$date</td><td>$time</td><td align=left>$arr[txt]</td></tr>\n");
    }
    print("</table>");
  }
  print("<p>Allir t�mar eru � �slenskum t�ma (UTC).</p>\n");
  stdfoot();
  
?>
