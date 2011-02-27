<?

require_once("include/bittorrent.php");

dbconn(false);
stdhead("Hlekkir");


function add_link($url, $title, $description = "")
{
  $text = "<a class=altlink href=$url>$title</a>";
  if ($description)
    $text = "$text - $description";
  print("<li>$text</li>\n");
}

?>
<? if ($CURUSER) { ?>
<script type="text/javascript">
<!--
function addEngine(name,ext,cat)
{
window.sidebar.addSearchEngine(
        "http://torrent.is/ismod/"+name+".src",
        "http://torrent.is/ismod/"+name+"."+ext,
        name,
        cat );
}
//-->
</script>
<p><a href=sendmessage.php?receiver=2>Vinsamlegast látið vita af dauðum hlekkjum!</a></p>
<? } ?>
<table width=750 class=main border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>

<h2>Hlutir &aacute; &thorn;essari s&iacute;&eth;u </h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text><ul>
<li><a class=altlink href=myndaalbum.php>Mynda albúm notenda</a> - Hér getið þið skoðað myndir sem aðrir notendur hafa sent inn.
<li><a class=altlink href=mynda-upload.php>Mynda Upload</a> - Hér er hægt að senda inn myndir á albúmið til að nota í lýsingum eða persónu myndum.
<li><a class=altlink href="javascript:addEngine('istorrent','png','Torrent')">Leitarvél</a> - Is Torrent leitarvél fyrir Mozilla Firefox.
<li><a class=altlink href=rss.xml>RSS feed</a> -
  Til a&eth; nota me&eth; forritum me&eth; RSS stu&eth;ningi.
<li><a class=altlink href=rssdd.xml>RSS feed (direct download)</a> -
  Beinir beint &aacute; Torrent skr&aacute;nna.
</ul></td></tr></table>

<h2>BitTorrent uppl&yacute;singar </h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text><ul>
<li><a class=altlink href=http://dessent.net/btfaq/>Brian's BitTorrent FAQ and Guide</a> -
  Allt sem &thorn;&uacute; &thorn;arft a&eth; vita um BitTorrent, Skyldu lesning fyrir byrjendur.</font>
<li><a class=altlink href=http://10mbit.com/faq/bt/>The Ultimate BitTorrent FAQ</a> -
  Annar g&oacute;&eth;ur spurningalisti um BitTorrent.
</ul></td></tr></table>

<h2>BitTorrent Forrit </h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text><ul>
<li><a class=altlink href=http://pingpong-abc.sourceforge.net/>ABC</a> -
  "ABC is an improved client for the Bittorrent peer-to-peer file distribution solution."</li>
<li><a class=altlink href=http://azureus.sourceforge.net/>Azureus</a> -
  "Azureus is a java bittorrent client. It provides a quite full bittorrent protocol implementation using java language."</li>
<li><a class=altlink href=http://www.utorrent.com/>&micro;Torrent</a> - &micro;Torrent er ótrúlega lítið torrent forrit en þó með marga möguleika, mælum með þessu.
<li><a class=altlink href=http://bittornado.com/>BitTornado</a> -
  a.k.a "TheSHAD0W's Experimental BitTorrent Client".</li>
<li><a class=altlink href=http://www.bitconjurer.org/BitTorrent>BitTorrent</a> -
  Bram Cohen's official BitTorrent client.</li>
<li><a class=altlink href=http://ei.kefro.st/projects/btclient/>BitTorrent EXPERIMENTAL</a> -
  "This is an unsupported, unofficial, and, most importantly, experimental build of the BitTorrent GUI for Windows."</li>
<li><a class=altlink href=http://g3torrent.sourceforge.net/>G3 Torrent</a> -
  "A feature rich and graphically empowered bittorrent client written in python."</li>
<li><a class=altlink href=http://krypt.dyndns.org:81/torrent/maketorrent/>MakeTorrent</a> -
  A tool for creating torrents.</li>
<li><a class=altlink href=http://www.shareaza.com/>Shareaza</a> -
  Gnutella, eDonkey and BitTorrent client.</li>
</ul></td></tr></table>

<h2>Download s&iacute;&eth;ur</h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text><ul>
<li><a class=altlink href=http://www.btefnet.net/>BT efnet</a> - Þættir</li>
<li><a class=altlink href=http://empornium.us>Empornium</a> -
  Pr0n
</ul></td></tr></table>

<h2>Spjall samf&eacute;l&ouml;g </h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td class=text><ul>
<li><a class=altlink href=http://www.filesoup.com/>Filesoup</a> -
  BitTorrent community.</li>
<li><a class=altlink href=http://www.torrent-addiction.com/forums/index.php>Torrent Addiction</a> -
  Another BitTorrent community. [popups]</li>
<li><a class=altlink href=http://www.terabits.net/>TeraBits</a> -
Games, movies, apps both unix and win, tracker support, music, xxx.</li>
<li><a class=altlink href=http://www.ftpdreams.com/new/forum/sitenews.asp>FTP Dreams</a> - "Where Dreams Become a Reality".</li>
</ul></td></tr></table>

<h2>A&eth;rar s&iacute;&eth;ur </h2>
<table width=100% border=1 cellspacing=0 cellpadding=10>
  <tr><td class=text><ul>
<li><a class=altlink href=http://www.nforce.nl/>NFOrce</a> -
  Game and movie release tracker / forums.</li>
<li><a class=altlink href=http://www.grokmusiq.com/>grokMusiQ</a> -
  Music release tracker.</li>
<li><a class=altlink href=http://www.izonews.com/>iSONEWS</a> -
  Release tracker and forums.</li>
<li><a class=altlink href=http://www.btsites.tk>BTSITES.TK</a> -
  BitTorrent link site. [popups]</li>
<li><a class=altlink href=http://www.litezone.com/>Link2U</a> -
  BitTorrent link site.</li>
</ul></td></tr></table>

<p align=right><font size=1 color=#004E98><b>S&iacute;&eth;ast uppf&aelig;rt  30.4.05 (13:54 GMT)</b></font></p>
</td></tr></table>
<? if ($CURUSER) { ?>
<? } ?>

<?php

stdfoot();

?>
