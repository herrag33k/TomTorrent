<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Bo�slyklar");
begin_main_frame();
//loggedinorreturn();
begin_frame('Hva� er bo�slykill?');
echo '
Bo�slykill er k��i sem me�limur innan Istorrent mun gefa �eim sem hann/h�n vill bj��a. Lykillinn mun eing�ngu 
virka fyrir netfangi� sem bj��andi sl�r inn.
';
end_frame();
begin_frame('Af hverju �arf �g bo�slykil?');
echo '
Til a� stu�la a� �byrgara samf�lagi og minnka misnotkun var sett � �a� skipulag a� f�lk ber �byrg� � �eim 
sem �a� b��ur. Ef samf�lagi� v�ri alveg opi� myndi �a� valda miklum vandr��um �ar sem �a� myndi b�kstaflega 
fl��a inn f�lk sem kann ekki � samf�lagi� og �a� myndi enda me� �v� a� g��u me�limirnir myndu bara fara 
burt. Me� �essu kerfi er veri� a� tryggja a� einhver s� til sta�ar sem a� pers�nan �ekkir og getur leita� 
til ef h�n �arfnast hj�lpar.
';
end_frame();
begin_frame('Bo�slykillinn minn virkar ekki, hva� ��?');
echo '
1. Athuga�u hvort �� sl�st hann inn r�tt e�a afrita�ir hann allan yfir.<br />
2. Bj��andi g�ti hafa eytt �t bo�slyklinum.<br />
3. IP talan ��n g�ti veri� � banni en � �eim tilvikum getur�u ekki n�skr�� �ig.<br />
4. Er notandinn sem bau� ��r enn �� inn � kerfinu? Ef hann hefur veri� eyddur �t er hann ekki lengur gildur bj��andi.<br />
5. Hafir �� fari� � gegnum �ll skrefin fyrir ofan og �etta virkar ekki enn ��, <a href="mailto:torrent@torrent.is">haf�u samband vi� notendaa�sto�</a>
';
end_frame();
begin_frame('Kunningi minn getur ekki bo�i� m�r, hva� ��?');
echo '
�a� v�ri h�gt a� hvetja hann til a� b�ta sig en skilyr�in til a� geta bo�i� einhverjum inn er a� hafa veri� 
me�limur � 2 vikur og vera me� 0,85 � hlutf�ll. Uppfylli hann �essi skilyr�i �arf hann �ar a� auki a� hafa deilt 30 
g�gab�tum af g�gnum til a� f� sinn fyrsta bo�slykil. Eftir �a� f�r hann auka bo�slykil fyrir hver 5 g�gab�ti sem hann 
deilir � vi�b�t.
';
end_frame();
begin_frame('Get �g ekki fengi� undantekningu? �g mun vera dugleg(ur)');
echo '
Ef vi� f�rum a� veita undantekningar, �� ver�a ��r ekki lengur undantekningar �v� a� fleiri og fleiri munu 
nota s�r ��r og kerfi� ver�ur gagnslaust.
';
end_frame();
begin_frame('�g �ekki engan sem er me� a�gang, hvernig get �g redda� bo�slykli?');
echo '
�v� mi�ur er �a� eina sem m� gera � �eirri a�st��u er a� bi�ja �� sem ma�ur �ekkir a� redda ��r lykli �egar 
�eir hafa fengi� a�gang (og uppfylla skilyr�in til a� gefa bo�slykla).<br />
<br />
<b>Stj�rnendur �tvega ekki bo�slykla og vinsamlegast ekki fara � opinbera sta�i til �ess a� bi�ja f�lk um 
bo�slykla.</b> Hvorugu er teki� vel af stj�rnendum.
';
end_frame();
end_main_frame();
stdfoot();
?>
