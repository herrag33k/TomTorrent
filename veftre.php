<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Veftré");
begin_main_frame();
//loggedinorreturn();
begin_frame('Veftré');
?>
Atriði með rauðum skýringartexta krefst innskráningar til að fara inn á.<br />
<a href="/">Aðalsíða</a><br />
&nbsp;&nbsp;<a href="/login.php">Innskráning</a> - Skrá sig inn á vefinn<br />
&nbsp;&nbsp;<a href="/signup.php">Nýskráning</a> - Búa til aðgang inn á vefinn<br />
&nbsp;&nbsp;<a href="/browse.php">Skrár</a> - <span style="color:red">Innsend torrent</span><br />
&nbsp;&nbsp;<a href="/upload.php">Deila</a> - <span style="color:red">Senda inn torrent</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/mytorrents.php">Mín torrent</a> - <span style="color:red">Umsjón með þínum torrentum</span><br />
&nbsp;&nbsp;<a href="/my.php">Prófíll</a> - <span style="color:red">Breyttu upplýsingum um þig</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/mymyndir.php">Mínar myndir</a> - <span style="color:red">Umsjón með þínum myndum (Virkir notendur og hærri)</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/mynda-upload.php">Senda inn mynd</a> - <span style="color:red">Senda inn mynd (Virkir notendur eða hærri)</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/myndaalbum.php">Skoða myndir annarra notenda</a> - <span style="color:red">Myndir sem notendur hafa sent inn</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/friends.php">Vinir</a> - <span style="color:red">Umsjón með þínum vinalista</span><br />
<? if($CURUSER['id']) { ?>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/userdetails.php?id=<?=$CURUSER['id']?>">Upplýsingar um þig</a> - <span style="color:red">Upplýsingasíðan þín fyrir almenning</span><br />
<? } ?>
&nbsp;<a href="/personuvernd.php">Persónuverndarstefna</a> - Stefna Istorrent varðandi persónuupplýsingar<br />
&nbsp;&nbsp;Skilaboðakerfið<br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/inbox.php">Skilaboð til þín</a> - <span style="color:red">Bréf inn</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/inbox.php">Skilaboð frá þér</a> - <span style="color:red">Bréf út</span><br />
&nbsp;&nbsp;<a href="/viewrequests.php">Eftirspurnir</a> - <span style="color:red">Eftirspurnir</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/requests.php">Senda inn</a> - <span 
style="color:red">Leggja inn eftirspurn</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/minar_eftirsp.php">Mínar eftirspurnir</a> - <span style="color:red">Umsjón með þínum eftirspurnum</span><br />
&nbsp;&nbsp;<a href="/forums.php">Spjallborð</a> - <span style="color:red">Umræðusvæði fyrir notendur vefsins</span><br />
&nbsp;&nbsp;<a href="/invites.php">Boðslyklar</a> - <span style="color:red">Bjóða inn notanda/sjá hverjum þú hefur boðið</span><br />
&nbsp;&nbsp;<a href="/links.php">Hlekkir</a> - Hlekkir yfir á aðra vefi<br />
&nbsp;&nbsp;<a href="/styrkir.php">Styrkja</a> - Upplýsingar hvernig á að styrkja Istorrent<br />
&nbsp;&nbsp;<a href="/topten.php">Topp 10</a> - <span style="color:red">Ýmsar topp tölur um notendur og torrent</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/topten.php?type=1">Topp 10 notendur</a> - <span style="color:red">Notendur</span><br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/topten.php?type=2">Topp 10 torrent</a> - <span style="color:red">Torrent</span><br />
&nbsp;&nbsp;<a href="/log.php">Aðgerðaskrá</a> - <span style="color:red">Yfirlit yfir aðgerðir</span><br />
&nbsp;&nbsp;<a href="/rules.php">Reglur</a> - Reglur vefsins<br />
&nbsp;&nbsp;<a href="/disclaimer.php?form=nei">Skilmálar</a> - Skilyrði fyrir notkun vefsins<br />
&nbsp;&nbsp;<a href="/staff.php">Stjórnendur</a> - <span style="color:red">Upplýsingar um stjórnendur og hjálpara</span><br />
&nbsp;&nbsp;<a href="/bodslykill.php">Boðslyklar</a> - Um boðslykla<br />
&nbsp;&nbsp;<a href="/hlutfoll.php">Hlutföll</a> - Um hlutföll<br />
&nbsp;&nbsp;<a href="/users.php">Notendalisti</a> - <span style="color:red">Listi yfir notendur vefsins</span><br />
&nbsp;&nbsp;RSS<br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/rss.xml">RSS nýjustu torrent</a> - RSS yfir nýjustu torrent<br />
&nbsp;&nbsp;&nbsp;&nbsp;<a href="/rssdd.xml">RSS nýjustu torrent skrár</a> - RSS yfir nýjustu torrent með tenglum á torrent skrárnar<br />
&nbsp;&nbsp;<a href="/utorrent.php">µtorrent tilraunaleiðbeiningar</a> - Leiðbeiningar fyrir µtorrent<br />
&nbsp;&nbsp;<a href="/faq.php">SOS</a> - Spurt og svarað<br />
&nbsp;&nbsp;<a href="/hjalp.php">Hjálparkerfi</a> - Finnur út vandamálið þitt.<br />
&nbsp;&nbsp;<a href="/vandamal.php">Vandamál?</a> - Hvernig skal spyrja um hjálp<br />
&nbsp;&nbsp;<a href="/1skipti.php">Fyrsta skiptið hér?</a> - Smá kynning fyrir byrjendur<br />
<?
end_frame();
end_main_frame();
stdfoot();
?>
