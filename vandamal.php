<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Vandam�l?");
begin_main_frame();
//loggedinorreturn();
echo '<h1 align=center>Vandam�l?</h1>';
begin_frame('Efnisyfirlit');
echo '
<a href="vandamal.php#hjalp">Hj�lp, hva� get �g gert?</a><br />
<a href="vandamal.php#adur">��ur en er spurt</a><br />
<a href="vandamal.php#hvert1">Hvert skal leita? [1]</a><br />
<a href="vandamal.php#hvert2">Hvert skal leita? [2]</a><br />
<a href="vandamal.php#hverjir">Hverjir hj�lpa ��r fyrir h�nd stj�rnenda?</a><br />
<a href="vandamal.php#hvernig">Hvernig skal spyrja um hj�lp?</a><br />
<a href="vandamal.php#ekkinefna">Hva� skal ekki nefna � hj�lparbei�num</a><br />
<a href="vandamal.php#heflausn">�g hef lausn � vandam�li sem er ekki h�r</a><br />
';
end_frame();
echo '<a name="hjalp">';
begin_frame('Hj�lp, hva� get �g gert?');
echo '
�a� fyrsta sem �� getur gert er a� sko�a �etta skjal. Tilgangurinn me� �v� er samt ekki a� veita sv�rin 
vi� vandam�lunum sj�lfum, heldur hj�lpa f�lki a� finna lausnina vi� �eim.
';
end_frame();
echo '<a name="adur">';
begin_frame('��ur en er spurt');
echo '
��ur en er spurt er fyrir bestu a� sta�festa a� �etta s� � raun og veru vandam�l. Endurtaki� �a� sem var 
gert og ef �a� sama gerist aftur, athugi� hvort a� allt var gert eins og lei�beiningarnar segja. Leiti� 
s��an hj�lpar annarra. <b>� flestum tilvikum, ��tt �tr�legt s�, eru 
flestar hj�lparbei�nir bygg�ar � vandam�lum �ar sem f�lk er ekki a� lesa 
hj�lpartextann e�a a�ra texta sem eru � kringum formin.</b>
'; 
end_frame(); echo '<a name="hvert1">';
begin_frame('Hvert skal leita? [1]');
echo '
Leita�u fyrst � opinberum texta h�r � vefnum eins og <a href="faq.php">SOS</a> og sj��u hvort �a� s� 
lausn �ar. ��ur en leita� er til spjallbor�s e�a stj�rnenda skal fyrst flokka vandam�li�. Ef �a� kemur 
vefnum sj�lfum beint vi� eins og bilanir � vefnum sj�lfum og �ess konar, skal hafa samband vi� 
stj�rnendur en �nnur vandam�l eins og a�fer�ir til a� deila e�a lei�beiningar til a� gera �msa hluti eru � h�ndum 
annarra. �� skal hafa � huga a� sum vandam�l s�nast sn�ast um vefinn sj�lfan �annig a� �a� er fyrir 
bestu a� f� �lit hj� ��rum reyndum notanda fyrst.
';
end_frame();
echo '<a name="hvert2">';
begin_frame('Hvert skal leita? [2]');
echo '
�egar �� veist hvort a� spyrja eigi stj�rnendur e�a a�ra notendur er komi� anna� ferli. Notendur eru 
hvattir til a� spyrja stj�rnendur eing�ngu ef a� a�rir notendur geta ekki leyst vandam�li�. Stj�rnendur 
hafa allan r�tt � a� neita a� veita lausnir vi� vandam�lum sem h�gt eru a� leysa me� �v� a� k�kja � 
spjallbor�i� e�a a�rar au�veldar lei�ir. Var�andi hverja skal spyrja skal helst fara eftirfarandi 
lei�ir (eftir �essari r��):<br />
<br />
1. Vefurinn. Athuga hvort a� lausnin er �egar til sta�ar � opinberum texta e�a einfaldlega � �tfyllingarforminu sj�lfu.<br />
<br />
2. Spyrja bj��anda. �essi a�ili ber �byrg� � veru �inni h�r og �v� er hann skyldugur til a� hj�lpa ��r. 
Neiti hann a� hj�lpa ��r ertu be�in(n) um a� tilkynna notandann me� tilkynningarkerfinu.<br />
<br />
3a. Spjallbor�i�. Notendur �ar g�tu hafa lent � �essum s�mu vandam�lum og leyst �au. 
<br />
3b. Irki�. Ef vi�komandi hefur forrit til a� komast � irki� er h�gt a� 
leita �anga�. �� eru notendur hvattir til a� pr�fa spjallbor�i� fyrst.<br />
<br />
4. Vefir framlei�enda BitTorrent forritanna. Vandam�li� g�ti veri� aflei�ing galla � forritinu e�a 
einhvers annars � forritinu.<br />
<br />
5. Stj�rnendur og hj�lparar. H�gt er a� spyrja stj�rnendur h�r en eing�ngu ef vandam�li� er mj�g 
alvarlegt og hefur mikilv�ga merkingu fyrir virkni vefsins. Hj�lparar sj� um a� leysa �nnur vandam�l 
sem ekki hefur veri� h�gt a� leysa � gegnum hin skrefin.<br />
<br />
Lausnir vi� vandam�lum � hverju skrefi koma ekki fram � sek�ndunni sem er spurt. S�na skal �olim��i vi� 
bi� � lausnum �ar sem f�lki� sem veit lausnina (ef einhverjir) er ekki endilega vi� allan s�larhringinn 
til a� svara ��r.
';
end_frame();
echo '<a name="hverjir">';
begin_frame('Hverjir hj�lpa ��r fyrir h�nd stj�rnenda?');
echo '
�ar sem stj�rnendur eru oft nokku� uppteknir hafa �eir �tnefnt hj�lpara til a� sj� um notendaa�sto�. 
�eir vakta oft spjallbor�in e�a irkr�sina og reyna oft a� svara eftir bestu getu. Lista yfir �� m� f� 
me� �v� a� fara � tengilinn <a href="staff.php">stj�rnendur</a> sem m� finna � efnisyfirlitinu h�r a� ofan. Mikilv�gar 
�kvar�anir eins og stefnur e�a refsingar vegna reglubrota eru samt teknar af stj�rnendum.
';
end_frame();
echo '<a name="hvernig">';
begin_frame('Hvernig skal spyrja um hj�lp?');
echo '
�egar spurt er um hj�lp � a� s�na a� �� hafir �huga � a� leysa vandam�li� og munir lausnina til 
framt��arnota. Ef a� vi�komandi kemur s��an aftur og spyr "hver var lausnin aftur?" e�a eitthva� �l�ka, 
er �l�klegt a� f�lk geri �a� n� hj�lpar �essari manneskju aftur vi� �nnur vandam�l.<br />
<br />
Taka skal fram, �egar be�i� er um hj�lp, hva� var veri� a� gera �egar �a� kom upp, hva�a BitTorrent 
forrit var er veri� a� nota og a�rar uppl�singar sem g�tu skipt m�li. Eftir atvikum skal taka fram 
hlutf�ll og st��u vi�komandi � Istorrent vefnum.<br />
<br />
�a� er mikil �kurteisi a� segja einfaldlega "hj�lp, X virkar ekki, hvernig get �g leyst �a�?" e�a 
eitthva� �l�ka og l�ta s��an f�lk spyrja �ig st��ugt � leit a� lausninni. �a� ert <b>��</b> sem �arft � 
lausninni a� halda, ekki a�rir. Veri� �v� eins n�mkv�m og geti� en takmarki� �� uppl�singarnar vi� 
��r sem g�tu skipt m�li. Veri� einnig tilb�in til a� fara eftir �eim lei�beiningum sem eru gefnar en 
fari� �� g�tilega �ar sem margir �t � heimi finnst gaman a� ey�ileggja m�guleika ��na a� lausn e�a 
anna� sem er � t�lvunni �inni.<br />
<br />
S��ast en ekki s�st, <b>ekki</b> heimta a� einstaklingar hafi samband vi� �ig � gegnum einkaskilabo� 
e�a annan �opinberan vettvang eftir a� hafa sent fyrirspurn � opinberum vettvangi �ar sem �a� er mj�g 
eigingjarnt �ar sem a� notendur sem vilja fylgjast me� umr��unni og hafa hag af henni er svipt �eim 
m�guleika a� hafa lausnina vi� hendi.
';
end_frame();
echo '<a name="ekkinefna">';
begin_frame('Hva� skal ekki nefna � hj�lparbei�num');
echo '
Eftirfarandi uppl�singar skal <b>ekki taka fram</b> � hj�lparbei�num:<br />
<br />
1. <b>Lykilor�</b>. Einnig skal for�ast a� gefa upp notandanafni� til annarra en stj�rnenda vefsins en 
��r uppl�singar �ttu eing�ngu a� vera sendar � bei�num gegnum t�lvup�st e�a gegnum vefinn sj�lfan. 
Lykilor� eru gagnslaus fyrir stj�rnendur og �v� �arf ekki a� gefa �au upp.<br />
<br />
2. <b>Au�kennislykil (e. passkey)</b>. �� � ekki a� gefa upp �ar sem h�gt er a� nota �� til a� n�ta s�r 
hlutf�llin ��n til a� n� � hluti.<br />
<br />
3. <b>Bo�slykilor�</b>. Bi�ji a�rir notendur um bo�slykilor� skal neita �eim um �a� nema �eir s�u 
stj�rnendur. �au � samt ekki a� nefna � opinberum vettvangi og er �a� hvorki �tlun n� vilji stj�rnenda a� �au s�u 
nefnd �ar.<br />
<br />
4. <b>�nnur lykilor� e�a pers�nuuppl�singar</b>. �essar uppl�singar hafa l�klegast ekkert a� gera me� 
vandam�li� sj�lft og �v� er alger ��arfi a� nefna ��r.
';
end_frame();
echo '<a name="heflausn">';
begin_frame('�g hef lausn � vandam�li sem er ekki h�r');
echo '
Fr�b�rt. Vi� gerum �a� a� okkar markmi�i a� reyna a� halda �ti lausnum � vandam�lum svo a� f�lk �arf 
ekki a� leita lengra en hinga� � vefinn til a� leysa �au. Tilkynna m� lausnirnar � t�lvup�stfangi� <a 
href="mailto:torrent@torrent.is">torrent@torrent.is</a> �samt l�singu � 
vandam�linu.
';
end_frame();
end_main_frame();
stdfoot();
?>
