<?
  require "include/bittorrent.php";
  dbconn(false);

  loggedinorreturn();

  // delete items older than a week
  $secs = 24 * 60 * 60;
  stdhead("Aðgerðaskrá");
if($CURUSER['class'] < UC_MODERATOR)
	exit('Þú mátt ekki skoða aðgerðaskrána');
mysql_query("DELETE FROM sitelog WHERE " . gmtime() . " - UNIX_TIMESTAMP(added) > $secs") or sqlerr(__FILE__, __LINE__);
  $res = mysql_query("SELECT added, txt FROM sitelog ORDER BY added DESC") or sqlerr(__FILE__, __LINE__);
  print("<h1>Aðgerðaskrá</h1>\n");
  if (mysql_num_rows($res) == 0)
    print("<b>Skráin er tóm</b>\n");
  else
  {
    print("<table border=1 cellspacing=0 cellpadding=5>\n");
    print("<tr><td class=colhead align=left>Dagsetning</td><td class=colhead align=left>Tími</td><td class=colhead align=left>Aðgerð</td></tr>\n");
    while ($arr = mysql_fetch_assoc($res))
    {
      $date = substr($arr['added'], 0, strpos($arr['added'], " "));
      $time = substr($arr['added'], strpos($arr['added'], " ") + 1);
      print("<tr><td>$date</td><td>$time</td><td align=left>$arr[txt]</td></tr>\n");
    }
    print("</table>");
  }
  print("<p>Allir tímar eru á íslenskum tíma (UTC).</p>\n");
  stdfoot();
  
?>
