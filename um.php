<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Um Istorrent");
begin_main_frame();
//loggedinorreturn();
begin_frame('Tilgangur skjalsins');
echo '
� �essu skjali er svara� spurningum er var�a starfssemi Istorrent.
';
end_frame();
begin_frame('Hva� er Istorrent?');
echo '
Istorrent er nafn � f�lagskap nokkurra ��sund einstaklinga sem hafa �a� a� markmi�i a� n�ta s�r BitTorrent t�knina til 
a� dreifa skr�m. � henni felst a� hver einstaklingur sem tekur ��tt � dreifingarferlinu skuldbindur sig til a� hj�lpa 
til vi� dreifingu � �v� sem hann hefur n�� �. Vefurinn var stofna�ur 1. ma� 2005 og telur n� um 5 ��sund skr��a 
a�ganga. Einnig hafa yfir 50 ��sund BitTorrent deilingar veri� skr��ar fr� upphafi. St�rt spjallsv��i er � sv��inu og geta notendur �ar spjalla� saman um �a� sem tengist og tengist ekki Istorrent.
';
end_frame();
begin_frame('Rekstur');
echo '
Rekstri f�lagsins er haldi� saman af Svavari Kjarrval (Kjarrval) � sj�lfbo�astarfi og s�r hann um 
yfirumsj�n, �r�un og fj�rm�l vefsins � me�an a�rir stj�rnendur gefa vinnu s�na vi� venjulega umsj�n hans. 
Rekstrarf� er fengi� fr� frj�lsum framl�gum og er eing�ngu nota� vi� � tengslum vi� vefinn og er allt gefi� 
upp til skatts � hverju �ri.
';
end_frame();
begin_frame('Hvernig ver� �g me�limur?');
echo '
Eing�ngu �eir sem bo�nir eru s�rstaklega af �eim sem eru n� �egar me�limir samf�lagsins og uppfylla �kve�in skilyr�i 
er hleypt inn � samf�lagi�. �etta er gert til a� stu�la a� pers�nulegra umhverfi og til a� vi�komandi a�ili hafa 
einhvern sem hann �ekkir til a� leita til ef hann �arfnast hj�lpar. �eir sem vilja bj��a einhverjum inn, l�ta 
vi�komandi f� bo�slykil sem hann notar s��an til a� n�skr� sig. �essi a�ili er �v� � �byrg� �ess sem hann bau� inn.
';
end_frame();
begin_frame('Hva� er BitTorrent?');
echo '
BitTorrent t�knin byggist � �v� a� reiknu� er skr�arsumma hverra X k�l�b�ta af �eim skr� sem � a� dreifa og ��r 
settar inn �, �samt fleirum uppl�singum, � eina skr� sem ber endinguna .torrent og getur dreifingara�ilinn dreift 
�eirri skr� eins og hann vill. �eir sem opna .torrent skr�na me� s�rst�kum forritum geta �v� s�tt skr�arb�tana fr� 
�eim sem bj� til .torrent skr�na. �l�kt m�rgum ��rum forritum, �� getur s�kjandinn byrja� a� dreifa hverjum skr�arb�t �fram um 
lei� og hann er kominn me� hann.
';
end_frame();
end_main_frame();
stdfoot();
?>
