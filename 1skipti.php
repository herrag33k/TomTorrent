<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Fyrsta skipti�");
begin_main_frame();
//loggedinorreturn();
begin_frame('Fyrsta skipti�');
echo '
Um lei� og vi� �skum ��r til hamingju me� a� vera bo�i� � Istorrent viljum vi� veita ��r mikilv�gar 
uppl�singar sem gagnast ��r � veru �inni � Istorrent og f�kka vandr��atilfellum.
';
end_frame();
begin_frame('Hva� er Istorrent?');
echo '
Istorrent er samf�lag �ar sem notendur geta deilt og s�tt skr�r me� BitTorrent t�kninni. Helsti 
kosturinn er a� skr�arumfer�in fer fram beint � milli notenda en vefurinn s�r eing�ngu um a� mi�la 
helstu uppl�singum � milli notenda.
';
end_frame();
begin_frame('Er Istorrent fyrir �ig?');
echo '
Hreinskilnin er ofarlega � bla�i hj� stj�rnendum �essa vefs og viljum vi� koma �v� � framf�ri a� 
Istorrent er ekki vi� h�fi allra. T�kni�ekking og/e�a sj�lfsbjargarvi�leitni er eitt af �v� sem 
vefurinn reynir nokku� � � upphafi en ver�ur s��an l�ti� m�l �egar notandinn er vanari �v� umhverfi sem hann 
byggir �.<br />
<br />
Til a� taka ��tt � torrent samf�laginu �arf a� hafa eftirfarandi:<br />
* T�lvu<br />
* Tengingu vi� Interneti�<br />
* Hugb�na� sem getur unni� me� BitTorrent sta�alinn.<br />
<br />
Mj�g gott er, ef h�gt er, a� hafa kveikt � t�lvunni yfir sem lengst t�mabil me� BitTorrent hugb�na�inn � gangi.
';
end_frame();
begin_frame('Hva� er BitTorrent?');
echo '
BitTorrent (oft kalla� "torrent") er samskiptasta�all sem heimilar dreifingu � skr�m gegnum Interneti�. 
Sta�allinn heimilar s�kjanda a� hj�lpa til vi� dreifingu �eirra skr�a sem er deilt. Um lei� og hver 
s�kjandi er kominn me� b�t af skr�nni, dreifir hann t��um skr�arb�t �fram til annarra s�kjenda sem 
vantar hann.<br />
<br />
Deilandi byrjar � �v� a� b�a til svonefnda ".torrent" skr� sem geymir helstu uppl�singar 
um skr�na e�a skr�rnar sem � a� fara a� deila �samt sl��ina a� deilista�num. Hann sendir s��an skr�na 
til deilista�arins sem s�r s��an um a� halda �ti uppl�singar um ��tttakendur. A�ilar sem vilja taka 
��tt � dreifingunni n� � ".torrent" skr�na og s�kja s��an uppl�singar til deilista�arins, f� 
uppl�singar um a�ra ��tttakendur og tengjast �eim s��an beint. Skr�arumfer�in sj�lf fer �v� ekki � 
gegnum deilista�inn, heldur beint � milli ��tttakenda.
';
end_frame();
begin_frame('Hverjir nota BitTorrent?');
echo '
M�rg fyrirt�ki eru a� nota �essa t�kni n� �egar og hafa s�� notagildi� � henni til a� minnka umfer� � 
net�j�na s�na og �araflei�andi spara kostna� vi� a� halda �ti st�rri tengingu vi� Interneti� en �eir 
myndu gera vi� venjulegar a�st��ur. Einnig hafa margir dreifendur gjaldfrj�lsra st�rikerfa teki� upp 
BitTorrent a�fer�ina sem valkost vi� venjulegu a�fer�ina og m� �� nefna <a 
href="http://www.is.freebsd.org">FreeBSD</a> og <a href="http://www.redhat.com">Red Hat</a>. 
Leikjaframlei�andinn <a href="http://www.blizzard.com">Blizzard</a> hefur teki� upp dreifingara�fer�ina 
sem eina valkostinn til a� dreifa uppf�rslum fyrir leikinn <a href="http://wow-europe.com">World of Warcraft</a>.
';
end_frame();
begin_frame('Byrjunar�r�ugleikar?');
echo '
Istorrent b��ur upp � mikla hj�lp � spjallflokknum "Lei�beiningar" � spjallbor�inu og eru notendur hvattir til 
a� leita � spjallbor�inu ��ur en stj�rnendur eru spur�ir um hj�lp. �eir vinna mj�g g�fult starf sem 
sj�lfbo�ali�ar og �a� er betra a� �n��a �� ekki nema �a� s� alger nau�syn. Einnig er helstu spurningum svara� 
� "SOS" (Spurt og svara�) og er mikilv�gt a� allir venji sig � a� k�kja �anga� fyrst.
';
end_frame();
begin_frame('Hlutf�ll skipta m�li');
echo '
Me� meiri notkun � vefnum kemstu a� hugtakinu hlutf�ll en �a� er nota� sem m�lit�ki � �v� magni af 
b�tum sem �� deilir til annarra deilt me� �v� magni sem �� n�r� � fr� ��rum. Hlutfalli� 1.0 merkir a� 
�� deilir jafn miklu og �� s�kir fr� ��rum og er vi�unandi a� vera � �eirri a�st��u e�a hafa h�rra en 
1.0 sem ���ir a� �� deilir meiru en �� s�kir. � me�an deilimagni� og ni�urhalsmagni� er l�ti�, geta 
hlutf�llin teki� sn�ggum breytingum og er �v� ekki gott a� vera a� s�kja miki� � upphafi.<br />
<br />
�egar svonefndur reynslut�mi er li�inn, 2 vikur, byrja hlutf�llin a� hafa �hrif � veru notenda. S�u 
hlutf�llin of l�g byrjar notandinn a� finna fyrir skertum r�ttindum � vefnum en h� hlutf�ll geta b�tt 
st��u �eirra verulega.<br />
<br />
N�nar um hlutf�ll er h�gt a� finna � sl��inni <a href="http://torrent.is/hlutfoll.php">torrent.is/hlutfoll.php</a>.
';
end_frame();
begin_frame('Hverju skal deila og hve miklu?');
echo '
Margir notendur gera �au lei�u mist�k a� deila bara �v� sem �eir finna � har�adisknum s�num en 
svolei�is virkar ekki Istorrent. Sumir halda a� deilimagni� �eirra h�kki sj�lfkrafa bara me� �v� a� 
b�a til ".torrent" skr� og senda en � raun og veru skiptir eing�ngu m�li raunmagni� sem sendist � milli 
notenda.<br />
<br />
�nnur mist�k sem notendur eiga til me� a� gera er a� bj��a fram efni sem er �egar � dreifingu af ��rum 
notanda en h�gt er a� leysa �r vandanum me� �v� a� framkv�ma leit a� skr�nni og sj� hvort a� einhver 
ni�ursta�a kemur fram. Eing�ngu m� hefja deilingu � sama efninu ef �etta er talsvert betri �tg�fa e�a 
lagf�ring � �tg�fu sem er �egar komin.<br />
<br />
�egar skr� er bo�in fram skal passa a� n�gur t�mi s� til a� deila skr�nni alveg ��ur en notandi fer a� 
sl�kkva � t�lvunni �v� ekki er liti� vel � �a� a� notendur s�u a� gera hl� � skr�ardeilingu �n �ess a� 
minnsta kosti annar notandi s� kominn me� allt efni�. �eir sem �urfa t.d. a� hafa sl�kkt � t�lvunni 
yfir n�tt �ttu helst a� hefja deilingu � morgnana og passa a� �eir geti kl�ra� a� senda allt efni� � a� 
minnsta kosti einn annan notanda ��ur en sl�kkt er � t�lvunni n�stu n�tt. Ef skr�in er nokku� st�r e�a 
deiling gengur h�gar en ��tla� var er �g�tt a� nefna skort � deilingu � l�singu og � hva�a t�mabili m� 
b�ast vi� st��vun � deilingu.<br />
<br />
�urfi notandi a� spila netleiki e�a gera anna� sem er bandv�ddarfrekt er �g�tt a� h�gja frekar � 
bandv�ddinni sem BitTorrent forriti� tekur frekar en a� st��va deilingu alveg.
';
end_frame();
begin_frame('A� bj��a fram efni er ekki nau�synlegt');
echo '
Notendur sem ekki hafa �ekkingu af �v� a� b�a til ".torrent" skr�r �urfa ekki a� hafa �hyggjur af 
�v� a� �urfa a� b�a ��r til. H�gt er a� taka ��tt � vefnum me� g�� hlutf�ll �n �ess a� bj��a fram 
eina einustu ".torrent" skr�.<br />
<br />
Sumir notendur stunda �a� a� n� � br��lega vins�lar skr�r stuttu eftir 
a� annar notandi hefur bo�i� ��r fram og f� �v� auki� deilimagn fyrir �a� eing�ngu a� dreifa skr�nni 
�fram til annarra notenda. �eir notendur rannsaka e�a finna �t hva�a efni er/ver�ur vins�lt � 
dreifingu og s�kja �a� af vefnum ��ur en margir eru b�nir a� n� � �a� og f� �v� eitthva� fyrir �a� 
ni�urhalsmagn sem �eir "fj�rfestu" � skr�num. Sumir n� � eins eintak af skr�num af opnum BitTorrent 
vefjum e�a af ��rum st��um og taka ��tt � deilingu �n �ess a� n� � skr�na. S� "fj�rfesting" er 
�h�ttulaus �ar sem h�n kostar ekkert Istorrent-ni�urhalsmagn fyrir notandann en getur veitt honum 
miki� deilimagn.<br />
<br />
�essar a�fer�ir krefjast ekki eins mikillar fyrirhafnar og a� bj��a fram efni og kostar bara sm� 
�j�lfun � upphafi. Ekki er samt r��legt a� nota hana vi� efni sem notandinn "hefur ekki efni �" 
�egar reynslut�manum er loki�.
';
end_frame();
begin_frame('Vandr��i?');
echo '
Notendur sem eru � einhverjum vandr��um eru be�nir um a� fara eftir skrefunum sem eru � hlekknum 
"<a href="http://torrent.is/vandamal.php">Vandam�l?</a>" � efnisyfirlitinu.
';
end_frame();
end_main_frame();
stdfoot();
?>
