<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Fyrsta skiptið");
begin_main_frame();
//loggedinorreturn();
begin_frame('Fyrsta skiptið');
echo '
Um leið og við óskum þér til hamingju með að vera boðið á Istorrent viljum við veita þér mikilvægar 
upplýsingar sem gagnast þér í veru þinni á Istorrent og fækka vandræðatilfellum.
';
end_frame();
begin_frame('Hvað er Istorrent?');
echo '
Istorrent er samfélag þar sem notendur geta deilt og sótt skrár með BitTorrent tækninni. Helsti 
kosturinn er að skráarumferðin fer fram beint á milli notenda en vefurinn sér eingöngu um að miðla 
helstu upplýsingum á milli notenda.
';
end_frame();
begin_frame('Er Istorrent fyrir þig?');
echo '
Hreinskilnin er ofarlega á blaði hjá stjórnendum þessa vefs og viljum við koma því á framfæri að 
Istorrent er ekki við hæfi allra. Tækniþekking og/eða sjálfsbjargarviðleitni er eitt af því sem 
vefurinn reynir nokkuð á í upphafi en verður síðan lítið mál þegar notandinn er vanari því umhverfi sem hann 
byggir á.<br />
<br />
Til að taka þátt í torrent samfélaginu þarf að hafa eftirfarandi:<br />
* Tölvu<br />
* Tengingu við Internetið<br />
* Hugbúnað sem getur unnið með BitTorrent staðalinn.<br />
<br />
Mjög gott er, ef hægt er, að hafa kveikt á tölvunni yfir sem lengst tímabil með BitTorrent hugbúnaðinn í gangi.
';
end_frame();
begin_frame('Hvað er BitTorrent?');
echo '
BitTorrent (oft kallað "torrent") er samskiptastaðall sem heimilar dreifingu á skrám gegnum Internetið. 
Staðallinn heimilar sækjanda að hjálpa til við dreifingu þeirra skráa sem er deilt. Um leið og hver 
sækjandi er kominn með bút af skránni, dreifir hann téðum skráarbút áfram til annarra sækjenda sem 
vantar hann.<br />
<br />
Deilandi byrjar á því að búa til svonefnda ".torrent" skrá sem geymir helstu upplýsingar 
um skrána eða skrárnar sem á að fara að deila ásamt slóðina að deilistaðnum. Hann sendir síðan skrána 
til deilistaðarins sem sér síðan um að halda úti upplýsingar um þátttakendur. Aðilar sem vilja taka 
þátt í dreifingunni ná í ".torrent" skrána og sækja síðan upplýsingar til deilistaðarins, fá 
upplýsingar um aðra þátttakendur og tengjast þeim síðan beint. Skráarumferðin sjálf fer því ekki í 
gegnum deilistaðinn, heldur beint á milli þátttakenda.
';
end_frame();
begin_frame('Hverjir nota BitTorrent?');
echo '
Mörg fyrirtæki eru að nota þessa tækni nú þegar og hafa séð notagildið í henni til að minnka umferð á 
netþjóna sína og þarafleiðandi spara kostnað við að halda úti stærri tengingu við Internetið en þeir 
myndu gera við venjulegar aðstæður. Einnig hafa margir dreifendur gjaldfrjálsra stýrikerfa tekið upp 
BitTorrent aðferðina sem valkost við venjulegu aðferðina og má þá nefna <a 
href="http://www.is.freebsd.org">FreeBSD</a> og <a href="http://www.redhat.com">Red Hat</a>. 
Leikjaframleiðandinn <a href="http://www.blizzard.com">Blizzard</a> hefur tekið upp dreifingaraðferðina 
sem eina valkostinn til að dreifa uppfærslum fyrir leikinn <a href="http://wow-europe.com">World of Warcraft</a>.
';
end_frame();
begin_frame('Byrjunarörðugleikar?');
echo '
Istorrent býður upp á mikla hjálp í spjallflokknum "Leiðbeiningar" á spjallborðinu og eru notendur hvattir til 
að leita á spjallborðinu áður en stjórnendur eru spurðir um hjálp. Þeir vinna mjög göfult starf sem 
sjálfboðaliðar og það er betra að ónáða þá ekki nema það sé alger nauðsyn. Einnig er helstu spurningum svarað 
í "SOS" (Spurt og svarað) og er mikilvægt að allir venji sig á að kíkja þangað fyrst.
';
end_frame();
begin_frame('Hlutföll skipta máli');
echo '
Með meiri notkun á vefnum kemstu að hugtakinu hlutföll en það er notað sem mælitæki á því magni af 
bætum sem þú deilir til annarra deilt með því magni sem þú nærð í frá öðrum. Hlutfallið 1.0 merkir að 
þú deilir jafn miklu og þú sækir frá öðrum og er viðunandi að vera í þeirri aðstöðu eða hafa hærra en 
1.0 sem þýðir að þú deilir meiru en þú sækir. Á meðan deilimagnið og niðurhalsmagnið er lítið, geta 
hlutföllin tekið snöggum breytingum og er því ekki gott að vera að sækja mikið í upphafi.<br />
<br />
Þegar svonefndur reynslutími er liðinn, 2 vikur, byrja hlutföllin að hafa áhrif á veru notenda. Séu 
hlutföllin of lág byrjar notandinn að finna fyrir skertum réttindum á vefnum en há hlutföll geta bætt 
stöðu þeirra verulega.<br />
<br />
Nánar um hlutföll er hægt að finna á slóðinni <a href="http://torrent.is/hlutfoll.php">torrent.is/hlutfoll.php</a>.
';
end_frame();
begin_frame('Hverju skal deila og hve miklu?');
echo '
Margir notendur gera þau leiðu mistök að deila bara því sem þeir finna á harðadisknum sínum en 
svoleiðis virkar ekki Istorrent. Sumir halda að deilimagnið þeirra hækki sjálfkrafa bara með því að 
búa til ".torrent" skrá og senda en í raun og veru skiptir eingöngu máli raunmagnið sem sendist á milli 
notenda.<br />
<br />
Önnur mistök sem notendur eiga til með að gera er að bjóða fram efni sem er þegar í dreifingu af öðrum 
notanda en hægt er að leysa úr vandanum með því að framkvæma leit að skránni og sjá hvort að einhver 
niðurstaða kemur fram. Eingöngu má hefja deilingu á sama efninu ef þetta er talsvert betri útgáfa eða 
lagfæring á útgáfu sem er þegar komin.<br />
<br />
Þegar skrá er boðin fram skal passa að nægur tími sé til að deila skránni alveg áður en notandi fer að 
slökkva á tölvunni því ekki er litið vel á það að notendur séu að gera hlé á skráardeilingu án þess að 
minnsta kosti annar notandi sé kominn með allt efnið. Þeir sem þurfa t.d. að hafa slökkt á tölvunni 
yfir nótt ættu helst að hefja deilingu á morgnana og passa að þeir geti klárað að senda allt efnið á að 
minnsta kosti einn annan notanda áður en slökkt er á tölvunni næstu nótt. Ef skráin er nokkuð stór eða 
deiling gengur hægar en áætlað var er ágætt að nefna skort á deilingu í lýsingu og á hvaða tímabili má 
búast við stöðvun á deilingu.<br />
<br />
Þurfi notandi að spila netleiki eða gera annað sem er bandvíddarfrekt er ágætt að hægja frekar á 
bandvíddinni sem BitTorrent forritið tekur frekar en að stöðva deilingu alveg.
';
end_frame();
begin_frame('Að bjóða fram efni er ekki nauðsynlegt');
echo '
Notendur sem ekki hafa þekkingu af því að búa til ".torrent" skrár þurfa ekki að hafa áhyggjur af 
því að þurfa að búa þær til. Hægt er að taka þátt í vefnum með góð hlutföll án þess að bjóða fram 
eina einustu ".torrent" skrá.<br />
<br />
Sumir notendur stunda það að ná í bráðlega vinsælar skrár stuttu eftir 
að annar notandi hefur boðið þær fram og fá því aukið deilimagn fyrir það eingöngu að dreifa skránni 
áfram til annarra notenda. Þeir notendur rannsaka eða finna út hvaða efni er/verður vinsælt í 
dreifingu og sækja það af vefnum áður en margir eru búnir að ná í það og fá því eitthvað fyrir það 
niðurhalsmagn sem þeir "fjárfestu" í skránum. Sumir ná í eins eintak af skránum af opnum BitTorrent 
vefjum eða af öðrum stöðum og taka þátt í deilingu án þess að ná í skrána. Sú "fjárfesting" er 
áhættulaus þar sem hún kostar ekkert Istorrent-niðurhalsmagn fyrir notandann en getur veitt honum 
mikið deilimagn.<br />
<br />
Þessar aðferðir krefjast ekki eins mikillar fyrirhafnar og að bjóða fram efni og kostar bara smá 
þjálfun í upphafi. Ekki er samt ráðlegt að nota hana við efni sem notandinn "hefur ekki efni á" 
þegar reynslutímanum er lokið.
';
end_frame();
begin_frame('Vandræði?');
echo '
Notendur sem eru í einhverjum vandræðum eru beðnir um að fara eftir skrefunum sem eru á hlekknum 
"<a href="http://torrent.is/vandamal.php">Vandamál?</a>" í efnisyfirlitinu.
';
end_frame();
end_main_frame();
stdfoot();
?>
