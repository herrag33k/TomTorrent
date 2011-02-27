<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Vandamál?");
begin_main_frame();
//loggedinorreturn();
echo '<h1 align=center>Vandamál?</h1>';
begin_frame('Efnisyfirlit');
echo '
<a href="vandamal.php#hjalp">Hjálp, hvað get ég gert?</a><br />
<a href="vandamal.php#adur">Áður en er spurt</a><br />
<a href="vandamal.php#hvert1">Hvert skal leita? [1]</a><br />
<a href="vandamal.php#hvert2">Hvert skal leita? [2]</a><br />
<a href="vandamal.php#hverjir">Hverjir hjálpa þér fyrir hönd stjórnenda?</a><br />
<a href="vandamal.php#hvernig">Hvernig skal spyrja um hjálp?</a><br />
<a href="vandamal.php#ekkinefna">Hvað skal ekki nefna í hjálparbeiðnum</a><br />
<a href="vandamal.php#heflausn">Ég hef lausn á vandamáli sem er ekki hér</a><br />
';
end_frame();
echo '<a name="hjalp">';
begin_frame('Hjálp, hvað get ég gert?');
echo '
Það fyrsta sem þú getur gert er að skoða þetta skjal. Tilgangurinn með því er samt ekki að veita svörin 
við vandamálunum sjálfum, heldur hjálpa fólki að finna lausnina við þeim.
';
end_frame();
echo '<a name="adur">';
begin_frame('Áður en er spurt');
echo '
Áður en er spurt er fyrir bestu að staðfesta að þetta sé í raun og veru vandamál. Endurtakið það sem var 
gert og ef það sama gerist aftur, athugið hvort að allt var gert eins og leiðbeiningarnar segja. Leitið 
síðan hjálpar annarra. <b>Í flestum tilvikum, þótt ótrúlegt sé, eru 
flestar hjálparbeiðnir byggðar á vandamálum þar sem fólk er ekki að lesa 
hjálpartextann eða aðra texta sem eru í kringum formin.</b>
'; 
end_frame(); echo '<a name="hvert1">';
begin_frame('Hvert skal leita? [1]');
echo '
Leitaðu fyrst í opinberum texta hér á vefnum eins og <a href="faq.php">SOS</a> og sjáðu hvort það sé 
lausn þar. Áður en leitað er til spjallborðs eða stjórnenda skal fyrst flokka vandamálið. Ef það kemur 
vefnum sjálfum beint við eins og bilanir í vefnum sjálfum og þess konar, skal hafa samband við 
stjórnendur en önnur vandamál eins og aðferðir til að deila eða leiðbeiningar til að gera ýmsa hluti eru í höndum 
annarra. Þó skal hafa í huga að sum vandamál sýnast snúast um vefinn sjálfan þannig að það er fyrir 
bestu að fá álit hjá öðrum reyndum notanda fyrst.
';
end_frame();
echo '<a name="hvert2">';
begin_frame('Hvert skal leita? [2]');
echo '
Þegar þú veist hvort að spyrja eigi stjórnendur eða aðra notendur er komið annað ferli. Notendur eru 
hvattir til að spyrja stjórnendur eingöngu ef að aðrir notendur geta ekki leyst vandamálið. Stjórnendur 
hafa allan rétt á að neita að veita lausnir við vandamálum sem hægt eru að leysa með því að kíkja á 
spjallborðið eða aðrar auðveldar leiðir. Varðandi hverja skal spyrja skal helst fara eftirfarandi 
leiðir (eftir þessari röð):<br />
<br />
1. Vefurinn. Athuga hvort að lausnin er þegar til staðar í opinberum texta eða einfaldlega á útfyllingarforminu sjálfu.<br />
<br />
2. Spyrja bjóðanda. Þessi aðili ber ábyrgð á veru þinni hér og því er hann skyldugur til að hjálpa þér. 
Neiti hann að hjálpa þér ertu beðin(n) um að tilkynna notandann með tilkynningarkerfinu.<br />
<br />
3a. Spjallborðið. Notendur þar gætu hafa lent í þessum sömu vandamálum og leyst þau. 
<br />
3b. Irkið. Ef viðkomandi hefur forrit til að komast á irkið er hægt að 
leita þangað. Þó eru notendur hvattir til að prófa spjallborðið fyrst.<br />
<br />
4. Vefir framleiðenda BitTorrent forritanna. Vandamálið gæti verið afleiðing galla í forritinu eða 
einhvers annars í forritinu.<br />
<br />
5. Stjórnendur og hjálparar. Hægt er að spyrja stjórnendur hér en eingöngu ef vandamálið er mjög 
alvarlegt og hefur mikilvæga merkingu fyrir virkni vefsins. Hjálparar sjá um að leysa önnur vandamál 
sem ekki hefur verið hægt að leysa í gegnum hin skrefin.<br />
<br />
Lausnir við vandamálum í hverju skrefi koma ekki fram á sekúndunni sem er spurt. Sýna skal þolimæði við 
bið á lausnum þar sem fólkið sem veit lausnina (ef einhverjir) er ekki endilega við allan sólarhringinn 
til að svara þér.
';
end_frame();
echo '<a name="hverjir">';
begin_frame('Hverjir hjálpa þér fyrir hönd stjórnenda?');
echo '
Þar sem stjórnendur eru oft nokkuð uppteknir hafa þeir útnefnt hjálpara til að sjá um notendaaðstoð. 
Þeir vakta oft spjallborðin eða irkrásina og reyna oft að svara eftir bestu getu. Lista yfir þá má fá 
með því að fara á tengilinn <a href="staff.php">stjórnendur</a> sem má finna á efnisyfirlitinu hér að ofan. Mikilvægar 
ákvarðanir eins og stefnur eða refsingar vegna reglubrota eru samt teknar af stjórnendum.
';
end_frame();
echo '<a name="hvernig">';
begin_frame('Hvernig skal spyrja um hjálp?');
echo '
Þegar spurt er um hjálp á að sýna að þú hafir áhuga á að leysa vandamálið og munir lausnina til 
framtíðarnota. Ef að viðkomandi kemur síðan aftur og spyr "hver var lausnin aftur?" eða eitthvað álíka, 
er ólíklegt að fólk geri það né hjálpar þessari manneskju aftur við önnur vandamál.<br />
<br />
Taka skal fram, þegar beðið er um hjálp, hvað var verið að gera þegar það kom upp, hvaða BitTorrent 
forrit var er verið að nota og aðrar upplýsingar sem gætu skipt máli. Eftir atvikum skal taka fram 
hlutföll og stöðu viðkomandi á Istorrent vefnum.<br />
<br />
Það er mikil ókurteisi að segja einfaldlega "hjálp, X virkar ekki, hvernig get ég leyst það?" eða 
eitthvað álíka og láta síðan fólk spyrja þig stöðugt í leit að lausninni. Það ert <b>þú</b> sem þarft á 
lausninni að halda, ekki aðrir. Verið því eins námkvæm og getið en takmarkið þó upplýsingarnar við 
þær sem gætu skipt máli. Verið einnig tilbúin til að fara eftir þeim leiðbeiningum sem eru gefnar en 
farið þó gætilega þar sem margir út í heimi finnst gaman að eyðileggja möguleika þína að lausn eða 
annað sem er í tölvunni þinni.<br />
<br />
Síðast en ekki síst, <b>ekki</b> heimta að einstaklingar hafi samband við þig í gegnum einkaskilaboð 
eða annan óopinberan vettvang eftir að hafa sent fyrirspurn á opinberum vettvangi þar sem það er mjög 
eigingjarnt þar sem að notendur sem vilja fylgjast með umræðunni og hafa hag af henni er svipt þeim 
möguleika að hafa lausnina við hendi.
';
end_frame();
echo '<a name="ekkinefna">';
begin_frame('Hvað skal ekki nefna í hjálparbeiðnum');
echo '
Eftirfarandi upplýsingar skal <b>ekki taka fram</b> í hjálparbeiðnum:<br />
<br />
1. <b>Lykilorð</b>. Einnig skal forðast að gefa upp notandanafnið til annarra en stjórnenda vefsins en 
þær upplýsingar ættu eingöngu að vera sendar í beiðnum gegnum tölvupóst eða gegnum vefinn sjálfan. 
Lykilorð eru gagnslaus fyrir stjórnendur og því þarf ekki að gefa þau upp.<br />
<br />
2. <b>Auðkennislykil (e. passkey)</b>. Þá á ekki að gefa upp þar sem hægt er að nota þá til að nýta sér 
hlutföllin þín til að ná í hluti.<br />
<br />
3. <b>Boðslykilorð</b>. Biðji aðrir notendur um boðslykilorð skal neita þeim um það nema þeir séu 
stjórnendur. Þau á samt ekki að nefna á opinberum vettvangi og er það hvorki ætlun né vilji stjórnenda að þau séu 
nefnd þar.<br />
<br />
4. <b>Önnur lykilorð eða persónuupplýsingar</b>. Þessar upplýsingar hafa líklegast ekkert að gera með 
vandamálið sjálft og því er alger óþarfi að nefna þær.
';
end_frame();
echo '<a name="heflausn">';
begin_frame('Ég hef lausn á vandamáli sem er ekki hér');
echo '
Frábært. Við gerum það að okkar markmiði að reyna að halda úti lausnum á vandamálum svo að fólk þarf 
ekki að leita lengra en hingað á vefinn til að leysa þau. Tilkynna má lausnirnar á tölvupóstfangið <a 
href="mailto:torrent@torrent.is">torrent@torrent.is</a> ásamt lýsingu á 
vandamálinu.
';
end_frame();
end_main_frame();
stdfoot();
?>
