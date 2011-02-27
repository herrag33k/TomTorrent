<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Aðgangsheimildir");
begin_main_frame();
//loggedinorreturn();
begin_frame('Tilgangur skjalsins');
echo '
Í þessu skjali er svarað spurningum er varða aðgangsheimildir á vefnum almennt.
';
end_frame();
begin_frame('Hverjir mega nota vefinn?');
echo '
Allir mega nota vefinn sem fara eftir skilmálum vefsins. Tengil á þá má finna hér fyrir ofan í efnisyfirlitinu.
';
end_frame();
begin_frame('Hverjir mega ekki nota vefinn');
echo '
Þeir mega ekki nota vefinn sem ekki fara eftir fyrrgreindum skilmálum. Auk þess þeir sem stjórnendur hafa 
sérstaklega bannað að nota vefinn. Það má ekki nota vefinn með milligöngu opinberra aðila, þar 
á meðal gegnum Internettengingu þeirra, eða með notkun IP talna í þeirra umsjá. Aðilum sem tengjast 
höfundarréttarsamtökum er stranglega bannaður aðgangur að Istorrent. Að lokum mega þeir ekki nota vefinn þar sem 
umsjónarmaður tengingarinnar hefur sérstaklega beðið eftir aðgangsbanni.
';
end_frame();
begin_frame('Sótt um aðgangsbann');
echo '
Umsjónarfólk netkerfa getur sent fyrirspurn um að banna notkun ákveðins IP nets eða IP talna að efni vefsins og 
gildir bannið bæði um spjallborðið og þátttöku við dreifingu. Hins vegar verður einstaklingurinn að sanna að 
hann/hún sé í raun og veru með heimild (ef þess er krafist) til þess að biðja um þetta bann. Sé um að ræða 
opinbera aðila er það óþarft og þarf eingöngu að nefna IP netið og það verður bannað. Fyrirspurnirnar skal senda 
á netfangið <a href="mailto:torrent@torrent.is">torrent@torrent.is</a>.
';
end_frame();
begin_frame('Af hverju mega opinberir aðilar ekki nota vefinn?');
echo '
Opinberir aðilar mega auðvitað nota vefinn, en ekki í vinnunni. Það er ekki heilbrigt að skattpeningar okkar 
fari í að borga laun og tengingarkostnað opinberra stofnana svo að starfsfólkið þar geti notað vefinn. 
Viðkomandi aðilar eru beðnir um að stilla sér hóf og bíða þangað til vinnudegi er lokið.
';
end_frame();
begin_frame('Af hverju mega höfundaréttarsamtök ekki nota vefinn?');
echo '
Þar sem stefna Istorrent er að allir skuli eiga að hafa að vera komnir með réttinn til að dreifa og sækja það 
efni sem þeir taka þátt í að dreifa, þá finnst okkur það alger óþarfi fyrir fyrrgreind samtök að eyða peningum 
sem eiga að fara í greiðslur til höfunda í að fylgjast með vefnum. Höfundarnir eiga skilið meira fé en þeir eru 
að fá núna frá samtökunum.
';
end_frame();
begin_frame('Af hverju hafa stjórnendur heimild til þess að banna IP tölur?');
echo '
Eins og er almennt í heiminum í dag eru ekki allir sem haga sér í samræmi við þær leikreglur sem þjóðin setur og 
sömuleiðis geta notendur brotið reglur Istorrent. Það er samt ekki fyrr en við síendurtekin brot eða alvarleg 
brot sem farið er að banna notendur eftir IP tölum.
';
end_frame();
end_main_frame();
stdfoot();
?>
