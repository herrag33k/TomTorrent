<?
require_once("include/bittorrent.php");
dbconn();
stdhead("A�gangsheimildir");
begin_main_frame();
//loggedinorreturn();
begin_frame('Tilgangur skjalsins');
echo '
� �essu skjali er svara� spurningum er var�a a�gangsheimildir � vefnum almennt.
';
end_frame();
begin_frame('Hverjir mega nota vefinn?');
echo '
Allir mega nota vefinn sem fara eftir skilm�lum vefsins. Tengil � �� m� finna h�r fyrir ofan � efnisyfirlitinu.
';
end_frame();
begin_frame('Hverjir mega ekki nota vefinn');
echo '
�eir mega ekki nota vefinn sem ekki fara eftir fyrrgreindum skilm�lum. Auk �ess �eir sem stj�rnendur hafa 
s�rstaklega banna� a� nota vefinn. �a� m� ekki nota vefinn me� millig�ngu opinberra a�ila, �ar 
� me�al gegnum Internettengingu �eirra, e�a me� notkun IP talna � �eirra umsj�. A�ilum sem tengjast 
h�fundarr�ttarsamt�kum er stranglega banna�ur a�gangur a� Istorrent. A� lokum mega �eir ekki nota vefinn �ar sem 
umsj�narma�ur tengingarinnar hefur s�rstaklega be�i� eftir a�gangsbanni.
';
end_frame();
begin_frame('S�tt um a�gangsbann');
echo '
Umsj�narf�lk netkerfa getur sent fyrirspurn um a� banna notkun �kve�ins IP nets e�a IP talna a� efni vefsins og 
gildir banni� b��i um spjallbor�i� og ��ttt�ku vi� dreifingu. Hins vegar ver�ur einstaklingurinn a� sanna a� 
hann/h�n s� � raun og veru me� heimild (ef �ess er krafist) til �ess a� bi�ja um �etta bann. S� um a� r��a 
opinbera a�ila er �a� ��arft og �arf eing�ngu a� nefna IP neti� og �a� ver�ur banna�. Fyrirspurnirnar skal senda 
� netfangi� <a href="mailto:torrent@torrent.is">torrent@torrent.is</a>.
';
end_frame();
begin_frame('Af hverju mega opinberir a�ilar ekki nota vefinn?');
echo '
Opinberir a�ilar mega au�vita� nota vefinn, en ekki � vinnunni. �a� er ekki heilbrigt a� skattpeningar okkar 
fari � a� borga laun og tengingarkostna� opinberra stofnana svo a� starfsf�lki� �ar geti nota� vefinn. 
Vi�komandi a�ilar eru be�nir um a� stilla s�r h�f og b��a �anga� til vinnudegi er loki�.
';
end_frame();
begin_frame('Af hverju mega h�fundar�ttarsamt�k ekki nota vefinn?');
echo '
�ar sem stefna Istorrent er a� allir skuli eiga a� hafa a� vera komnir me� r�ttinn til a� dreifa og s�kja �a� 
efni sem �eir taka ��tt � a� dreifa, �� finnst okkur �a� alger ��arfi fyrir fyrrgreind samt�k a� ey�a peningum 
sem eiga a� fara � grei�slur til h�funda � a� fylgjast me� vefnum. H�fundarnir eiga skili� meira f� en �eir eru 
a� f� n�na fr� samt�kunum.
';
end_frame();
begin_frame('Af hverju hafa stj�rnendur heimild til �ess a� banna IP t�lur?');
echo '
Eins og er almennt � heiminum � dag eru ekki allir sem haga s�r � samr�mi vi� ��r leikreglur sem �j��in setur og 
s�mulei�is geta notendur broti� reglur Istorrent. �a� er samt ekki fyrr en vi� s�endurtekin brot e�a alvarleg 
brot sem fari� er a� banna notendur eftir IP t�lum.
';
end_frame();
end_main_frame();
stdfoot();
?>
