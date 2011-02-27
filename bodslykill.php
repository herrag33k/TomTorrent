<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Boðslyklar");
begin_main_frame();
//loggedinorreturn();
begin_frame('Hvað er boðslykill?');
echo '
Boðslykill er kóði sem meðlimur innan Istorrent mun gefa þeim sem hann/hún vill bjóða. Lykillinn mun eingöngu 
virka fyrir netfangið sem bjóðandi slær inn.
';
end_frame();
begin_frame('Af hverju þarf ég boðslykil?');
echo '
Til að stuðla að ábyrgara samfélagi og minnka misnotkun var sett á það skipulag að fólk ber ábyrgð á þeim 
sem það býður. Ef samfélagið væri alveg opið myndi það valda miklum vandræðum þar sem það myndi bókstaflega 
flæða inn fólk sem kann ekki á samfélagið og það myndi enda með því að góðu meðlimirnir myndu bara fara 
burt. Með þessu kerfi er verið að tryggja að einhver sé til staðar sem að persónan þekkir og getur leitað 
til ef hún þarfnast hjálpar.
';
end_frame();
begin_frame('Boðslykillinn minn virkar ekki, hvað þá?');
echo '
1. Athugaðu hvort þú slóst hann inn rétt eða afritaðir hann allan yfir.<br />
2. Bjóðandi gæti hafa eytt út boðslyklinum.<br />
3. IP talan þín gæti verið í banni en í þeim tilvikum geturðu ekki nýskráð þig.<br />
4. Er notandinn sem bauð þér enn þá inn í kerfinu? Ef hann hefur verið eyddur út er hann ekki lengur gildur bjóðandi.<br />
5. Hafir þú farið í gegnum öll skrefin fyrir ofan og þetta virkar ekki enn þá, <a href="mailto:torrent@torrent.is">hafðu samband við notendaaðstoð</a>
';
end_frame();
begin_frame('Kunningi minn getur ekki boðið mér, hvað þá?');
echo '
Það væri hægt að hvetja hann til að bæta sig en skilyrðin til að geta boðið einhverjum inn er að hafa verið 
meðlimur í 2 vikur og vera með 0,85 í hlutföll. Uppfylli hann þessi skilyrði þarf hann þar að auki að hafa deilt 30 
gígabætum af gögnum til að fá sinn fyrsta boðslykil. Eftir það fær hann auka boðslykil fyrir hver 5 gígabæti sem hann 
deilir í viðbót.
';
end_frame();
begin_frame('Get ég ekki fengið undantekningu? Ég mun vera dugleg(ur)');
echo '
Ef við förum að veita undantekningar, þá verða þær ekki lengur undantekningar því að fleiri og fleiri munu 
nota sér þær og kerfið verður gagnslaust.
';
end_frame();
begin_frame('Ég þekki engan sem er með aðgang, hvernig get ég reddað boðslykli?');
echo '
Því miður er það eina sem má gera í þeirri aðstöðu er að biðja þá sem maður þekkir að redda þér lykli þegar 
þeir hafa fengið aðgang (og uppfylla skilyrðin til að gefa boðslykla).<br />
<br />
<b>Stjórnendur útvega ekki boðslykla og vinsamlegast ekki fara á opinbera staði til þess að biðja fólk um 
boðslykla.</b> Hvorugu er tekið vel af stjórnendum.
';
end_frame();
end_main_frame();
stdfoot();
?>
