<?
require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
    stderr("Error", "No Access");

//You can remove this function if you have it in global.php
function ratios($up,$down, $color = True)
{
    if ($down > 0)
    {
        $r = number_format($up / $down, 2);
    if ($color)
            $r = "<font color=".get_ratio_color($r).">$r</font>";
    }
    else
        if ($up > 0)
          $r = "Inf.";
      else
          $r = "---";
    return $r;
}

stdhead("Search in IP History");
begin_main_frame();

print("<center><h1>Search in IP History</h1></center>\n");
print("<table align=center border=1 cellspacing=0 width=115 cellpadding=5>\n");
print("<tr><td align=left>IP:</td>\n");
print("<td align=left><form method=\"get\" action=$_SERVER[PHP_SELF]>\n");
print("<input type=\"text\" name=\"ip\" size=\"40\" value=\"" . htmlspecialchars($ip) . "\">\n");
print("<tr><td td align=left>Mask:</td><td align=left>\n");
print("<input type=\"text\" name=\"mask\" size=\"40\" value=\"" . htmlspecialchars($mask) . "\"></td></tr>\n");
print("<tr><td align=right colspan=2><input type=submit value=" . SEARCH . " style='height: 20px' /></form>");
print("</td></tr></table><br><br>\n");


$ip = trim($_GET['ip']);
if ($ip)
  {
      $regex = "/^(((1?\d{1,2})|(2[0-4]\d)|(25[0-5]))(\.\b|$)){4}$/";
    if (!preg_match($regex, $ip))
    {
        stdmsg("Error", "Invalid IP.");
        end_main_frame();
        stdfoot();
        die();
    }

    $mask = trim($_GET['mask']);
    if ($mask == "" || $mask == "255.255.255.255")
    {
       $where1 = "u.ip = '$ip'";
       $where2 = "iplog.ip = '$ip'";
       $dom = @gethostbyaddr($ip);
       if ($dom == $ip || @gethostbyname($dom) != $ip)
          $addr = "";
       else
          $addr = $dom;
    }
    else
    {
       if (substr($mask,0,1) == "/")
          {
           $n = substr($mask, 1, strlen($mask) - 1);
         if (!is_numeric($n) or $n < 0 or $n > 32)
         {
            stdmsg("Error", "Invalid subnet mask.");
               end_main_frame();
            stdfoot();
            die();
         }
         else
             $mask = long2ip(pow(2,32) - pow(2,32-$n));
       }
       elseif (!preg_match($regex, $mask))
       {
          stdmsg("Error", "Invalid subnet mask.");
          end_main_frame();
          stdfoot();
          die();
       }
       $where1 = "INET_ATON(u.ip) & INET_ATON('$mask') = INET_ATON('$ip') & INET_ATON('$mask')";
       $where2 = "INET_ATON(iplog.ip) & INET_ATON('$mask') = INET_ATON('$ip') & INET_ATON('$mask')";
       $addr = "Mask: $mask";
    }

  $queryc = "SELECT COUNT(*) FROM
           (
             SELECT u.id FROM users AS u WHERE $where1
             UNION SELECT u.id FROM users AS u RIGHT JOIN iplog ON u.id = iplog.userid WHERE $where2
             GROUP BY u.id
           ) AS ipsearch";
           
  $res = mysql_query($queryc) or sqlerr(__FILE__, __LINE__);
  $row = mysql_fetch_array($res);
  $count = $row[0];
  
  if ($count == 0)
  {
      print("<br><b>No users found</b>\n");
      end_main_frame();
      stdfoot();
      die;
  }
      
  $order = $_GET['order'];
  $page = 0 + $_GET["page"];
  $perpage = 20;

  list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, 
"$_SERVER[PHP_SELF]?ip=$ip&mask=$mask&order=$order&");

  if ($order == "added")
    $orderby = "added DESC";
elseif ($order == "username")
    $orderby = "UPPER(username) ASC";
elseif ($order == "email")
    $orderby = "email ASC";
elseif ($order == "last_ip")
    $orderby = "last_ip ASC";
elseif ($order == "last_access")
    $orderby = "last_ip ASC";
else
    $orderby = "access DESC";

  $query = "SELECT * FROM (
          SELECT u.id, u.username, u.ip AS ip, u.ip AS last_ip, u.last_access, u.last_access AS access, u.email, 
u.invitari, u.added, u.class, u.uploaded, u.downloaded, u.donor, u.enabled, u.warned
          FROM users AS u
          WHERE $where1
          UNION SELECT u.id, u.username, iplog.ip AS ip, u.ip as last_ip, u.last_access, max(iplog.access) AS 
access, u.email, u.invitari, u.added, u.class, u.uploaded, u.downloaded, u.donor, u.enabled, u.warned
          FROM users AS u
          RIGHT JOIN iplog ON u.id = iplog.userid
          WHERE $where2
          GROUP BY u.id ) as ipsearch
          GROUP BY id
          ORDER BY $orderby
          $limit";


  $res = mysql_query($query) or sqlerr(__FILE__, __LINE__);

  begin_frame("$count users have used the IP: $ip ($addr)", True);

      if ($count > $perpage)
          echo $pagertop;
    echo "<table border=1 cellspacing=0 cellpadding=5>\n";
    echo "<tr><td class=colhead align=left><a class=colhead href=\"" . $_SERVER['PHP_SELF'] . 
"?ip=$ip&mask=$mask&order=username\">Username</a></td>".
          "<td class=colhead align=left>Ratio</td>".
        "<td class=colhead align=left><a class=colhead href=\"" . $_SERVER['PHP_SELF'] . 
"?ip=$ip&mask=$mask&order=email\">Email</a></td>".
        "<td class=colhead align=left><a class=colhead href=\"" . $_SERVER['PHP_SELF'] . 
"?ip=$ip&mask=$mask&order=last_ip\">Last IP</a></td>".
        "<td class=colhead align=left><a class=colhead href=\"" . $_SERVER['PHP_SELF'] . 
"?ip=$ip&mask=$mask&order=last_access\">Last access</a></td>".
        "<td class=colhead align=left>Num of IP's</td>".
        "<td class=colhead align=left><a class=colhead href=\"" . $_SERVER['PHP_SELF'] . 
"?ip=$ip&mask=$mask\">Last access on <br>$ip</a></td>".
        "<td class=colhead align=left><a class=colhead href=\"" . $_SERVER['PHP_SELF'] . 
"?ip=$ip&mask=$mask&order=added\">Added</a></td>".
        "<td class=colhead align=left>Invited by</td>";

    while ($user = mysql_fetch_array($res))
    {
        if ($user['added'] == '0000-00-00 00:00:00')
          $user['added'] = '---';
      if ($user['last_access'] == '0000-00-00 00:00:00')
          $user['last_access'] = '---';

      if ($user['last_ip'])
      {
        $nip = ip2long($user['last_ip']);
        $auxres = mysql_query("SELECT COUNT(*) FROM bans WHERE $nip >= first AND $nip <= last") or 
sqlerr(__FILE__, __LINE__);
        $array = mysql_fetch_row($auxres);
           if ($array[0] == 0)
              $ipstr = $user['last_ip'];
        else
              $ipstr = "<a href='/testip.php?ip=" . $user['last_ip'] . "'><font color='#FF0000'><b>" . 
$user['last_ip'] . "</b></font></a>";
            }
            else
          $ipstr = "---";
          
        $resip = mysql_query("SELECT ip FROM iplog WHERE userid=" . $user['id'] . " GROUP BY iplog.ip") or 
sqlerr(__FILE__, __LINE__);
        $iphistory = mysql_num_rows($resip);
        
        
        if ($user["invitari"] > 0)
        {
           $auxres = mysql_query("SELECT username FROM users WHERE id=$user[invitari]");
           $array = mysql_fetch_array($auxres);
           $invitari = $array["username"];
           if ($invitari == "")
              $invitari = "<i>[Deleted]</i>";
           else
              $invitari = "<a href=userdetails.php?id=$user[invitari]>$invitari</a>";
        }
        else
           $invitari = "--";

           echo "<tr><td><b><a href='userdetails.php?id=" . $user['id'] . "'>" .
             $user['username']."</a></b>" . get_user_icons($user) . "</td>".
          "<td>" . ratios($user['uploaded'], $user['downloaded']) . "</td>
          <td>" . $user['email'] . "</td><td>" . $ipstr . "</td>
          <td><div align=center>" . $user['last_access'] . "</div></td>
          <td><div align=center><b><a href=iphistory.php?id=" . $user['id'] . ">" . $iphistory. 
"</a></b></div></td>
          <td><div align=center>" . $user['access'] . "</div></td>
          <td><div align=center>" . $user['added'] . "</div></td>
          <td><div align=center>" . $invitari . "</div></td>
          </tr>\n";
    }
    echo "</table>";
    if ($count > $perpage)
        echo $pagerbottom;

  end_frame();

}

end_main_frame();
stdfoot();
die;
?>
