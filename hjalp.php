<?
ob_start();
require_once("include/bittorrent.php");
dbconn();
$file = basename($_SERVER['PHP_SELF']);
function problink($cat,$id,$title) {
	global $file;
	return '<a href="/'.$file.'?cat='.$cat.'&amp;ansid='.$id.'">'.$title.'</a><br />';
}
stdhead("Hjálparkerfi");
begin_main_frame();
loggedinorreturn();

$verifystring = verifystring($_GET['cat'],'num');
if($verifystring === TRUE)
        $cat = $_GET['cat'];

$verifystring = verifystring($_GET['ansid'],'num');
if($verifystring === TRUE)
        $ansid = $_GET['ansid'];

if(!$CURUSER) {
	begin_frame('Notkun kerfisins');
	echo 'Kerfið er eingöngu hannað til að anna hjálparspurnum notenda sem eru innskráðir. Getir þú það ekki skal leita í <a href="/faq.php">SOS</a> eða senda tölvupóst á <a href="mailto:torrent@torrent.is">torrent@torrent.is</a>.';
	end_frame();
} else {
	if(!$cat) {
		begin_frame('Velja flokk');
		echo 'Þetta hjálparkerfi mun sérsniða svörin þín byggt á upplýsingum sem Istorrent hefur um þig. Notendur geta því fengið mismunandi svör við hverri spurningu.<br />';
		echo 'ATH: Kerfið er eingöngu hannað fyrir vandamál og fyrirspurnir sem tengjast Istorrent beint. Öll vandræði sem ekki er hægt að rekja til Istorrent er hægt að forvitnast um <a href="/forums.php">á spjallborðinu</a>.<br /><br />';
		echo 'Til hvaða flokks á vandamálið mest við?<br />
		<a href="/'.$file.'?cat=1">Deiling</a><br />
		<a href="/'.$file.'?cat=2">Niðurhal</a><br />
		<a href="/'.$file.'?cat=3">Stöður</a><br />
		<a href="/'.$file.'?cat=4">Boðslyklar</a><br />
		<a href="/'.$file.'?cat=5">Eftirspurnir</a><br />
		<a href="/'.$file.'?cat=6">Annað</a><br />
		';
		end_frame();
	} elseif(isset($cat) && !$ansid) {
		switch($cat) {
		case '1':
			$title = 'Deilingarvandamál';
			$body = problink('1','1','Get ekki deilt skrá');
			$body .= problink('1','10','Þú hefur ekki nóg af lausum hólfum til að búa til torrent');
		break;
		case '2':
			$title = 'Niðurhal';
			$body = problink('2','10','Þú hefur ekki nægan hólfafjölda til að hefja þetta niðurhal');
			$body .= problink('2','11','Hvað þýða skilaboðin "Þetta torrent tekur ekki hólf, njóttu vel" ?');
			$body .= problink('2','12','"Þú ert ekki að deila inn torrenti sem þú hefur sent inn..." kemur þegar ég reyni að ná í torrent.');
		break;
		case '3':
			$title = 'Stöður';
			$body = problink('3','6','Af hverju er ég ekki komin(n) með merkismannastöðuna?');
			$body .= problink('3','7','Af hverju er ég ekki talin(n) sem "Mjög virkur notandi"?');
		break;
		case '4':
			$title = 'Boðslyklar';
			$body = problink('4','8','Af hverju hef ég enga boðslykla?');
		break;
		case '5':
			$title = 'Eftirspurnir';
			$body = problink('5','9','Af hverju get ég ekki lagt inn eftirspurn?');
		break;
		case '6':
			$title = 'Annað';
			$body = problink('6','13','Hvernig breyti ég um titil?');
			$body .= problink('6','14','Hvernig hef ég umsjón með undirskriftinni minni?');
			$body .= problink('6','15','Kerfið kvartar yfir því að ég sé ekki að deila...');
		}
		begin_frame($title);
		echo $body;
		end_frame();
	} elseif(isset($cat) && isset($ansid)) {
		switch($ansid) {
			case '1':
				$title = 'Get ekki deilt skrá';
				$body = 'Hefurðu sent torrent skrána inn á Istorrent?<br />';
				$body .= problink('1','2','Já');
				$body .= problink('1','3','Nei');
			break;
			case '2':
				$title = 'Get ekki deilt skrá';
				$body = '<b>Hef sent inn skrána</b><br />';
				if(find_unseeded($CURUSER['id']))
					$body .= 'Einhver skráin sem þú hefur sent inn vantar deilara. Nánar um málið er í <a href="/faq.php#85">SOS færslu 85</a>.';
				else {
					$body .= 'Istorrent er að rannsaka hvað þú hefur sent inn...<br />';
					$sql = 'SELECT name FROM torrents WHERE owner='.$CURUSER['id'];
					$res = mysql_query($sql);
					if(mysql_num_rows($res) > '0') {
						$body .= 'Er einhver eftirtalinna skrá sú sem þú ert að reyna að deila?<br />';
						while($arr = mysql_fetch_assoc($res)) {
							$body .= $arr['name'].'<br />';
						}
						$body .= problink('1','4','Já');
						$body .= problink('1','5','Nei');
					} else
						$body .= '<br /><b>Engin skrá frá þér er inni í kerfinu.</b>';
				}
			break;
			case '3':
				$title = 'Get ekki deilt skrá';
				$body = '<b>Hef ekki sent inn torrent skrána</b><br /><br />';
				$body .= 'Hafir þú ekki reynt að senda inn skrána, vinsamlegast gerðu það. Ef þú hefur reynt en þér tekst það ekki, lestu áfram.<br />';
				if(find_unseeded($CURUSER['id']) === '1')
					$body .= 'Þú getur ekki deilt ef eitthvað sem þú hefur sent inn vantar deilara. Auk þess geturðu ekki sent inn ef þú ert eina manneskjan sem ert að deila einhverju "yngra" en 24 klukkustunda. Nánar um lausnir á þessu og upplýsingar eru í <a href="/faq.php#85">SOS færslu 85</a>';
				elseif(slots($CURUSER['id'],'free') == '0') {
					$body .= 'Þú hefur ekki nóg af lausum hólfum. Vinsamlegast líttu á eftirfarandi svar:<br />';
					$body .= problink('1','10','Þú hefur ekki næg hólf til að...');
				} else {
					$body .= 'Athugaðu skrána og hvort að eitthvað af eftirfarandi passar við hana:<br />';
					$body .= '* Skráarendingin er ekki .torrent<br />';
					$body .= '* .torrent skráin er stærri en 10 megabæti (10.485.760 bæti)<br />';
					$body .= '* Skrárnar taldar inni í .torrent skránni eru mikið fleiri en þúsund talsins.<br />';
					$body .= 'Passi eitt af fyrstu tveim atriðunum mun skráin ekki vera tekin við af Istorrent. Passi seinasta atriðið er hætta á því að kvartað sé um að orðabók vanti lykla.';
				}
			break;
			case '4':
				$title = 'Get ekki deilt skrá';
				$body = 'Til að klára að senda inn, þarf að ná í skrána frá Istorrent og nota hana inn í BitTorrent forritinu en ekki skrána sem var búin til.';
			break;
			case '5':
				$title = 'Get ekki deilt skrá';
				// Hef ekki sent inn skrána - svar
			break;
			case '6':
				$title = 'Af hverju er ég ekki skráð(ur) sem gefandi?';
				if($CURUSER['donor'] === 'yes')
					$body = 'Þú ert þegar skráð(ur) sem gefandi.';
				else
					$body = 'Hafir þú sent tölvupóst á torrent@torrent.is með notandanafni, kennitölu greiðanda og upphæð, þá ætti umsóknin þín að vera í afgreiðslu. Það getur tekið einhverja daga þangað til umsóknin er afgreidd og þangað til staðan er veitt.';
			break;
			case '7':
				$title = 'Af hverju er ég ekki talin(n) sem "Mjög virkur notandi"?';
				if($CURUSER['class'] > UC_POWER_USER)
					$body = 'Þeir sem eru stjórnendur eru ekki taldir sem "Mjög virkir notendur" þar sem þeir njóta þeirra kjara nú þegar í núverandi stöðu.';
				elseif($CURUSER['class'] == UC_POWER_USER)
					$body = 'Þú ert nú þegar talin(n) gegna þessari stöðu.';
				else {
					$body = 'Kerfið fer nú yfir skilyrði til þess að vera með stöðuna og nefnir hvort þú stenst þær kröfur...<br />';
					@$ratio = $CURUSER['uploaded']/$CURUSER['downloaded'];
					$body .= 'Hlutföll jafnt og eða hærri en 4,00 - ';
					if($ratio >= 4.00)
						$body .= 'Uppfyllt<br />';
					else {
						if(round($ratio,2) === '4.00')
							$body .= '<b>Óuppfyllt (Hlutfallið sem þú sérð er námundað upp í 4.00 svo það er stutt í að þú uppfyllir þá kröfu)</b><br />';
						else
							$body .= '<b>Óuppfyllt</b><br />';
					}
					$t_medlimur = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400*14)));
				        $t_medlimur2 = str_replace(array(' ',':','-'),'',$CURUSER['added']);
					$body .= 'Hafa verið meðlimur Istorrent í 2 vikur eða lengur - ';
					if($t_medlimur > $t_medlimur2)
						$body .= 'Uppfyllt<br />';
					else
						$body .= '<b>Óuppfyllt</b><br />';
					$body .= 'Hafa deilt 100 gígabætum eða meira - ';
					if($CURUSER['uploaded'] >= (100*1024*1024*1024))
						$body .= 'Uppfyllt<br />';
					else
						$body .= '<b>Óuppfyllt</b><br />';
					$body .= '<br />Uppfylla þarf öll þessi skilyrði til að fá þessa stöðu. Kerfið lítur yfir notendur á 15 mínútna fresti og veitir þeim stöðuhækkun sem uppfylla skilyrðin svo að það er möguleiki að það ferli eigi eftir að fara í gang.<br />';
					$body .= 'Einnig ber að geta þess að hlutfallatölur sem birtast notendum eru námundaðar að 2 aukastöfum en kerfið sem veitir stöðuhækkanir gerir það ekki.';
				}
			break;
			case '8':
				$title = 'Af hverju hef ég enga boðslykla?';
				$lyklar = inviteleft($CURUSER['id'],$CURUSER['uploaded'],$CURUSER['downloaded'],$CURUSER['warned'],$CURUSER['added']);
				if($lyklar > '0')
					$body = 'Þú átt að hafa '.$lyklar.' nýtanlega boðslykla. Slóðin sem þarf að fara á til að búa til boðslykla er <a href="/invites.php">'.$BASEURL.'/invites.php</a>';
				elseif($CURUSER['class'] < UC_USER && $CURUSER['donor'] === 'no')
					$body = 'Þú gegnir ekki nógu hárri stöðu til að geta gefið út boðslykla.';
				else {
					$body = 'Kerfið mun reikna út af hverju þú getur ekki boðið inn...<br />';
					if($CURUSER['warned'] === 'yes')
						$body .= '<b>Notendur með gilda viðvörun geta ekki búið til boðslykla.</b><br />';
					$t_medlimur = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400*14)));
				        $t_medlimur2 = str_replace(array(' ',':','-'),'',$CURUSER['added']);
					if($t_medlimur2 > $t_medlimur)
						$body .= '<b>Þú verður að hafa verið meðlimur Istorrent í 2 vikur eða lengur til að geta boðið inn.</b><br />';
					else
						$body .= 'Þú uppfyllir skilyrðið um að hafa verið meðlimur Istorrent í 2 vikur eða lengur.<br />';
					$body .= 'Þú hefur deilt: '.mksize($CURUSER['uploaded']).'<br />';
					$s = (25*1024*1024*1024);
					$body .= 'Kerfið mun fyrst draga '.mksize($s).' frá deilimagninu þínu í þessum útreikningum og þá kemur í ljós að þú hefur '.mksizegb($CURUSER['uploaded']-$s).' eftir það.<br />';
					$body .= 'Sé talan neikvæð, þarftu að vinna það upp en kerfið lætur þig strax fá "0" boðslykla ef hún er það.<br />';
					if($CURUSER['uploaded']-$s > '0') {
						$lyklar = floor(($CURUSER['uploaded']-$s)/(5*1024*1024*1024));
						$body .= 'Síðan er reiknað hve marga boðslykla þú ættir að hafa eftir hver 5 gígabæti yfir þau '.mksize($s).' sem þú þarft að hafa og í þínu tilviki eru...'.$lyklar.'<br />';
						$sql_inv = 'SELECT COUNT(*) FROM users WHERE invitari = '.$CURUSER['id'].' AND deleted=0';
				                $s2 =  mysql_result(mysql_query($sql_inv),0);
						$body .= 'Fjöldi notenda sem þú hefur boðið og hafa ekki verið eyddir út, '.$s2.', eru dregnir frá<br />';
						$sql_inv2 = 'SELECT COUNT(*) FROM invites WHERE inviter_id='.$CURUSER['id'].' AND used=0';
						$s3 = mysql_result(mysql_query($sql_inv2),0);
						$body .= 'Notendur sem þú hefur búið til boðslykla fyrir og hafa ekki notað þá: '.$s3.'<br />';
						$body .= 'Þú ættir því að hafa '.($lyklar-$s2-$s3).' lykla á lausu. Kerfið mun hækka töluna upp í 0 sé talan neikvæð eða notandi hefur viðvörun.';
					} else
						$body .= 'Þú getur séð afganginn af útreikningunum eftir að hafa unnið þetta upp og ef þú ert enn í vandræðum.';
				}
			break;
			case '9':
				$title = 'Af hverju get ég ekki lagt inn eftirspurn?';
				if(($CURUSER['class'] >= UC_GOOD_USER || $CURUSER['donor'] === 'yes') && requests_free($CURUSER['id']) > '0')
					$body = 'Þú getur þegar lagt inn eftirspurn með því að fara á tengilinn <a href="/requests.php">'.$BASEURL.'/requests.php</a>';
				elseif($CURUSER['class'] >= UC_GOOD_USER || $CURUSER['donor'] === 'yes')
					$body .= 'Þú hefur réttindin til að setja inn eftirspurn en þú þarft að vinna þér það inn. Hægt er að leggja inn eina eftirspurn fyrir hver 10GB sem þú deilir.';
				else
					$body = 'Eingöngu þeir sem gegna stöðunni "Mjög virkur notandi" eða hærri geta lagt inn eftirspurn. Þú verður að vinna þér inn þessa stöðu áður.<br /><br />Leggir þú inn eftirspurn annars staðar á Istorrent muntu fá 3ja daga viðvörun.';
			break;
			case '10':
				$title = 'Þú hefur ekki nægan hólfafjölda...';
				$body = 'Þessi skilaboð birtast þegar þú hefur ekki laus hólf til að gera eitthvað.<br /><br />';
				$body .= 'Þegar verið er að taka þátt í torrenti, hvort sem þú ert að sækja eða deila, tekur það ákveðinn hólfafjölda. Þú getur séð hólfanotkun þína í textanum sem er rétt undir Istorrent merkinu efst til vinstri. Það sem er á undan / merkinu er hólfafjöldinn sem þú ert að nota og það sem er á eftir merkinu er sá hámarksfjöldi hólfa sem þú hefur til umráða.<br /><br />';
				if(slots($CURUSER['id'],'free') > '1')
					$body .= '<b>Þú ættir að hafa nægan hólfafjölda til að hefja niðurhal eða deilingu. Vinsamlegast prófaðu aftur þá aðgerð sem framkvæmdi þessi skilaboð.</b>';
				else
					$body .= '<b>Til að byrja að sækja eða deila meiru, þarftu að slökkva á einhverju af því sem er í gangi.</b>';
			break;
			case '11':
				$title = 'Hvað þýða skilaboðin "Þetta torrent tekur ekki hólf, njóttu vel" ?';
				$body = 'Hólfakerfið tekur eingöngu torrent sem eru yngri en 48 klst.(2 sólarhringa) sem hólf. Þessi skilaboð þýða að torrentið sem þú ert að skoða muni ekki teljast sem notað hólf.';
			break;
			case '12':
				$title = '"Þú ert ekki að deila inn torrenti sem þú hefur sent inn..." kemur þegar ég reyni að ná í torrent.';
				$body = 'Þetta þýðir að torrent sem þú hefur sent inn áður er án deilanda eða þú ert ekki að deila einhverju sem þú hefur sent inn seinustu 24 klukkustundirnar. Nánari upplýsingar eru í <a href="/faq.php#85">SOS færslu 85</a>.';
			break;
			case '13':
				$title = 'Hvernig breyti ég um titil?';
				if($CURUSER['class'] >= UC_POWER_USER || $CURUSER['donor'] === 'yes')
					$body = 'Titlum er breytt <a href="/my.php">í prófíl</a>';
				else
					$body = 'Þú þarft að vera skráður gefandi eða gegna stöðunni "Mjög virkur notandi" eða æðri til að geta breytt um titil';
			break;
			case '14':
				$title = 'Hvernig hef ég umsjón með undirskriftinni minni?';
				if($CURUSER['class'] >= UC_USER || $CURUSER['donor'] === 'yes') {
					$body = 'Hægt er að setja og breyta undirskrift <a href="/my.php">í prófíl</a>.<br /><br />';
					$body .= 'Til að eyða undirskriftinni er nóg að tæma undirskriftarreitinn og framkvæma breytinguna.';
				} else
					$body = 'Þú þarft að vera skráður gefandi eða gegna stöðunni "Notandi" eða æðri til að geta sett og breytt þinni eigin undirskrift.';
			break;
			case '15':
				$title = 'Kerfið kvartar yfir því að ég sé ekki að deila...';
				$body = 'Kerfið mun lista hér þær skrár sem þú átt að deila en gerir það ekki:<br /><br />';
				if(find_unseeded($CURUSER['id']) === '1') {
					$body .= find_unseeded($CURUSER['id'],'list');
					$body .= 'Áður en þú getur niðurhalað eða sent inn fleiri skrár þarftu að bregðast við þeim villum sem eru nefndir fyrir aftan hvert innsent torrent.<br /><br />';
				} else
					$body .= '<b>Þú ert að deila öllu sem þú átt að deila þessa stundina.</b>';
				$body .= 'Nánari lýsingu á því hvernig <a href="/faq.php#85">þessi villuboð virka og mögulegar lausnir</a> er hægt að finna í Spurt og svarað.';
			break;
		}
		begin_frame($title);
		echo $body;
		end_frame();
	}
}
end_main_frame();
stdfoot();
ob_flush();
?>
