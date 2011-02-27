<?php

	ob_start("ob_gzhandler");

  require "include/bittorrent.php";
  dbconn(false);
  loggedinorreturn();

/*
  function donortable($res, $frame_caption)
  {
    begin_frame($frame_caption, true);
    begin_table();
?>
<tr>
<td class=colhead>Sæti</td>
<td class=colhead align=left>Notandi</td>
<td class=colhead align=right>Donated</td>
</tr>
<?
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
        ++$num;
		$this = $a["donated"];
		if ($this == $last)
			$rank = "";
		else
		{
		  $rank = $num;
		}
	if ($rank && $num > 10)
    	break;
      print("<tr><td>$rank</td><td align=left><a href=userdetails.php?id=$a[id]><b>$a[username]" .
         "</b></a></td><td align=right>$$this</td></tr>");
		$last = $this;
    }
    end_table();
    end_frame();
  }
*/

  function usertable($res, $frame_caption)
  {
    begin_frame($frame_caption, true);
    begin_table();
?>
<tr>
<td class=colhead>Sæti</td>
<td class=colhead align=left>Notandi</td>
<td class=colhead>Uploadað</td>
<td class=colhead>Downloadað</td>
<td class=colhead align=right>Ratio</td>
</tr>
<?
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      if ($a["downloaded"])
      {
        $ratio = $a["uploaded"] / $a["downloaded"];
        $color = get_ratio_color($ratio);
        $ratio = number_format($ratio, 2);
        if ($color)
          $ratio = "<font color=$color>$ratio</font>";
	if($a['notoplist'] === '1')
		$tcolor = ' style="background-color:DarkGray"';
	else
		$tcolor = '';
      }
      else
        $ratio = "Endalaust.";
      echo '<tr'.$tcolor.'><td align="center">'.$num.'</td><td align="left"><a href=userdetails.php?id='.$a["id"].'><b>'. $a["username"].
         '</b></a></td><td align="right">'.mksize($a['uploaded']).
         '</td><td align="right">'.mksize($a['downloaded']).
         '</td><td align="right">'.$ratio.'</td></tr>';
    }
    end_table();
    end_frame();
  }

  function posterstable($res, $frame_caption)
  {
    begin_frame($frame_caption, true);
    begin_table();
?>
<tr>
<td class=colhead>Sæti</td>
<td class=colhead align=left>Notandi</td>
<td class=colhead align=right>Fjöldi pósta</td>
</tr>
<?
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
	if($a['notoplist'] === '1')
		$tcolor = ' style="background-color:DarkGray"';
	else
		$tcolor = '';
      ++$num;
	echo '<tr'.$tcolor.'><td align="center">'.$num.'</td>';
	echo '<td align="left"><a href=userdetails.php?id='.$a['userid'].'><b>'.$a['username']. '</b></a></td>';
	echo '<td align="left">'.$a['num'].'</td></tr>';

    }
    end_table();
    end_frame();
  }

  function _torrenttable($res, $frame_caption)
  {
    begin_frame($frame_caption, true);
    begin_table();
?>
<tr>
<td class=colhead align=center>Sæti</td>
<td class=colhead align=left>Nafn</td>
<td class=colhead align=right>Sótt.</td>
<td class=colhead align=right>Gögn</td>
<td class=colhead align=right>Se.</td>
<td class=colhead align=right>Le.</td>
<td class=colhead align=right>Sl.</td>
<td class=colhead align=right>Ratio</td>
</tr>
<?
    $num = 0;
    while ($a = mysql_fetch_assoc($res))
    {
      ++$num;
      if ($a["leechers"])
      {
        $r = $a["seeders"] / $a["leechers"];
        $ratio = "<font color=" . get_ratio_color($r) . ">" . number_format($r, 2) . "</font>";
      }
      else
        $ratio = "Endalaust.";
      print("<tr><td align=center>$num</td><td align=left><a href=details.php?id=" . $a["id"] . "&hit=1><b>" .
        $a["name"] . "</b></a></td><td align=right>" . number_format($a["times_completed"]) .
				"</td><td align=right>" . number_format($a["size"]*$a["times_completed"]/(1024*1024*1024),2) . "GB" .
        "</td><td align=right>" . number_format($a["seeders"]) .
        "</td><td align=right>" . number_format($a["leechers"]) .
        "</td><td align=right>" . ($a["leechers"] + $a["seeders"]) .
        "</td><td align=right>$ratio</td>\n");
    }
    end_table();
    end_frame();
  }

  stdhead("Topp 10");
begin_main_frame();
if($CURUSER['class'] >= UC_ADMINISTRATOR)
	$notoplist = '';
else
	$notoplist = ' AND notoplist=0';

//  $r = mysql_query("SELECT * FROM users ORDER BY donated DESC, username LIMIT 100") or die;
//  donortable($r, "Top 10 Donors");
	if(!empty($_GET['type']))
		$type = $_GET['type'];
	else
		$type = '0';
	if (!in_array($type,array(1,2,3)))
		$type = 1;
	if(!empty($_GET['lim']))
		$limit = $_GET['lim'];
	else
		$limit = '0';
	if(isset($_GET['subtype']))
		$subtype = $_GET["subtype"];

	echo '<p align="center">' .
	($type == 1 && !$limit ? "<b>Notendur</b>" : '<a href=topten.php?type=1>Notendur</a>') .
	' | '.
 	($type == 2 && !$limit ? "<b>Torrent</b>" : '<a href=topten.php?type=2>Torrent</a>') .
	'</p>';

  if ($CURUSER['class'] >= UC_GOOD_USER || $CURUSER['donor'] === 'yes')
  	$pu = TRUE;

  if ($type == 1)
  {
  	if (!$limit || $limit > 250)
  		$limit = 10;
  	if ($limit == 10 || $subtype == "ul")
  	{
		$r = mysql_query("SELECT id, username, uploaded, downloaded, notoplist FROM users WHERE enabled='yes' AND deleted=0$notoplist ORDER BY uploaded DESC LIMIT $limit") or sqlerr();
	  	usertable($r, "Topp $limit Deilarar" . ($limit == '10' && $pu ? " <font class=small> - [<a href=topten.php?type=1&amp;lim=100&amp;subtype=ul>Topp 100</a>] - [<a href=topten.php?type=1&amp;lim=250&amp;subtype=ul>Topp 250</a>]</font>" : ""));
	  }
    if ($limit == 10 || $subtype == "dl")
  	{
		  $r = mysql_query("SELECT id, username, uploaded, downloaded, notoplist FROM users WHERE enabled = 'yes' AND deleted=0$notoplist ORDER BY downloaded DESC LIMIT $limit") or sqlerr();
		  usertable($r, "Topp $limit Niðurhalarar" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=1&amp;lim=100&amp;subtype=dl>Topp 100</a>] - [<a href=topten.php?type=1&amp;lim=250&amp;subtype=dl>Topp 250</a>]</font>" : ""));
	  }
    if ($limit == 10 || $subtype == "bsh")
  	{
	  	$r = mysql_query("SELECT id, username, uploaded, downloaded, notoplist FROM users WHERE downloaded > 1073741824 AND users.deleted=0 AND enabled = 'yes'$notoplist ORDER BY uploaded / downloaded DESC LIMIT $limit") or sqlerr();
	  	usertable($r, "Topp $limit Bestu deilendur <font class=small>(minnst 1 GB niðurhalað)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=1&amp;lim=100&amp;subtype=bsh>Topp 100</a>] - [<a href=topten.php?type=1&amp;lim=250&amp;subtype=bsh>Topp 250</a>]</font>" : ""));
		}
    if ($limit == 10 || $subtype == "wsh")
  	{
	  	$r = mysql_query("SELECT id, username, uploaded, downloaded, notoplist FROM users WHERE downloaded > 1073741824 AND deleted=0 AND enabled = 'yes'$notoplist ORDER BY uploaded / downloaded ASC, downloaded DESC LIMIT $limit") or sqlerr();
	  	usertable($r, "Topp $limit verstu deilendur <font class=small>(með minnst 1 GB niðurhalað)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=1&amp;lim=100&amp;subtype=wsh>Topp 100</a>] - [<a href=topten.php?type=1&amp;lim=250&amp;subtype=wsh>Topp 250</a>]</font>" : ""));
	  }
//    if ($limit == 10 || $subtype == 'pwh') {
//	$r = mysql_query("SELECT posts.userid,users.username,topics.forumid, COUNT(*) as num, notoplist FROM posts,topics,users WHERE (posts.userid = users.id) AND (posts.topicid=topics.id) AND users.enabled='yes' AND users.deleted=0 AND topics.forumid!=11 AND topics.forumid!=12$notoplist GROUP BY posts.userid ORDER BY num DESC LIMIT $limit") or sqlerr();
//	posterstable($r, "Topp $limit spjallhórur (Spjallpóstar í \"Drasl\" ekki taldir með)" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=4&lim=25&subtype=pwh>Topp 25</a>]</font>" : ""),"Notendur");
//  }

  }
  elseif ($type == 2)
  {
   	if (!$limit || $limit > 50)
  		$limit = 10;
   	if ($limit == 10 || $subtype == "act")
  	{
		  $r = mysql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' GROUP BY t.id ORDER BY seeders + leechers DESC, seeders DESC, added ASC LIMIT $limit") or sqlerr();
		  _torrenttable($r, "Topp $limit virkustu Torrent" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=2&amp;lim=25&amp;subtype=act>Topp 25</a>] - [<a href=topten.php?type=2&amp;lim=50&amp;subtype=act>Topp 50</a>]</font>" : ""));
	  }
   	if ($limit == 10 || $subtype == "sna")
   	{
	  	$r = mysql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' GROUP BY t.id ORDER BY times_completed DESC LIMIT $limit") or sqlerr();
		  _torrenttable($r, "Topp $limit sóttustu Torrent" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=2&amp;lim=25&amp;subtype=sna>Topp 25</a>] - [<a href=topten.php?type=2&amp;lim=50&amp;subtype=sna>Topp 50</a>]</font>" : ""));
	  }
   /*	if ($limit == 10 || $subtype == "mdt")
   	{
		  $r = mysql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' AND leechers >= 5 AND times_completed > 0 GROUP BY t.id ORDER BY data DESC, added ASC LIMIT $limit") or sqlerr();
		  _torrenttable($r, "Topp $limit Torrent með mestu gögn millifærð" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=2&amp;lim=25&amp;subtype=mdt>Topp 25</a>] - [<a href=topten.php?type=2&amp;lim=50&amp;subtype=mdt>Topp 50</a>]</font>" : ""));
		}*/
   	if ($limit == 10 || $subtype == "bse")
   	{
		  $r = mysql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' AND seeders >= 5 GROUP BY t.id ORDER BY seeders / leechers DESC, seeders DESC, added ASC LIMIT $limit") or sqlerr();
	  	_torrenttable($r, "Topp $limit Best seeduðu Torrent <font class=small>(með minnst 5 a&eth; deila)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=2&amp;lim=25&amp;subtype=bse>Topp 25</a>] - [<a href=topten.php?type=2&amp;lim=50&amp;subtype=bse>Topp 50</a>]</font>" : ""));
    }
   	if ($limit == 10 || $subtype == "wse")
   	{
		  $r = mysql_query("SELECT t.*, (t.size * t.times_completed + SUM(p.downloaded)) AS data FROM torrents AS t LEFT JOIN peers AS p ON t.id = p.torrent WHERE p.seeder = 'no' AND leechers >= 5 AND times_completed > 0 GROUP BY t.id ORDER BY seeders / leechers ASC, leechers DESC LIMIT $limit") or sqlerr();
		  _torrenttable($r, "Topp $limit verst seeduðu Torrent <font class=small>(með minnst 5 a&eth; s&aelig;kja)</font>" . ($limit == 10 && $pu ? " <font class=small> - [<a href=topten.php?type=2&amp;lim=25&amp;subtype=wse>Topp 25</a>] - [<a href=topten.php?type=2&amp;lim=50&amp;subtype=wse>Topp 50</a>]</font>" : ""));
		}
  }

  end_main_frame();
  stdfoot();
?>
