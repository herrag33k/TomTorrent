<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Af hverju hlutföll skipta máli");
begin_main_frame();
loggedinorreturn();

begin_frame('Hvað eru hlutföll?');
echo '
Hlutföll er mælikvarði á hve mikið notandinn deilir og niðurhalar. Þau eru reiknuð með því að taka deilimagnið og deila því með 
niðurhalsmagninu þar sem 1.0 merkir engan mun, minna en 1.0 merkir að niðurhalið er meira og hærra en 1.0 þýðir að deilimagnið er 
hærra.
';
end_frame();
begin_frame('Af hverju eruð þið að spá í hlutföll?');
echo '
Til að samfélagið virki þurfa sem flestir að vera virkir í því að leggja eitthvað af mörkum. Ef það er ekki gert og flestir gera 
ekkert annað en að vera byrði, mun það deyja. Hlutföllin eru einmitt til þess að sjá hve virkur hver notandi er.

Við verðum samt að gefa nýliðum tækifæri á að aðlagast og þess vegna eru þeir ekki bannaðir fyrir hlutföll fyrr 
en þeir hafa verið meðlimir í að minnsta kosti 2 vikur. Til að banna ekki notendur útaf engu og auka marktæknina, er skilyrðið líka 
að notandinn hafi náð í 2 gígabæti af gögnum. Einnig skiljum við að notendur geti haft slæm tímabil þrátt fyrir að vera komnir vel inn í samfélagið en 
0.2 og lægra eru mörkin og eru allir notendur innan þeirra marka bannaðir um leið og umsjónarfólk vefsins sér hlutföllin.
';
end_frame();
begin_frame('Hvað um notendur sem halda sér rétt fyrir ofan 0.2 til að verða ekki bannaðir?');
echo '
Stjórnendur eru alltaf að leita leiða til þess að hvetja notendur til þess að halda sér sem hæst með því til dæmis láta þá vita af 
óframlegðinni og takmarka möguleika notenda í kerfinu eftir því sem þeir hafa lægri hlutföll. Notendur með 0.75 eða hærra þurfa ekki 
að bíða eftir nýjum deiliskrám; þeir sem hafa 0.5 til 0.75 bíða í 12 tíma og þeir sem hafa minna en 0.5 fá 24 klst í bið.<br />
<br />
Alltaf er verið að bæta við nýjum möguleikum í kerfið og eru lúxus-möguleikarnir eingöngu notanlegir af notendum sem uppfylla 
ákveðin skilyrði, meðal annars um ákveðin hlutföll.
';
end_frame();
begin_frame('Hvað um notendur sem hafa góð hlutföll en brjóta reglurnar?');
echo '
Allir notendur sem fá viðvörun munu lenda í því að fá bið eftir nýjum deiliskrám eins og þeir væru með minna en 0.5 í hlutföll á 
meðan viðvörunartímabilið varir.
';
end_frame();
begin_frame('Hjálp, ég er með slæm hlutföll, hvernig bæti ég mig?');
echo '
Til eru 2 leiðir:<br />
1. Finna einhvern hlut sem þú átt og heldur að aðrir hér í samfélaginu hafi áhuga á að ná í. Til eru leiðbeiningar á spjallborðinu um 
hvernig á að búa til deiliskrár (torrent) með ákveðnum forritum.<br />
<br />
2. Þegar þú hefur náð í eitthvað, haltu glugganum opnum á meðan aðrir halda áfram að ná í skránna. Hlutfallið þitt mun síðan hækka 
eftir því sem þú ert lengur og eftir því hve margir eiga eftir að ná í þetta eftir að þú klárar. Athugaðu samt sem áður að 
niðurhalið á deiliskránum sem þú ætlar að deila með þessari aðferð mun vinna gegn þér í byrjun en vinnst upp ef þú nærð yfir 1.0 í 
hlutföllum fyrir þessa skrá. Það er samt gott að nota þessa aðferð ef þú þarft endilega að ná í ákveðið efni.
';
end_frame();
end_main_frame();
stdfoot();
?>
