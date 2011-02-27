<?
require_once("include/bittorrent.php");
dbconn(true);
stdhead("Styrkir");
begin_main_frame();
//loggedinorreturn();
begin_frame('Styrkja Istorrent');
echo '
Istorrent hafið vaxið í vinsældum undanfarið og því hefur rekstrarkostnaðurinn aukist. Til að berjast gegn þessu vandamáli, höfum við byrjað á söfnun þar sem fólki er frjálst að gefa í hana þær upphæðir sem það vill láta frá sér.<br />
<br />
Við viljum benda á það að það er alls engin skylda að gefa í þessa söfnun 
og notendum verður ekki refsað á neinn hátt fyrir að ákveða að gefa ekki í 
hana.<br />
<br />
Núverandi markmið:<br />
Engin<br />
*BÚIÐ* Sumar í kóðun verkefnið.<br />
*BÚIÐ* 1 GB 400 MHz DDR vinnsluminni fyrir netþjóninn.
';
end_frame();
begin_frame('Í hvað fer féð?');
echo '
Stjórn Istorrent mun ákveða það í hvert skipti sem þeir sjá að hinn 
almenni notandi muni hagnast á því. Þegar svona ákvarðanir eru teknar eru 
notendur vefsins látnir vita. Þeir sem eru skráðir gefendur munu ekki 
hafa meira vald eða atkvæðisrétt til að ákveða í hvað féð fer en hinn 
almenni notandi en þeir hafa báðir rétt á að koma með hugmyndir um það.
';
end_frame();
begin_frame('Hvað fæ ég í staðinn?');
echo '
Vinsamlegast athugið! <u>Styrkir eru ekki endurgreiddir</u> eftir að þeir 
hafa verið færðir inn. Meðferð Istorrent á styrkjum er samkvæmt styrkjareglum.<br />
<br />
Einnig ber að geta að <b>gefendastöðunar eru ekki söluvara</b> og 
eru þeir því ekki endurgreiddir þótt viðkomandi notandi var bannaður vegna 
reglubrota eða einhverra hluta vegna getur ekki notað aðganginn.<br />
<br />
Allir þeir sem gefa 500 kr. eða meira verða skráðir sem gefendur út 
líftíma vefsins en í því er innifalið:<br />
- Að minnsta kosti sömu réttindi og þeir sem gegna stöðunni "Virkur notandi".
- Alls engin bið eftir deiliskrám, óháð hlutföllum.<br />
- Hækkun á hólfafjölda upp í 12.<br />
- Getur sett sérstakan titil á þig í gegnum prófíl.<br />
- Verður ekki eydd(ur) út þrátt fyrir að vera óvirkur eins og venjan er með venjulega aðganga.<br />
<br />
<b>Hvað fæ ég <u>ekki</u> með því einu að styrkja?:</b><br />
- Ónæmi gagnvart reglunni um lágmarkshlutfall (0.20 eða lægra).<br />
- Forréttindi eða forgang yfir aðra notendur Istorrent.<br />
- Einkakennslu á Istorrent eða neitt annað.<br />
- Undantekningar frá reglum eða stefnum Istorrent.<br />
- Kóðann á bakvið Istorrent.<br />
- Lægri eða enga refsingu fyrir brot á reglum.<br />
- Inngöngu í Istorrent samfélagið.<br />
';
end_frame();
begin_frame('Hvernig er hægt að styrkja?');
echo '
Með því að leggja inn einhverja (jákvæða) fjárhæð á bankareikning 
0327-13-003120 (kennitala 071183-2119).<br />
<br />
Ef þú hefur gefið 500 kr. eða meira og vilt merkismannastöðuna 
(engin skylda), sendirðu bara póst á <a href="mailto:torrent@torrent.is">torrent@torrent.is</a> 
og greinir frá notandanafni, fullu nafni (alvöru nafninu þínu) og 
kennitölu greiðanda ásamt upphæð.<br />
<br />
Að gefnu tilefni ber að nefna að styrkir eru að jafnaði afgreiddir á föstudagskvöldum eða á laugardegi.<br />
Þeir sem millifæra eftir kl. 21:00 á föstudegi munu ekki vera afgreiddir fyrr en í fyrsta lagi næsta bankadag 
(takmarkanir v/bankans, ekki Istorrent).<br />
Fyrir fríhelgar er því fyrir bestu að millifæra tímanlega til að tryggja að geta notið hennar með réttindum 
skráðra gefenda. Ef fríhelgi byrjar ekki á föstudegi eru styrkir afgreiddir nokkrum klst. áður en hún hefst ef 
kostur er.
';
end_frame();
begin_frame('Mig langar að gefa miklu meira eða oftar, hvað fæ ég?');
echo '
Þú færð þakkir frá okkur og heldur skráningu þinni sem gefandi sem þú vannst 
þér inn en meira færðu ekki. Ef þú vilt, getum við sett þig á ákveðinn 
lista yfir heiðursmenn svo þú getir montað þig við aðra.
';
end_frame();
begin_frame('Ég er ekki skráður notandi á Istorrent, má ég gefa?');
echo '
Já, allir mega gefa. Stjórnendur munu hins vegar ekki bjóða inn óskráðum 
aðilum vegna þess að þeir hafa gefið, sama hve háar fjárhæðir þeir eru 
tilbúnir að gefa í staðinn (nema að það sé heil milljón eða meira...þá 
hugsum við málið eftir að fjárhæðin er komin yfir ;) ).
';
end_frame();
end_main_frame();
stdfoot();
?>
