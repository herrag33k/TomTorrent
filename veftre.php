<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Veftr�");
begin_main_frame();
//loggedinorreturn();
begin_frame('Veftr�');
?>
Atri�i me� rau�um sk�ringartexta krefst innskr�ningar til a� fara inn �.<br />
<a href="/">A�als��a</a><br />
&nbsp;&nbsp;<a href="/login.php">Innskr�ning</a> - Skr� sig inn � vefinn<br />
&nbsp;&nbsp;<a href="/signup.php">N�skr�ning</a> - B�a til a�gang inn � vefinn<br />
&nbsp;&nbsp;<a href="/browse.php">Skr�r</a> - <span style="color:red">Innsend torrent</span><br />
&nbsp;&nbsp;<a href="/upload.php">Deila</a> - <span style="color:red">Senda inn torrent</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/mytorrents.php">M�n torrent</a> - <span style="color:red">Umsj�n me� ��num torrentum</span><br />
&nbsp;&nbsp;<a href="/my.php">Pr�f�ll</a> - <span style="color:red">Breyttu uppl�singum um �ig</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/mymyndir.php">M�nar myndir</a> - <span style="color:red">Umsj�n me� ��num myndum (Virkir notendur og h�rri)</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/mynda-upload.php">Senda inn mynd</a> - <span style="color:red">Senda inn mynd (Virkir notendur e�a h�rri)</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/myndaalbum.php">Sko�a myndir annarra notenda</a> - <span style="color:red">Myndir sem notendur hafa sent inn</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/friends.php">Vinir</a> - <span style="color:red">Umsj�n me� ��num vinalista</span><br />
<? if($CURUSER['id']) { ?>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/userdetails.php?id=<?=$CURUSER['id']?>">Uppl�singar um �ig</a> - <span style="color:red">Uppl�singas��an ��n fyrir almenning</span><br />
<? } ?>
&nbsp;<a href="/personuvernd.php">Pers�nuverndarstefna</a> - Stefna Istorrent var�andi pers�nuuppl�singar<br />
&nbsp;&nbsp;Skilabo�akerfi�<br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/inbox.php">Skilabo� til ��n</a> - <span style="color:red">Br�f inn</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/inbox.php">Skilabo� fr� ��r</a> - <span style="color:red">Br�f �t</span><br />
&nbsp;&nbsp;<a href="/viewrequests.php">Eftirspurnir</a> - <span style="color:red">Eftirspurnir</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/requests.php">Senda inn</a> - <span 
style="color:red">Leggja inn eftirspurn</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/minar_eftirsp.php">M�nar eftirspurnir</a> - <span style="color:red">Umsj�n me� ��num eftirspurnum</span><br />
&nbsp;&nbsp;<a href="/forums.php">Spjallbor�</a> - <span style="color:red">Umr��usv��i fyrir notendur vefsins</span><br />
&nbsp;&nbsp;<a href="/invites.php">Bo�slyklar</a> - <span style="color:red">Bj��a inn notanda/sj� hverjum �� hefur bo�i�</span><br />
&nbsp;&nbsp;<a href="/links.php">Hlekkir</a> - Hlekkir yfir � a�ra vefi<br />
&nbsp;&nbsp;<a href="/styrkir.php">Styrkja</a> - Uppl�singar hvernig � a� styrkja Istorrent<br />
&nbsp;&nbsp;<a href="/topten.php">Topp 10</a> - <span style="color:red">�msar topp t�lur um notendur og torrent</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/topten.php?type=1">Topp 10 notendur</a> - <span style="color:red">Notendur</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/topten.php?type=2">Topp 10 torrent</a> - <span style="color:red">Torrent</span><br />
&nbsp;&nbsp;<a href="/log.php">A�ger�askr�</a> - <span style="color:red">Yfirlit yfir a�ger�ir</span><br />
&nbsp;&nbsp;<a href="/rules.php">Reglur</a> - Reglur vefsins<br />
&nbsp;&nbsp;<a href="/disclaimer.php?form=nei">Skilm�lar</a> - Skilyr�i fyrir notkun vefsins<br />
&nbsp;&nbsp;<a href="/staff.php">Stj�rnendur</a> - <span style="color:red">Uppl�singar um stj�rnendur og hj�lpara</span><br />
&nbsp;&nbsp;<a href="/bodslykill.php">Bo�slyklar</a> - Um bo�slykla<br />
&nbsp;&nbsp;<a href="/hlutfoll.php">Hlutf�ll</a> - Um hlutf�ll<br />
&nbsp;&nbsp;<a href="/users.php">Notendalisti</a> - <span style="color:red">Listi yfir notendur vefsins</span><br />
&nbsp;&nbsp;RSS<br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/rss.xml">RSS n�justu torrent</a> - RSS yfir n�justu torrent<br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/rssdd.xml">RSS n�justu torrent skr�r</a> - RSS yfir n�justu torrent me� tenglum � torrent skr�rnar<br />
&nbsp;&nbsp;<a href="/utorrent.php">�torrent tilraunalei�beiningar</a> - Lei�beiningar fyrir �torrent<br />
&nbsp;&nbsp;<a href="/faq.php">SOS</a> - Spurt og svara�<br />
&nbsp;&nbsp;<a href="/hjalp.php">Hj�lparkerfi</a> - Finnur �t vandam�li� �itt.<br />
&nbsp;&nbsp;<a href="/vandamal.php">Vandam�l?</a> - Hvernig skal spyrja um hj�lp<br />
&nbsp;&nbsp;<a href="/1skipti.php">Fyrsta skipti� h�r?</a> - Sm� kynning fyrir byrjendur<br />
<?
end_frame();
end_main_frame();
stdfoot();
?>
