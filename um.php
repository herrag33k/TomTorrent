<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Um Istorrent");
begin_main_frame();
//loggedinorreturn();
begin_frame('Tilgangur skjalsins');
echo '
Í þessu skjali er svarað spurningum er varða starfssemi Istorrent.
';
end_frame();
begin_frame('Hvað er Istorrent?');
echo '
Istorrent er nafn á félagskap nokkurra þúsund einstaklinga sem hafa það að markmiði að nýta sér BitTorrent tæknina til 
að dreifa skrám. Í henni felst að hver einstaklingur sem tekur þátt í dreifingarferlinu skuldbindur sig til að hjálpa 
til við dreifingu á því sem hann hefur náð í. Vefurinn var stofnaður 1. maí 2005 og telur nú um 5 þúsund skráða 
aðganga. Einnig hafa yfir 50 þúsund BitTorrent deilingar verið skráðar frá upphafi. Stórt spjallsvæði er á svæðinu og geta notendur þar spjallað saman um það sem tengist og tengist ekki Istorrent.
';
end_frame();
begin_frame('Rekstur');
echo '
Rekstri félagsins er haldið saman af Svavari Kjarrval (Kjarrval) í sjálfboðastarfi og sér hann um 
yfirumsjón, þróun og fjármál vefsins á meðan aðrir stjórnendur gefa vinnu sína við venjulega umsjón hans. 
Rekstrarfé er fengið frá frjálsum framlögum og er eingöngu notað við í tengslum við vefinn og er allt gefið 
upp til skatts á hverju ári.
';
end_frame();
begin_frame('Hvernig verð ég meðlimur?');
echo '
Eingöngu þeir sem boðnir eru sérstaklega af þeim sem eru nú þegar meðlimir samfélagsins og uppfylla ákveðin skilyrði 
er hleypt inn í samfélagið. Þetta er gert til að stuðla að persónulegra umhverfi og til að viðkomandi aðili hafa 
einhvern sem hann þekkir til að leita til ef hann þarfnast hjálpar. Þeir sem vilja bjóða einhverjum inn, láta 
viðkomandi fá boðslykil sem hann notar síðan til að nýskrá sig. Þessi aðili er því á ábyrgð þess sem hann bauð inn.
';
end_frame();
begin_frame('Hvað er BitTorrent?');
echo '
BitTorrent tæknin byggist á því að reiknuð er skráarsumma hverra X kílóbæta af þeim skrá sem á að dreifa og þær 
settar inn í, ásamt fleirum upplýsingum, í eina skrá sem ber endinguna .torrent og getur dreifingaraðilinn dreift 
þeirri skrá eins og hann vill. Þeir sem opna .torrent skrána með sérstökum forritum geta því sótt skráarbútana frá 
þeim sem bjó til .torrent skrána. Ólíkt mörgum öðrum forritum, þá getur sækjandinn byrjað að dreifa hverjum skráarbút áfram um 
leið og hann er kominn með hann.
';
end_frame();
end_main_frame();
stdfoot();
?>
