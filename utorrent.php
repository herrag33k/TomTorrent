<?
require_once("include/bittorrent.php");
dbconn();
stdhead("µtorrent leiðbeiningar");
begin_main_frame();
begin_frame('µtorrent deilileiðbeiningar');
echo '
Þessar leiðbeiningar eru gerðar til að auðvelda fólki að deila með forritinu µTorrent sem fæst á 
slóðinni <a href="http://www.utorrent.com">utorrent.com</a>
';
end_frame();

begin_frame(); ?>
1. Venjulegur µtorrent gluggi - útgáfa 1.2<br />
<a href="/leidbeiningar/utorrent_1.jpg"><img src="/leidbeiningar/utorrent_1_thumall.jpg" 
alt="Venjulegur µtorrent gluggi - Klikkaðu á myndina fyrir stærri útgáfu" /></a><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
2. Til að búa til Torrent er smellt á File á valstikunni og þar á "Create New Torrent" en einnig 
er hægt að halda Control takkanum inni og ýta á N.<br />
<img src="/leidbeiningar/utorrent_2.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
3. Þar færðu eftirfarandi form:<br />
<img src="/leidbeiningar/utorrent_3.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
4. Í þessu dæmi ætlum við að deila FreeBSD 6.0 sem kom út 4. nóvember. Þar sem þetta er mappa 
verðum við að velja "Add directory" efsti á forminu. Þar flettum við á staðinn þar sem FreeBSD 
6.0 mappan sem við ætlum að deila eru staðsett. Eftir að mappan hefur verið valin er slóðin 
innan harðadisksins birt í efsta hvíta kassanum (sjá mynd).<br />
<img src="/leidbeiningar/utorrent_4.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
5. Í kassanum við hliðina á textanum "Tracker URL:" er skrifuð tilkynningarslóðin sem er gefin 
upp. Í þessu tilviki er hún "http://torrent.is/announce.php" (án gæsalappanna). Einnig skal 
sjá til þess að ekki sé hakað við "Start seeding" en að það sé hak við "Private torrent" undir 
"Other" flokknum eins og sést á mynd. Oftast er gott að láta "Piece size:" vera í "(default)" og 
helst ekki breyta því nema sá sami hafi góða þekkingu á torrent tækninni.<br />
<img src="/leidbeiningar/utorrent_5.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
6. Þegar þessu formi er lokið er smellt á "Create and save as..." takkann en þá byrjar µTorrent 
að búa til torrent skrána. Þá kemur ferli eins og er hér að neðan á myndinni.<br />
<img src="/leidbeiningar/utorrent_6.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
7. Þegar ferlinu í seinasta skrefi er lokið kemur upp form sem biður þig um að velja staðsetningu 
fyrir torrent skránna sem þú varst að búa til og getur þú valið hvað torrent skráin heitir. Ýtir 
síðan á "Save" og ýtir einnig á "Close" sem er á hinu forminu.<br />
<img src="/leidbeiningar/utorrent_7.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
8. Nú er komið að því að deila .torrent skránni og er fyllt út í formið sem er á Istorrent. En 
við gerðum mistök þar sem við getum ekki látið skrána heita bara "FreeBSD 6.0.torrent". Það er ekkert 
mál að breyta nafninu þar sem það hefur ekki áhrif á innihaldið. Þá förum við bara á staðinn sem 
við vistuðum hana og breytum nafninu í "FreeBSD 6.0-i386.torrent" áður en við fyllum formið 
út.<br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
9. Þegar því ferli er lokið ferðu aftur í µtorrent og velur "File" og þar fyrir neðan "Add 
torrent..." eða "Add torrent (no default save)..." en það það fer eftir stillingum. Það 
fyrrgreinda er þó nóg í bili. Þar velur þú torrent skránna sem þú náðir í frá Istorrent. 
Eftir það færðu glugga þar sem þú getur valið möppuna sem þú bjóst til (sem við gerum í þessu 
dæmi) eða skránna sem við bjuggum til. Síðan er smellt á "OK".<br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
10. µtorrent fer síðan yfir skránna og athugar hvort hún sé nákvæmlega sú sama og torrent skráin 
segir. Eftir það mun µtorrent sjálfkrafa tilkynna Istorrent að deiling á skránna sé hafin og 
færslan birtist á Istorrent vefnum þar sem venjulegir notendur geta náð í torrent skránna og 
byrjað að niðurhala frá þér. Munið síðan eftir reglunum sem varða deilingu á efni og deila efninu 
vel.
<?
end_frame();
end_main_frame();
stdfoot();
?>
