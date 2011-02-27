<?

ob_start("ob_gzhandler");

require "include/bittorrent.php";

dbconn(true);

stdhead("Reglur - flóknari");

begin_main_frame();
?>
Skýringar:<br />
Hver regla er með eina svartútfyllta kúlu fyrir framan.<br />
Undirreglur eru merktar með "---" fyrir framan.<br />
<br />


<? 
begin_frame("Almennar reglur");

?>
<ul>
<li>Skilmálar skulu alltaf vera æðri reglunum ef þeim greinir á.</li><br />
--- Fyrir utan skilmálana, skulu reglurnar alltaf hafa síðasta orðið.
<li>Allir notendur vefsins skulu fara eftir þeim skilmálum og reglum sem eru í gildi á vefnum.</li>
<li>Persónuverndarstefna Istorrent skal alltaf vera opinber og gefin til kynna í nýskráningarferli.</li>
<li>Gefa má út einfaldari útgáfa af þessum reglum en þessar reglur skulu vera þær sem gilda ef upp koma ágreiningsmál.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Óvirknisreglur'); ?>
<ul>
<li>Notendur sem eru óvirkir í 12 vikur samfleytt skal vera eytt.</li><br />
--- Óvirkni er þegar notandi skráir sig alls ekkert inn á vefinn.<br />
<li>Stjórnendur mega veita undantekningu á þessari reglu ef góð ástæða er gefin upp og að tímalengd óvirkninnar sem hlýst af þeim atburði er lengri, eða er spáð vera hættulega nálægt þeim mörkum er notandi verði gerður óvirkur.</li><br />
--- Lendi notandi í því að aðgangnum hans hafi verið eytt þrátt fyrir að hafa fengið undantekningu frá þessum reglum, má afturkalla eyðinguna eftir að fullnægjandi beiðni hefur borist frá viðkomandi notanda í tölvupósti.<br />
--- Undantekningin skal eingöngu vera í gildi fyrir eitt skipti í einu og beðið hafi verið um hana áður en óvirknistímabil notandans hófst.
</ul>
<? end_frame(); ?>
<? begin_frame('Hlutfallsreglur'); ?>
<ul>
<li>Hlutfall hvers notanda skal reiknast með því að deila deilimagni notandans með niðurhalsmagni hans.</li>
<li>Lágmarkshlutfallið skal skilgreint vera 0,20 eða lægra.</li><br />
--- Refsiaðgerðir vegna lágmarkshlutfalls skulu eingöngu vera framkvæmdar sé niðurhalsmagn notanda meira en 2 gígabæti og hafi hann verið meðlimur í 2 heilar vikur eða lengur.<br />
<li>Aðgangur að nýjustu torrent færslum skal stjórnast af hlutfalli viðkomandi notanda og er ákveðinn biðtími eftir að mega taka þátt í dreifingu nýrra torrent innsendinga. Biðin skal miðast við þann tíma frá því að torrent færslan var send inn.</li><br />
--- Notandi með 0,75 og hærra í hlutföll er ekki háður bið.<br />
--- Notandi frá og með 0,50 og minna en 0,75 í hlutföll skal þurfa að bíða 12 klukkustundir.<br />
--- Notandi með minna en 0,50 í hlutföll skal þurfa að bíða í 24 klukkustundir.<br />
<li>Sé notandi á lágmarkshlufallinu, skal hann vera gerður óvirkur.</li><br />
--- Ákvæðið skal ekki framkvæmt ef notandi er í óútrunnum vikufresti.
</ul>
<? end_frame(); ?>
<? begin_frame('Vikufrestur'); ?>
<ul>
<li>Notendur skulu geta beðið um vikufrest til að bæta úr lágmarkshlutfallinu eins og það er skilgreint í hlutfallareglum, hafi þeir verið gerðir óvirkir vegna þeirra.</li><br />
--- Hægt er að fá þennan frest þrátt fyrir að hafa verið eytt samkvæmt hlutfallsreglum og reglum um óvirkni.<br />
--- Lendi notandi í því, eftir að vikufresturinn er liðinn, að vera í lágmarkshlutfallinu, skal hann gerður óvirkur til frambúðar.<br />
--- Sé hálft ár liðið frá vikufrestinum og notanda tókst að vinna sig úr lágmarkshlutfallinu, má senda notanda viðvörun og tilkynna að honum skuli eytt ef hann bætir sig ekki innan viku. Þessa undantekningu skal eingöngu beita einu sinni fyrir hvern aðgang.<br /> </ul>
<? end_frame(); ?>
<? begin_frame('Hólfareglur'); ?>
<ul>
<li>Hólf skulu vera reiknuð sem fjöldi torrent færsla sem notandi tekur þátt í.</li>
<li>Sé notandi að fullnýta hólfin sem honum hefur verið úthlutað skal hann ekki geta hafið aðrar aðgerðir sem krefjast auka hólfa.</li>
<li>Hlutföll notanda og staða hans skulu ráða hve mörg hólf hann hefur til sinna umráða.</li><br />
--- Notendur í stöðunni "Mjög virkur notandi" eða hærra settir skulu hafa 12 hólf.<br />
--- Notendur í stöðunni "Virkur notandi" skulu hafa 8 hólf.<br />
--- Notendur í stöðunni "Notandi" skulu hafa 4 hólf.<br />
--- Notendur í stöðunni "Byrjandi" skulu hafa 2 hólf.
</ul>
<? end_frame(); ?>
<? begin_frame('Viðvörunarreglur'); ?>
<ul>
<li>Notendur sem ekki eru bannaðir af vefnum skulu fá viðvörun í ákveðinn langan tíma eftir broti þeirra.</li>
<li>Sé notandi með gilda viðvörun og tilefni er til annarar viðvörunar, er stjórnanda heimilt að banna notanda eða endurgefa viðvörunina með lengri tíma en þær hefðu gert samanlagt.</li><br />
--- Bannið skal eingöngu notað ef tilefni einhverrar viðvöruninnar er alvarlegt.<br />
--- Gæta skal þess að gefa ekki viðvörun fyrir nákvæmlega sama tilefni.<br />
--- Lengri tímasetningu skal eingöngu gefa ef síðari brot eru framkvæmd eftir að notanda hefur verið gert grein fyrir brotinu.<br />
<li>Notendur með viðvörun skulu þurfa að bíða í 24 klukkustundir frá innsendingartíma nýrra deilinga.</li>
<li>Notendur með viðvörun skulu hafa jafnmörg hólf til sinna umráða og sá hópur notenda sem hefur fæst hólf.</li>
<li>Notendur með viðvörun geta ekki búið til boðslykla.</li>
<li>Notendur með viðvörun skulu ekki geta sent inn eftirspurnir.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Spjallreglur'); ?>
<ul>
<li>Þessar spjallreglur skulu gilda fyrir umræðusvæði fyrir innsend torrent, spjallborðið, spjallrás Istorrent á IRC og aðra staði í boði Istorrent þar sem hægt er að tjá sig nema reglan sjálf nefni sérstaklega hvar hún á að gilda.</li>
<li>Notendur skulu hafa eins mikið tjáningarfrelsi og skilmálar vefsins leyfa.</li>
<li>Ekki senda inn hlekki á "warez" eða "crack" vefi.</li>
<li>Ekki biðja um eða veita forrit eða leiðbeiningar til þess að fara framhjá afritunarvörnum eða staðfestingarkóðum fyrir hugbúnað sem hefur svoleiðis vörn.</li>
<li>Ekki skal senda inn spjallpóst á "dautt" umræðuefni á spjallborðinu í þeim eina tilgangi að veita athygli að því á ný nema greinilegt sé að þörf var á því innleggi.</li>
<li>Hver og einn notandi skal gæta að því að nota ekki of stórar myndir í undirskrift sinni eða senda svoleiðis myndir sem hluta af spjallpósti.</li>
<li>Heimilt er að refsa notendum sem af stóru gáleysi eða vísvitandi senda inn umræðuefni í vitlausan flokk á spjallborðinu.</li>
<li>"Now playing" og álíkir textar eru bannaðir alls staðar á umræðusvæðum Istorrent nema skrifaður er lengri texti um það sem kom fram í fyrrgreindum texta.</li> </ul>
<li>Textar sem eru ekki skrifaðir handvirkt inn um tölvusamsetningu eru ekki leyfðir á spjallrás Istorrent á IRC.</li>
<li>Ekki skal biðja um boðslykla inn á Istorrent á spjallrás Istorrent á IRC</li>
<? end_frame(); ?>
<? begin_frame('Myndareglur'); ?>
<ul>
<li>Myndareglur skulu gilda á öllu vefsvæði Istorrent.</li>
<li>Ekki má senda inn myndir af atburðum þar sem innsending myndanna brýtur í bága við landslög.</li>
<li>Leyft er að senda inn myndir í þekktum sniðum sem heyra undir viðurkenndan vefstaðal. Þar má nefna gif, jpg og png.</li>
<li>Ef notuð er mynd sem smámynd (avatar) sem gæti móðgað eða sært blygðunarkennd annarra notenda, skal merkja hana sem slíka í prófíl.</li>
<li>Smámyndir skulu ekki vera meira en 200 pixlar á hæð og ekki vera stærri en 150 kílóbæti að stærð.</li>
<li>Ekki skal nota móðgandi eða særandi myndir í undirskriftum, senda inn slíkar myndir sem hluta af spjallpósti eða nota í torrent lýsingu.</li><br />
--- Þó má senda inn svoleiðis mynd sem hluta af spjallpósti eða sem hluta ad torrent lýsingu ef skoðandanum er gefin góð hugmynd um hvernig myndum hann má búast við að sjá áður en hann opnar viðeigandi síðu.
</ul>
<? end_frame(); ?>
<? begin_frame('Torrent innsendingarreglur'); ?>
<ul>
<li>Reglur í þessum flokki skulu gilda um allar innsendingar á torrent skrám og viðeigandi upplýsingum.</li>
<li>Innsendari ber ábyrgð á því að skránum sem deilt er með tilstilli innsendra torrent skráa uppfylli allar þær reglur sem settar eru af Istorrent.</li>
<li>Greinargóð lýsing skal fylgja hverri torrent færslu og skal skoðandi hafa góða hugmynd um hvað hann gæti náð í.</li>
<li>Óheimilt er að dreifa vírusum eða öðrum tölvuforritum sem geta skemmt gögn eða valdið öðrum notendum óþægindum.</li>
<li>Óheimilt er að dreifa forritum sem breyta virkni annarra forrita þar sem framleiðandi hefur fordæmt slíka notkun forrita.</li>
<li>Alltaf skal greina satt og rétt frá í upplýsingum innsendra torrenta.</li>
<li>Titill efnisins skal vera stuttur og hnitmiðaður og skal ekki innihalda óþarfa upplýsingar og má þar nefna hvort efnið hafi verið umbeðið eða ekki eða persónulegt álit á innihaldinu.</li>
<li>Ekki skal innihalda í lýsingu lykla eða leiðbeiningar til að komast framhjá afritunarvörnum eða staðfestingarkóðum.</li><br />
--- Þó skal ekki breyta "NFO" skjali ef það fylgir með.<br />
<li>Innsendandi skal sjá til þess að torrent færslan sé virk í 24 klukkustundir eftir að hún er send inn.</li><br />
--- Innsendandi má eingöngu hætta að deila innan 24 klukkustunda séu nokkrir aðrir komnir með efnið að fullu og eru að taka þátt í deilingu.<br />
<li>Innsendandi má ekki slökkva á deilingu sé hann sá eini sem er með allt efnið og virkir aðilar séu skráðir sækjendur þess.</li>
<li>Ef sent er inn safn, t.d. heilt þáttatímabil, skal senda inn eitt tímabil í einni innsendingu.</li>
<li>Ekki innihalda útgáfudag í torrent nafni.</li><br />
--- Þó má innihalda dagsetningu ef atburðirinn átti sér stað í beinni útsendingu.
</ul>
<? end_frame(); ?>
<? begin_frame('Merkismannastöðureglur'); ?>
<ul>
<li>Eingöngu þeir sem búið er að staðfesta að hafa gefið í Istorrent sjóðinn mega hafa þessa stöðu.</li><br />
--- Stjórnendur mega gefa einhverjum stöðuna í heiðursskyni fyrir störf sín á Istorrent.<br />
<li>Þeir sem gegna þessari stöðu skulu njóta að minnsta kosti sömu kjara og þeir sem gegna stöðunni "Virkur notandi".</li><br />
--- Þeir skulu samt ekki njóta sjálfkrafa þeirra kjara sem mjög virkir notendur njóta vegna deilimagns síns eða hlutfalls nema þess sé sérstaklega getið í þessum reglum.<br />
<li>Þeir sem gegna þessari stöðu skulu ekki þurfa að bíða eftir að geta byrjað á torrent færslu eins og getið er í hlutfallsreglum, óháð því hvað þeir hafa í hlutföll.</li>
<li>Þeir sem gegna þessari stöðu fá titil að eigin vali sem birtist við innsent efni notandans og í notandaupplýsingum.</li><br />
--- Þó má ekki nota titil sem gæti bent til þess að notandinn gegni hærri stöðu en hann er eða titil þar sem vísað er í aðra notendur vefsins.<br />
<li>Þeir sem gegna þessari stöðu eru undanskildir reglum um óvirkni.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Kosningar merkisfólks'); ?>
<ul>
<li>Allir sem styrkja vefinn fá atkvæðarétt við ákvarðanir sem teknar eru í tengslum við vefinn en þó ekki ákvarðanir sem hafa áhrif á breytingar á stefnu eða reglum Istorrent, mannaforráð félagsins eða hvort það eigi að hætta við eitthvað sem er þegar í framkvæmd (ef það er yfir takmarkað tímabil).</li>
<li>Eitt atkvæði skal vera fyrir hverjar byrjaðar 500 krónur sem notandinn hefur styrkt vefinn um.</li><br />
--- Hvert atkvæði er endurnýjanlegt fyrir hverja kosningu sem fer fram.<br />
<li>Hægt er að kjósa um hverja innsenda hugmynd í heila viku og eftir að tímabilið er liðið er reiknað út atkvæðavægið miðað við það sem er í gildi þá stundina sem útreikningur fer fram.</li>
<li>Þeir sem senda inn tillögu gefa henni sjálfkrafa atkvæði sitt.</li>
<li>Eingöngu þeir sem hafa styrkt í samræmi við styrktarreglur Istorrent mega greiða atkvæði.</li>
<li>Atkvæðahafar geta ekki skipt atkvæðunum sínum á milli valkosta í sömu kosningum.</li>
<li>Stjórnendur eru ekki bundnir við að framkvæma í samræmi við niðurstöðu kosninganna þegar í stað ef ekki er tími eða fjármagn til að framkvæma slíkar aðgerðir.</li>
<li>Ef það kemur í ljós að innsend hugmynd valdi því að Istorrent framkvæmi aðgerðir sem eru ólöglegar samkvæmt íslenskum lögum eða gegn skilmálum, reglum eða stefnu Istorrent, skal ekki framkvæma tillöguna.</li>
<li>Ákvæði til bráðabirgða: Reglur um kosningar skulu taka gildi þegar kerfið til þess er tilbúið til almennrar notkunar.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Styrkjareglur'); ?>
<ul>
<li>Styrkur er ekki talinn fullgildur fyrr en hann hefur borist inn á bankareikning Istorrent.</li>
<li>Notandi sem hefur styrkt fær ekki merkismannastöðuna fyrr en fullnægjandi tilkynning hefur borist til Istorrent þar sem hægt er að rekja notandanafn aðilans ásamt því að staðfesta að greiðslan hafi í raun verið fyrir tilstilli notandans.</li>
<li>Þegar styrkur hefur borist Istorrent er hann ekki endurgreiddur.</li><br />
--- Þó má endurgreiða hluta af styrknum ef greinileg mistök eru í upphæð hans sem gefandi hafi gert óvart. Svoleiðis beiðni þarf ekki framkvæma ef liðnir eru 2 dagar frá því að styrkur hafi verið staðfestur eða sama upphæð hafi verið gefin upp í tölvupósti til Istorrent.
<li>Öllum er heimilt að styrkja eins oft og þeir vilja.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Fríhelgareglur'); ?>
<ul>
<li>Fríhelgar er ákveðið tímabil, sem skal vara að minnsta kosti yfir laugardag og sunnudag sömu helgi, þar sem notendur skulu njóta sérkjara ásamt frádregnu niðurhali tímabilsins.</li>
<li>Fríhelgi skal hefjast kl. 18:00 á upphafsdegi hennar og ljúka þegar seinasta skilgreinda deginum lýkur.</li>
<li>Tímabilið skal ekki vara lengur en til loka sunnudags en þó má lengja hana sem lögbundnum frídögum nemur.</li><br />
--- Fríhelgi skal þó aldrei vera lengri en 4 sólarhringar nema samþykki liggi frá öllum stjórnendum fyrir undanþágunni.<br />
<li>Fríhelgi skal alltaf vera tilkynnt með að minnsta kosti 6 sólarhringa fyrirvara.</li><br />
--- Þó má hafa fríhelgar sem eru ekki tilkynntar en þá skal tilkynna að þær hafi verið að minnsta kosti sólarhring eftir að hverri þeirra lýkur.<br />
<li>Sérkjör hverrar fríhelgar sem krefjast útreikninga skulu vera framkvæmd eftir að henni er lokið en þó innan tveggja sólarhringa.</li>
<li>Stjórnendum er heimilt að ákveða að bið eftir nýjum torrent deilingum verði afnumin tímabundið á meðan fríhelgi varir.</li>
<li>Stjórnendum er heimilt að breyta hólfareglum tímabundið á meðan fríhelgi varir.</li>
<li>Öll sérkjör notenda, þar á meðal þau er varða hlutfalla- og hólfakerfið, skulu vera skilgreind og tilkynnt notendum með að minnsta kosti sólarhringsfyrirvara áður en þau taka gildi.</li><br />
--- Þó skal ekki tilkynna þau eigi fríhelgin að vera ótilkynnt.<br />
<li>Þrátt fyrir sérkjör notenda á fríhelgum, þá skulu notendur með gilda viðvörun ekki njóta þeirra sérkjara sem stangast á við refsingu þeirra.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Stjórnendareglur'); ?>
<ul>
<li>Stjórnendur hafa eins mikla heimild til aðgerða og er skilgreind í skilmálum og reglum vefsins.</li>
<li>Stjórnendur skulu alltaf fara eftir settum reglum og skilmálum.</li>
<li>Sé gripið til þeirrar heimildar sem gefin er í atriði 8d í skilmálunum, skal þegar í stað senda inn nýtt umræðuefni á stjórnendaspjallflokkinn og tilkynna atvikið ásamt þeim rökstuðningi sem er á bakvið þeirri aðgerð sem gripin var.</li><br />
--- Aðrir stjórnendur skulu greiða atkvæði hvort að aðgerð stjórnandans var réttlát miðað við aðstæður. Komi fram í atkvæðagreiðslu að ákvörðunin hafi verið réttlát, skal hún standa, annars dregin til baka. Eftir atvikum má refsa viðkomandi stjórnanda fyrir ákvörðun sína ef fundið er út að hún hafi verið röng en það skal ákveðið eftir að niðurstöður atkvæðagreiðslunnar hafa verið fengnar.<br />
<li>Færa skal umræðuefni á rétt spjallborð í staðinn fyrir að loka þeim. Viðeigandi notanda má refsa sé heimild til þess í reglunum.</li>
<li>Alltaf skal greina frá því af hverju spjallþræði er læst nema um sé að ræða opinberan spjallþráð.</li>
<li>Skrifuð ástæða skal alltaf fylgja öllum bönnum og viðvörunum sem notendur fá frá stjórnendum.</li>
<li>Stjórnendur mega ekki eyða öðrum texta sem fyrir er.</li>
<li>Stjórnendur eru undanskildir reglum um óvirkni.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Bannreglur'); ?>
<ul>
<li>Eftirfarandi brot á reglum og skilmálum Istorrent leiða til þess að aðgangur notanda á Istorrent verði gerður óvirkur.</li><br />
--- Fyrir að senda inn falsaðar upplýsingar um deilimagn, skal vera þekkt sem hlutfallasvindl.<br />
--- Fyrir að brjóta á einhverja af þeim reglum sem eru í 5. lið í skilmálum.<br />
--- Fyrir að brjóta á lið 7b eða 7c í skilmálunum. Brot á lið 7b má hins vegar taka út sem 3ja mánaða viðvörun.<br />
--- Fyrir að reyna að komast framhjá refsingu á öðrum aðgangi í hans eigu. Óvirknisreglurnar skulu ekki teljast sem refsing í þessum skilningi.
</ul>
<? end_frame();
end_main_frame();
stdfoot(); ?>
