<?

ob_start("ob_gzhandler");

require "include/bittorrent.php";

dbconn(true);

stdhead("Reglur - fl�knari");

begin_main_frame();
?>
Sk�ringar:<br />
Hver regla er me� eina svart�tfyllta k�lu fyrir framan.<br />
Undirreglur eru merktar me� "---" fyrir framan.<br />
<br />


<? 
begin_frame("Almennar reglur");

?>
<ul>
<li>Skilm�lar skulu alltaf vera ��ri reglunum ef �eim greinir �.</li><br />
--- Fyrir utan skilm�lana, skulu reglurnar alltaf hafa s��asta or�i�.
<li>Allir notendur vefsins skulu fara eftir �eim skilm�lum og reglum sem eru � gildi � vefnum.</li>
<li>Pers�nuverndarstefna Istorrent skal alltaf vera opinber og gefin til kynna � n�skr�ningarferli.</li>
<li>Gefa m� �t einfaldari �tg�fa af �essum reglum en �essar reglur skulu vera ��r sem gilda ef upp koma �greiningsm�l.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('�virknisreglur'); ?>
<ul>
<li>Notendur sem eru �virkir � 12 vikur samfleytt skal vera eytt.</li><br />
--- �virkni er �egar notandi skr�ir sig alls ekkert inn � vefinn.<br />
<li>Stj�rnendur mega veita undantekningu � �essari reglu ef g�� �st��a er gefin upp og a� t�malengd �virkninnar sem hl�st af �eim atbur�i er lengri, e�a er sp�� vera h�ttulega n�l�gt �eim m�rkum er notandi ver�i ger�ur �virkur.</li><br />
--- Lendi notandi � �v� a� a�gangnum hans hafi veri� eytt �r�tt fyrir a� hafa fengi� undantekningu fr� �essum reglum, m� afturkalla ey�inguna eftir a� fulln�gjandi bei�ni hefur borist fr� vi�komandi notanda � t�lvup�sti.<br />
--- Undantekningin skal eing�ngu vera � gildi fyrir eitt skipti � einu og be�i� hafi veri� um hana ��ur en �virknist�mabil notandans h�fst.
</ul>
<? end_frame(); ?>
<? begin_frame('Hlutfallsreglur'); ?>
<ul>
<li>Hlutfall hvers notanda skal reiknast me� �v� a� deila deilimagni notandans me� ni�urhalsmagni hans.</li>
<li>L�gmarkshlutfalli� skal skilgreint vera 0,20 e�a l�gra.</li><br />
--- Refsia�ger�ir vegna l�gmarkshlutfalls skulu eing�ngu vera framkv�mdar s� ni�urhalsmagn notanda meira en 2 g�gab�ti og hafi hann veri� me�limur � 2 heilar vikur e�a lengur.<br />
<li>A�gangur a� n�justu torrent f�rslum skal stj�rnast af hlutfalli vi�komandi notanda og er �kve�inn bi�t�mi eftir a� mega taka ��tt � dreifingu n�rra torrent innsendinga. Bi�in skal mi�ast vi� �ann t�ma fr� �v� a� torrent f�rslan var send inn.</li><br />
--- Notandi me� 0,75 og h�rra � hlutf�ll er ekki h��ur bi�.<br />
--- Notandi fr� og me� 0,50 og minna en 0,75 � hlutf�ll skal �urfa a� b��a 12 klukkustundir.<br />
--- Notandi me� minna en 0,50 � hlutf�ll skal �urfa a� b��a � 24 klukkustundir.<br />
<li>S� notandi � l�gmarkshlufallinu, skal hann vera ger�ur �virkur.</li><br />
--- �kv��i� skal ekki framkv�mt ef notandi er � ��trunnum vikufresti.
</ul>
<? end_frame(); ?>
<? begin_frame('Vikufrestur'); ?>
<ul>
<li>Notendur skulu geta be�i� um vikufrest til a� b�ta �r l�gmarkshlutfallinu eins og �a� er skilgreint � hlutfallareglum, hafi �eir veri� ger�ir �virkir vegna �eirra.</li><br />
--- H�gt er a� f� �ennan frest �r�tt fyrir a� hafa veri� eytt samkv�mt hlutfallsreglum og reglum um �virkni.<br />
--- Lendi notandi � �v�, eftir a� vikufresturinn er li�inn, a� vera � l�gmarkshlutfallinu, skal hann ger�ur �virkur til framb��ar.<br />
--- S� h�lft �r li�i� fr� vikufrestinum og notanda t�kst a� vinna sig �r l�gmarkshlutfallinu, m� senda notanda vi�v�run og tilkynna a� honum skuli eytt ef hann b�tir sig ekki innan viku. �essa undantekningu skal eing�ngu beita einu sinni fyrir hvern a�gang.<br /> </ul>
<? end_frame(); ?>
<? begin_frame('H�lfareglur'); ?>
<ul>
<li>H�lf skulu vera reiknu� sem fj�ldi torrent f�rsla sem notandi tekur ��tt �.</li>
<li>S� notandi a� fulln�ta h�lfin sem honum hefur veri� �thluta� skal hann ekki geta hafi� a�rar a�ger�ir sem krefjast auka h�lfa.</li>
<li>Hlutf�ll notanda og sta�a hans skulu r��a hve m�rg h�lf hann hefur til sinna umr��a.</li><br />
--- Notendur � st��unni "Mj�g virkur notandi" e�a h�rra settir skulu hafa 12 h�lf.<br />
--- Notendur � st��unni "Virkur notandi" skulu hafa 8 h�lf.<br />
--- Notendur � st��unni "Notandi" skulu hafa 4 h�lf.<br />
--- Notendur � st��unni "Byrjandi" skulu hafa 2 h�lf.
</ul>
<? end_frame(); ?>
<? begin_frame('Vi�v�runarreglur'); ?>
<ul>
<li>Notendur sem ekki eru banna�ir af vefnum skulu f� vi�v�run � �kve�inn langan t�ma eftir broti �eirra.</li>
<li>S� notandi me� gilda vi�v�run og tilefni er til annarar vi�v�runar, er stj�rnanda heimilt a� banna notanda e�a endurgefa vi�v�runina me� lengri t�ma en ��r hef�u gert samanlagt.</li><br />
--- Banni� skal eing�ngu nota� ef tilefni einhverrar vi�v�runinnar er alvarlegt.<br />
--- G�ta skal �ess a� gefa ekki vi�v�run fyrir n�kv�mlega sama tilefni.<br />
--- Lengri t�masetningu skal eing�ngu gefa ef s��ari brot eru framkv�md eftir a� notanda hefur veri� gert grein fyrir brotinu.<br />
<li>Notendur me� vi�v�run skulu �urfa a� b��a � 24 klukkustundir fr� innsendingart�ma n�rra deilinga.</li>
<li>Notendur me� vi�v�run skulu hafa jafnm�rg h�lf til sinna umr��a og s� h�pur notenda sem hefur f�st h�lf.</li>
<li>Notendur me� vi�v�run geta ekki b�i� til bo�slykla.</li>
<li>Notendur me� vi�v�run skulu ekki geta sent inn eftirspurnir.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Spjallreglur'); ?>
<ul>
<li>�essar spjallreglur skulu gilda fyrir umr��usv��i fyrir innsend torrent, spjallbor�i�, spjallr�s Istorrent � IRC og a�ra sta�i � bo�i Istorrent �ar sem h�gt er a� tj� sig nema reglan sj�lf nefni s�rstaklega hvar h�n � a� gilda.</li>
<li>Notendur skulu hafa eins miki� tj�ningarfrelsi og skilm�lar vefsins leyfa.</li>
<li>Ekki senda inn hlekki � "warez" e�a "crack" vefi.</li>
<li>Ekki bi�ja um e�a veita forrit e�a lei�beiningar til �ess a� fara framhj� afritunarv�rnum e�a sta�festingark��um fyrir hugb�na� sem hefur svolei�is v�rn.</li>
<li>Ekki skal senda inn spjallp�st � "dautt" umr��uefni � spjallbor�inu � �eim eina tilgangi a� veita athygli a� �v� � n� nema greinilegt s� a� ��rf var � �v� innleggi.</li>
<li>Hver og einn notandi skal g�ta a� �v� a� nota ekki of st�rar myndir � undirskrift sinni e�a senda svolei�is myndir sem hluta af spjallp�sti.</li>
<li>Heimilt er a� refsa notendum sem af st�ru g�leysi e�a v�svitandi senda inn umr��uefni � vitlausan flokk � spjallbor�inu.</li>
<li>"Now playing" og �l�kir textar eru banna�ir alls sta�ar � umr��usv��um Istorrent nema skrifa�ur er lengri texti um �a� sem kom fram � fyrrgreindum texta.</li> </ul>
<li>Textar sem eru ekki skrifa�ir handvirkt inn um t�lvusamsetningu eru ekki leyf�ir � spjallr�s Istorrent � IRC.</li>
<li>Ekki skal bi�ja um bo�slykla inn � Istorrent � spjallr�s Istorrent � IRC</li>
<? end_frame(); ?>
<? begin_frame('Myndareglur'); ?>
<ul>
<li>Myndareglur skulu gilda � �llu vefsv��i Istorrent.</li>
<li>Ekki m� senda inn myndir af atbur�um �ar sem innsending myndanna br�tur � b�ga vi� landsl�g.</li>
<li>Leyft er a� senda inn myndir � �ekktum sni�um sem heyra undir vi�urkenndan vefsta�al. �ar m� nefna gif, jpg og png.</li>
<li>Ef notu� er mynd sem sm�mynd (avatar) sem g�ti m��ga� e�a s�rt blyg�unarkennd annarra notenda, skal merkja hana sem sl�ka � pr�f�l.</li>
<li>Sm�myndir skulu ekki vera meira en 200 pixlar � h�� og ekki vera st�rri en 150 k�l�b�ti a� st�r�.</li>
<li>Ekki skal nota m��gandi e�a s�randi myndir � undirskriftum, senda inn sl�kar myndir sem hluta af spjallp�sti e�a nota � torrent l�singu.</li><br />
--- �� m� senda inn svolei�is mynd sem hluta af spjallp�sti e�a sem hluta ad torrent l�singu ef sko�andanum er gefin g�� hugmynd um hvernig myndum hann m� b�ast vi� a� sj� ��ur en hann opnar vi�eigandi s��u.
</ul>
<? end_frame(); ?>
<? begin_frame('Torrent innsendingarreglur'); ?>
<ul>
<li>Reglur � �essum flokki skulu gilda um allar innsendingar � torrent skr�m og vi�eigandi uppl�singum.</li>
<li>Innsendari ber �byrg� � �v� a� skr�num sem deilt er me� tilstilli innsendra torrent skr�a uppfylli allar ��r reglur sem settar eru af Istorrent.</li>
<li>Greinarg�� l�sing skal fylgja hverri torrent f�rslu og skal sko�andi hafa g��a hugmynd um hva� hann g�ti n�� �.</li>
<li>�heimilt er a� dreifa v�rusum e�a ��rum t�lvuforritum sem geta skemmt g�gn e�a valdi� ��rum notendum ���gindum.</li>
<li>�heimilt er a� dreifa forritum sem breyta virkni annarra forrita �ar sem framlei�andi hefur ford�mt sl�ka notkun forrita.</li>
<li>Alltaf skal greina satt og r�tt fr� � uppl�singum innsendra torrenta.</li>
<li>Titill efnisins skal vera stuttur og hnitmi�a�ur og skal ekki innihalda ��arfa uppl�singar og m� �ar nefna hvort efni� hafi veri� umbe�i� e�a ekki e�a pers�nulegt �lit � innihaldinu.</li>
<li>Ekki skal innihalda � l�singu lykla e�a lei�beiningar til a� komast framhj� afritunarv�rnum e�a sta�festingark��um.</li><br />
--- �� skal ekki breyta "NFO" skjali ef �a� fylgir me�.<br />
<li>Innsendandi skal sj� til �ess a� torrent f�rslan s� virk � 24 klukkustundir eftir a� h�n er send inn.</li><br />
--- Innsendandi m� eing�ngu h�tta a� deila innan 24 klukkustunda s�u nokkrir a�rir komnir me� efni� a� fullu og eru a� taka ��tt � deilingu.<br />
<li>Innsendandi m� ekki sl�kkva � deilingu s� hann s� eini sem er me� allt efni� og virkir a�ilar s�u skr��ir s�kjendur �ess.</li>
<li>Ef sent er inn safn, t.d. heilt ��ttat�mabil, skal senda inn eitt t�mabil � einni innsendingu.</li>
<li>Ekki innihalda �tg�fudag � torrent nafni.</li><br />
--- �� m� innihalda dagsetningu ef atbur�irinn �tti s�r sta� � beinni �tsendingu.
</ul>
<? end_frame(); ?>
<? begin_frame('Merkismannast��ureglur'); ?>
<ul>
<li>Eing�ngu �eir sem b�i� er a� sta�festa a� hafa gefi� � Istorrent sj��inn mega hafa �essa st��u.</li><br />
--- Stj�rnendur mega gefa einhverjum st��una � hei�ursskyni fyrir st�rf s�n � Istorrent.<br />
<li>�eir sem gegna �essari st��u skulu nj�ta a� minnsta kosti s�mu kjara og �eir sem gegna st��unni "Virkur notandi".</li><br />
--- �eir skulu samt ekki nj�ta sj�lfkrafa �eirra kjara sem mj�g virkir notendur nj�ta vegna deilimagns s�ns e�a hlutfalls nema �ess s� s�rstaklega geti� � �essum reglum.<br />
<li>�eir sem gegna �essari st��u skulu ekki �urfa a� b��a eftir a� geta byrja� � torrent f�rslu eins og geti� er � hlutfallsreglum, �h�� �v� hva� �eir hafa � hlutf�ll.</li>
<li>�eir sem gegna �essari st��u f� titil a� eigin vali sem birtist vi� innsent efni notandans og � notandauppl�singum.</li><br />
--- �� m� ekki nota titil sem g�ti bent til �ess a� notandinn gegni h�rri st��u en hann er e�a titil �ar sem v�sa� er � a�ra notendur vefsins.<br />
<li>�eir sem gegna �essari st��u eru undanskildir reglum um �virkni.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Kosningar merkisf�lks'); ?>
<ul>
<li>Allir sem styrkja vefinn f� atkv��ar�tt vi� �kvar�anir sem teknar eru � tengslum vi� vefinn en �� ekki �kvar�anir sem hafa �hrif � breytingar � stefnu e�a reglum Istorrent, mannaforr�� f�lagsins e�a hvort �a� eigi a� h�tta vi� eitthva� sem er �egar � framkv�md (ef �a� er yfir takmarka� t�mabil).</li>
<li>Eitt atkv��i skal vera fyrir hverjar byrja�ar 500 kr�nur sem notandinn hefur styrkt vefinn um.</li><br />
--- Hvert atkv��i er endurn�janlegt fyrir hverja kosningu sem fer fram.<br />
<li>H�gt er a� kj�sa um hverja innsenda hugmynd � heila viku og eftir a� t�mabili� er li�i� er reikna� �t atkv��av�gi� mi�a� vi� �a� sem er � gildi �� stundina sem �treikningur fer fram.</li>
<li>�eir sem senda inn till�gu gefa henni sj�lfkrafa atkv��i sitt.</li>
<li>Eing�ngu �eir sem hafa styrkt � samr�mi vi� styrktarreglur Istorrent mega grei�a atkv��i.</li>
<li>Atkv��ahafar geta ekki skipt atkv��unum s�num � milli valkosta � s�mu kosningum.</li>
<li>Stj�rnendur eru ekki bundnir vi� a� framkv�ma � samr�mi vi� ni�urst��u kosninganna �egar � sta� ef ekki er t�mi e�a fj�rmagn til a� framkv�ma sl�kar a�ger�ir.</li>
<li>Ef �a� kemur � lj�s a� innsend hugmynd valdi �v� a� Istorrent framkv�mi a�ger�ir sem eru �l�glegar samkv�mt �slenskum l�gum e�a gegn skilm�lum, reglum e�a stefnu Istorrent, skal ekki framkv�ma till�guna.</li>
<li>�kv��i til br��abirg�a: Reglur um kosningar skulu taka gildi �egar kerfi� til �ess er tilb�i� til almennrar notkunar.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Styrkjareglur'); ?>
<ul>
<li>Styrkur er ekki talinn fullgildur fyrr en hann hefur borist inn � bankareikning Istorrent.</li>
<li>Notandi sem hefur styrkt f�r ekki merkismannast��una fyrr en fulln�gjandi tilkynning hefur borist til Istorrent �ar sem h�gt er a� rekja notandanafn a�ilans �samt �v� a� sta�festa a� grei�slan hafi � raun veri� fyrir tilstilli notandans.</li>
<li>�egar styrkur hefur borist Istorrent er hann ekki endurgreiddur.</li><br />
--- �� m� endurgrei�a hluta af styrknum ef greinileg mist�k eru � upph�� hans sem gefandi hafi gert �vart. Svolei�is bei�ni �arf ekki framkv�ma ef li�nir eru 2 dagar fr� �v� a� styrkur hafi veri� sta�festur e�a sama upph�� hafi veri� gefin upp � t�lvup�sti til Istorrent.
<li>�llum er heimilt a� styrkja eins oft og �eir vilja.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Fr�helgareglur'); ?>
<ul>
<li>Fr�helgar er �kve�i� t�mabil, sem skal vara a� minnsta kosti yfir laugardag og sunnudag s�mu helgi, �ar sem notendur skulu nj�ta s�rkjara �samt fr�dregnu ni�urhali t�mabilsins.</li>
<li>Fr�helgi skal hefjast kl. 18:00 � upphafsdegi hennar og lj�ka �egar seinasta skilgreinda deginum l�kur.</li>
<li>T�mabili� skal ekki vara lengur en til loka sunnudags en �� m� lengja hana sem l�gbundnum fr�d�gum nemur.</li><br />
--- Fr�helgi skal �� aldrei vera lengri en 4 s�larhringar nema sam�ykki liggi fr� �llum stj�rnendum fyrir undan��gunni.<br />
<li>Fr�helgi skal alltaf vera tilkynnt me� a� minnsta kosti 6 s�larhringa fyrirvara.</li><br />
--- �� m� hafa fr�helgar sem eru ekki tilkynntar en �� skal tilkynna a� ��r hafi veri� a� minnsta kosti s�larhring eftir a� hverri �eirra l�kur.<br />
<li>S�rkj�r hverrar fr�helgar sem krefjast �treikninga skulu vera framkv�md eftir a� henni er loki� en �� innan tveggja s�larhringa.</li>
<li>Stj�rnendum er heimilt a� �kve�a a� bi� eftir n�jum torrent deilingum ver�i afnumin t�mabundi� � me�an fr�helgi varir.</li>
<li>Stj�rnendum er heimilt a� breyta h�lfareglum t�mabundi� � me�an fr�helgi varir.</li>
<li>�ll s�rkj�r notenda, �ar � me�al �au er var�a hlutfalla- og h�lfakerfi�, skulu vera skilgreind og tilkynnt notendum me� a� minnsta kosti s�larhringsfyrirvara ��ur en �au taka gildi.</li><br />
--- �� skal ekki tilkynna �au eigi fr�helgin a� vera �tilkynnt.<br />
<li>�r�tt fyrir s�rkj�r notenda � fr�helgum, �� skulu notendur me� gilda vi�v�run ekki nj�ta �eirra s�rkjara sem stangast � vi� refsingu �eirra.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Stj�rnendareglur'); ?>
<ul>
<li>Stj�rnendur hafa eins mikla heimild til a�ger�a og er skilgreind � skilm�lum og reglum vefsins.</li>
<li>Stj�rnendur skulu alltaf fara eftir settum reglum og skilm�lum.</li>
<li>S� gripi� til �eirrar heimildar sem gefin er � atri�i 8d � skilm�lunum, skal �egar � sta� senda inn n�tt umr��uefni � stj�rnendaspjallflokkinn og tilkynna atviki� �samt �eim r�kstu�ningi sem er � bakvi� �eirri a�ger� sem gripin var.</li><br />
--- A�rir stj�rnendur skulu grei�a atkv��i hvort a� a�ger� stj�rnandans var r�ttl�t mi�a� vi� a�st��ur. Komi fram � atkv��agrei�slu a� �kv�r�unin hafi veri� r�ttl�t, skal h�n standa, annars dregin til baka. Eftir atvikum m� refsa vi�komandi stj�rnanda fyrir �kv�r�un s�na ef fundi� er �t a� h�n hafi veri� r�ng en �a� skal �kve�i� eftir a� ni�urst��ur atkv��agrei�slunnar hafa veri� fengnar.<br />
<li>F�ra skal umr��uefni � r�tt spjallbor� � sta�inn fyrir a� loka �eim. Vi�eigandi notanda m� refsa s� heimild til �ess � reglunum.</li>
<li>Alltaf skal greina fr� �v� af hverju spjall�r��i er l�st nema um s� a� r��a opinberan spjall�r��.</li>
<li>Skrifu� �st��a skal alltaf fylgja �llum b�nnum og vi�v�runum sem notendur f� fr� stj�rnendum.</li>
<li>Stj�rnendur mega ekki ey�a ��rum texta sem fyrir er.</li>
<li>Stj�rnendur eru undanskildir reglum um �virkni.</li>
</ul>
<? end_frame(); ?>
<? begin_frame('Bannreglur'); ?>
<ul>
<li>Eftirfarandi brot � reglum og skilm�lum Istorrent lei�a til �ess a� a�gangur notanda � Istorrent ver�i ger�ur �virkur.</li><br />
--- Fyrir a� senda inn falsa�ar uppl�singar um deilimagn, skal vera �ekkt sem hlutfallasvindl.<br />
--- Fyrir a� brj�ta � einhverja af �eim reglum sem eru � 5. li� � skilm�lum.<br />
--- Fyrir a� brj�ta � li� 7b e�a 7c � skilm�lunum. Brot � li� 7b m� hins vegar taka �t sem 3ja m�na�a vi�v�run.<br />
--- Fyrir a� reyna a� komast framhj� refsingu � ��rum a�gangi � hans eigu. �virknisreglurnar skulu ekki teljast sem refsing � �essum skilningi.
</ul>
<? end_frame();
end_main_frame();
stdfoot(); ?>
