<?
require_once("include/bittorrent.php");
dbconn(true);
stdhead("Styrkir");
begin_main_frame();
//loggedinorreturn();
begin_frame('Styrkja Istorrent');
echo '
Istorrent hafi� vaxi� � vins�ldum undanfari� og �v� hefur rekstrarkostna�urinn aukist. Til a� berjast gegn �essu vandam�li, h�fum vi� byrja� � s�fnun �ar sem f�lki er frj�lst a� gefa � hana ��r upph��ir sem �a� vill l�ta fr� s�r.<br />
<br />
Vi� viljum benda � �a� a� �a� er alls engin skylda a� gefa � �essa s�fnun 
og notendum ver�ur ekki refsa� � neinn h�tt fyrir a� �kve�a a� gefa ekki � 
hana.<br />
<br />
N�verandi markmi�:<br />
Engin<br />
*B�I�* Sumar � k��un verkefni�.<br />
*B�I�* 1 GB 400 MHz DDR vinnsluminni fyrir net�j�ninn.
';
end_frame();
begin_frame('� hva� fer f��?');
echo '
Stj�rn Istorrent mun �kve�a �a� � hvert skipti sem �eir sj� a� hinn 
almenni notandi muni hagnast � �v�. �egar svona �kvar�anir eru teknar eru 
notendur vefsins l�tnir vita. �eir sem eru skr��ir gefendur munu ekki 
hafa meira vald e�a atkv��isr�tt til a� �kve�a � hva� f�� fer en hinn 
almenni notandi en �eir hafa b��ir r�tt � a� koma me� hugmyndir um �a�.
';
end_frame();
begin_frame('Hva� f� �g � sta�inn?');
echo '
Vinsamlegast athugi�! <u>Styrkir eru ekki endurgreiddir</u> eftir a� �eir 
hafa veri� f�r�ir inn. Me�fer� Istorrent � styrkjum er samkv�mt styrkjareglum.<br />
<br />
Einnig ber a� geta a� <b>gefendast��unar eru ekki s�luvara</b> og 
eru �eir �v� ekki endurgreiddir ��tt vi�komandi notandi var banna�ur vegna 
reglubrota e�a einhverra hluta vegna getur ekki nota� a�ganginn.<br />
<br />
Allir �eir sem gefa 500 kr. e�a meira ver�a skr��ir sem gefendur �t 
l�ft�ma vefsins en � �v� er innifali�:<br />
- A� minnsta kosti s�mu r�ttindi og �eir sem gegna st��unni "Virkur notandi".
- Alls engin bi� eftir deiliskr�m, �h�� hlutf�llum.<br />
- H�kkun � h�lfafj�lda upp � 12.<br />
- Getur sett s�rstakan titil � �ig � gegnum pr�f�l.<br />
- Ver�ur ekki eydd(ur) �t �r�tt fyrir a� vera �virkur eins og venjan er me� venjulega a�ganga.<br />
<br />
<b>Hva� f� �g <u>ekki</u> me� �v� einu a� styrkja?:</b><br />
- �n�mi gagnvart reglunni um l�gmarkshlutfall (0.20 e�a l�gra).<br />
- Forr�ttindi e�a forgang yfir a�ra notendur Istorrent.<br />
- Einkakennslu � Istorrent e�a neitt anna�.<br />
- Undantekningar fr� reglum e�a stefnum Istorrent.<br />
- K��ann � bakvi� Istorrent.<br />
- L�gri e�a enga refsingu fyrir brot � reglum.<br />
- Inng�ngu � Istorrent samf�lagi�.<br />
';
end_frame();
begin_frame('Hvernig er h�gt a� styrkja?');
echo '
Me� �v� a� leggja inn einhverja (j�kv��a) fj�rh�� � bankareikning 
0327-13-003120 (kennitala 071183-2119).<br />
<br />
Ef �� hefur gefi� 500 kr. e�a meira og vilt merkismannast��una 
(engin skylda), sendir�u bara p�st � <a href="mailto:torrent@torrent.is">torrent@torrent.is</a> 
og greinir fr� notandanafni, fullu nafni (alv�ru nafninu ��nu) og 
kennit�lu grei�anda �samt upph��.<br />
<br />
A� gefnu tilefni ber a� nefna a� styrkir eru a� jafna�i afgreiddir � f�studagskv�ldum e�a � laugardegi.<br />
�eir sem millif�ra eftir kl. 21:00 � f�studegi munu ekki vera afgreiddir fyrr en � fyrsta lagi n�sta bankadag 
(takmarkanir v/bankans, ekki Istorrent).<br />
Fyrir fr�helgar er �v� fyrir bestu a� millif�ra t�manlega til a� tryggja a� geta noti� hennar me� r�ttindum 
skr��ra gefenda. Ef fr�helgi byrjar ekki � f�studegi eru styrkir afgreiddir nokkrum klst. ��ur en h�n hefst ef 
kostur er.
';
end_frame();
begin_frame('Mig langar a� gefa miklu meira e�a oftar, hva� f� �g?');
echo '
�� f�r� �akkir fr� okkur og heldur skr�ningu �inni sem gefandi sem �� vannst 
��r inn en meira f�r�u ekki. Ef �� vilt, getum vi� sett �ig � �kve�inn 
lista yfir hei�ursmenn svo �� getir monta� �ig vi� a�ra.
';
end_frame();
begin_frame('�g er ekki skr��ur notandi � Istorrent, m� �g gefa?');
echo '
J�, allir mega gefa. Stj�rnendur munu hins vegar ekki bj��a inn �skr��um 
a�ilum vegna �ess a� �eir hafa gefi�, sama hve h�ar fj�rh��ir �eir eru 
tilb�nir a� gefa � sta�inn (nema a� �a� s� heil millj�n e�a meira...�� 
hugsum vi� m�li� eftir a� fj�rh��in er komin yfir ;) ).
';
end_frame();
end_main_frame();
stdfoot();
?>
