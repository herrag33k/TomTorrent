<?
require_once("include/bittorrent.php");
dbconn();
stdhead("�torrent lei�beiningar");
begin_main_frame();
begin_frame('�torrent deililei�beiningar');
echo '
�essar lei�beiningar eru ger�ar til a� au�velda f�lki a� deila me� forritinu �Torrent sem f�st � 
sl��inni <a href="http://www.utorrent.com">utorrent.com</a>
';
end_frame();

begin_frame(); ?>
1. Venjulegur �torrent gluggi - �tg�fa 1.2<br />
<a href="/leidbeiningar/utorrent_1.jpg"><img src="/leidbeiningar/utorrent_1_thumall.jpg" 
alt="Venjulegur �torrent gluggi - Klikka�u � myndina fyrir st�rri �tg�fu" /></a><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
2. Til a� b�a til Torrent er smellt � File � valstikunni og �ar � "Create New Torrent" en einnig 
er h�gt a� halda Control takkanum inni og �ta � N.<br />
<img src="/leidbeiningar/utorrent_2.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
3. �ar f�r�u eftirfarandi form:<br />
<img src="/leidbeiningar/utorrent_3.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
4. � �essu d�mi �tlum vi� a� deila FreeBSD 6.0 sem kom �t 4. n�vember. �ar sem �etta er mappa 
ver�um vi� a� velja "Add directory" efsti � forminu. �ar flettum vi� � sta�inn �ar sem FreeBSD 
6.0 mappan sem vi� �tlum a� deila eru sta�sett. Eftir a� mappan hefur veri� valin er sl��in 
innan har�adisksins birt � efsta hv�ta kassanum (sj� mynd).<br />
<img src="/leidbeiningar/utorrent_4.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
5. � kassanum vi� hli�ina � textanum "Tracker URL:" er skrifu� tilkynningarsl��in sem er gefin 
upp. � �essu tilviki er h�n "http://torrent.is/announce.php" (�n g�salappanna). Einnig skal 
sj� til �ess a� ekki s� haka� vi� "Start seeding" en a� �a� s� hak vi� "Private torrent" undir 
"Other" flokknum eins og s�st � mynd. Oftast er gott a� l�ta "Piece size:" vera � "(default)" og 
helst ekki breyta �v� nema s� sami hafi g��a �ekkingu � torrent t�kninni.<br />
<img src="/leidbeiningar/utorrent_5.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
6. �egar �essu formi er loki� er smellt � "Create and save as..." takkann en �� byrjar �Torrent 
a� b�a til torrent skr�na. �� kemur ferli eins og er h�r a� ne�an � myndinni.<br />
<img src="/leidbeiningar/utorrent_6.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
7. �egar ferlinu � seinasta skrefi er loki� kemur upp form sem bi�ur �ig um a� velja sta�setningu 
fyrir torrent skr�nna sem �� varst a� b�a til og getur �� vali� hva� torrent skr�in heitir. �tir 
s��an � "Save" og �tir einnig � "Close" sem er � hinu forminu.<br />
<img src="/leidbeiningar/utorrent_7.jpg" /><br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
8. N� er komi� a� �v� a� deila .torrent skr�nni og er fyllt �t � formi� sem er � Istorrent. En 
vi� ger�um mist�k �ar sem vi� getum ekki l�ti� skr�na heita bara "FreeBSD 6.0.torrent". �a� er ekkert 
m�l a� breyta nafninu �ar sem �a� hefur ekki �hrif � innihaldi�. �� f�rum vi� bara � sta�inn sem 
vi� vistu�um hana og breytum nafninu � "FreeBSD 6.0-i386.torrent" ��ur en vi� fyllum formi� 
�t.<br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
9. �egar �v� ferli er loki� fer�u aftur � �torrent og velur "File" og �ar fyrir ne�an "Add 
torrent..." e�a "Add torrent (no default save)..." en �a� �a� fer eftir stillingum. �a� 
fyrrgreinda er �� n�g � bili. �ar velur �� torrent skr�nna sem �� n��ir � fr� Istorrent. 
Eftir �a� f�r�u glugga �ar sem �� getur vali� m�ppuna sem �� bj�st til (sem vi� gerum � �essu 
d�mi) e�a skr�nna sem vi� bjuggum til. S��an er smellt � "OK".<br />
<br />
<? end_frame(); ?>

<? begin_frame(); ?>
10. �torrent fer s��an yfir skr�nna og athugar hvort h�n s� n�kv�mlega s� sama og torrent skr�in 
segir. Eftir �a� mun �torrent sj�lfkrafa tilkynna Istorrent a� deiling � skr�nna s� hafin og 
f�rslan birtist � Istorrent vefnum �ar sem venjulegir notendur geta n�� � torrent skr�nna og 
byrja� a� ni�urhala fr� ��r. Muni� s��an eftir reglunum sem var�a deilingu � efni og deila efninu 
vel.
<?
end_frame();
end_main_frame();
stdfoot();
?>
