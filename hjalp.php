<?
ob_start();
require_once("include/bittorrent.php");
dbconn();
$file = basename($_SERVER['PHP_SELF']);
function problink($cat,$id,$title) {
	global $file;
	return '<a href="/'.$file.'?cat='.$cat.'&amp;ansid='.$id.'">'.$title.'</a><br />';
}
stdhead("Hj�lparkerfi");
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
	echo 'Kerfi� er eing�ngu hanna� til a� anna hj�lparspurnum notenda sem eru innskr��ir. Getir �� �a� ekki skal leita � <a href="/faq.php">SOS</a> e�a senda t�lvup�st � <a href="mailto:torrent@torrent.is">torrent@torrent.is</a>.';
	end_frame();
} else {
	if(!$cat) {
		begin_frame('Velja flokk');
		echo '�etta hj�lparkerfi mun s�rsni�a sv�rin ��n byggt � uppl�singum sem Istorrent hefur um �ig. Notendur geta �v� fengi� mismunandi sv�r vi� hverri spurningu.<br />';
		echo 'ATH: Kerfi� er eing�ngu hanna� fyrir vandam�l og fyrirspurnir sem tengjast Istorrent beint. �ll vandr��i sem ekki er h�gt a� rekja til Istorrent er h�gt a� forvitnast um <a href="/forums.php">� spjallbor�inu</a>.<br /><br />';
		echo 'Til hva�a flokks � vandam�li� mest vi�?<br />
		<a href="/'.$file.'?cat=1">Deiling</a><br />
		<a href="/'.$file.'?cat=2">Ni�urhal</a><br />
		<a href="/'.$file.'?cat=3">St��ur</a><br />
		<a href="/'.$file.'?cat=4">Bo�slyklar</a><br />
		<a href="/'.$file.'?cat=5">Eftirspurnir</a><br />
		<a href="/'.$file.'?cat=6">Anna�</a><br />
		';
		end_frame();
	} elseif(isset($cat) && !$ansid) {
		switch($cat) {
		case '1':
			$title = 'Deilingarvandam�l';
			$body = problink('1','1','Get ekki deilt skr�');
			$body .= problink('1','10','�� hefur ekki n�g af lausum h�lfum til a� b�a til torrent');
		break;
		case '2':
			$title = 'Ni�urhal';
			$body = problink('2','10','�� hefur ekki n�gan h�lfafj�lda til a� hefja �etta ni�urhal');
			$body .= problink('2','11','Hva� ���a skilabo�in "�etta torrent tekur ekki h�lf, nj�ttu vel" ?');
			$body .= problink('2','12','"�� ert ekki a� deila inn torrenti sem �� hefur sent inn..." kemur �egar �g reyni a� n� � torrent.');
		break;
		case '3':
			$title = 'St��ur';
			$body = problink('3','6','Af hverju er �g ekki komin(n) me� merkismannast��una?');
			$body .= problink('3','7','Af hverju er �g ekki talin(n) sem "Mj�g virkur notandi"?');
		break;
		case '4':
			$title = 'Bo�slyklar';
			$body = problink('4','8','Af hverju hef �g enga bo�slykla?');
		break;
		case '5':
			$title = 'Eftirspurnir';
			$body = problink('5','9','Af hverju get �g ekki lagt inn eftirspurn?');
		break;
		case '6':
			$title = 'Anna�';
			$body = problink('6','13','Hvernig breyti �g um titil?');
			$body .= problink('6','14','Hvernig hef �g umsj�n me� undirskriftinni minni?');
			$body .= problink('6','15','Kerfi� kvartar yfir �v� a� �g s� ekki a� deila...');
		}
		begin_frame($title);
		echo $body;
		end_frame();
	} elseif(isset($cat) && isset($ansid)) {
		switch($ansid) {
			case '1':
				$title = 'Get ekki deilt skr�';
				$body = 'Hefur�u sent torrent skr�na inn � Istorrent?<br />';
				$body .= problink('1','2','J�');
				$body .= problink('1','3','Nei');
			break;
			case '2':
				$title = 'Get ekki deilt skr�';
				$body = '<b>Hef sent inn skr�na</b><br />';
				if(find_unseeded($CURUSER['id']))
					$body .= 'Einhver skr�in sem �� hefur sent inn vantar deilara. N�nar um m�li� er � <a href="/faq.php#85">SOS f�rslu 85</a>.';
				else {
					$body .= 'Istorrent er a� rannsaka hva� �� hefur sent inn...<br />';
					$sql = 'SELECT name FROM torrents WHERE owner='.$CURUSER['id'];
					$res = mysql_query($sql);
					if(mysql_num_rows($res) > '0') {
						$body .= 'Er einhver eftirtalinna skr� s� sem �� ert a� reyna a� deila?<br />';
						while($arr = mysql_fetch_assoc($res)) {
							$body .= $arr['name'].'<br />';
						}
						$body .= problink('1','4','J�');
						$body .= problink('1','5','Nei');
					} else
						$body .= '<br /><b>Engin skr� fr� ��r er inni � kerfinu.</b>';
				}
			break;
			case '3':
				$title = 'Get ekki deilt skr�';
				$body = '<b>Hef ekki sent inn torrent skr�na</b><br /><br />';
				$body .= 'Hafir �� ekki reynt a� senda inn skr�na, vinsamlegast ger�u �a�. Ef �� hefur reynt en ��r tekst �a� ekki, lestu �fram.<br />';
				if(find_unseeded($CURUSER['id']) === '1')
					$body .= '�� getur ekki deilt ef eitthva� sem �� hefur sent inn vantar deilara. Auk �ess getur�u ekki sent inn ef �� ert eina manneskjan sem ert a� deila einhverju "yngra" en 24 klukkustunda. N�nar um lausnir � �essu og uppl�singar eru � <a href="/faq.php#85">SOS f�rslu 85</a>';
				elseif(slots($CURUSER['id'],'free') == '0') {
					$body .= '�� hefur ekki n�g af lausum h�lfum. Vinsamlegast l�ttu � eftirfarandi svar:<br />';
					$body .= problink('1','10','�� hefur ekki n�g h�lf til a�...');
				} else {
					$body .= 'Athuga�u skr�na og hvort a� eitthva� af eftirfarandi passar vi� hana:<br />';
					$body .= '* Skr�arendingin er ekki .torrent<br />';
					$body .= '* .torrent skr�in er st�rri en 10 megab�ti (10.485.760 b�ti)<br />';
					$body .= '* Skr�rnar taldar inni � .torrent skr�nni eru miki� fleiri en ��sund talsins.<br />';
					$body .= 'Passi eitt af fyrstu tveim atri�unum mun skr�in ekki vera tekin vi� af Istorrent. Passi seinasta atri�i� er h�tta � �v� a� kvarta� s� um a� or�ab�k vanti lykla.';
				}
			break;
			case '4':
				$title = 'Get ekki deilt skr�';
				$body = 'Til a� kl�ra a� senda inn, �arf a� n� � skr�na fr� Istorrent og nota hana inn � BitTorrent forritinu en ekki skr�na sem var b�in til.';
			break;
			case '5':
				$title = 'Get ekki deilt skr�';
				// Hef ekki sent inn skr�na - svar
			break;
			case '6':
				$title = 'Af hverju er �g ekki skr��(ur) sem gefandi?';
				if($CURUSER['donor'] === 'yes')
					$body = '�� ert �egar skr��(ur) sem gefandi.';
				else
					$body = 'Hafir �� sent t�lvup�st � torrent@torrent.is me� notandanafni, kennit�lu grei�anda og upph��, �� �tti ums�knin ��n a� vera � afgrei�slu. �a� getur teki� einhverja daga �anga� til ums�knin er afgreidd og �anga� til sta�an er veitt.';
			break;
			case '7':
				$title = 'Af hverju er �g ekki talin(n) sem "Mj�g virkur notandi"?';
				if($CURUSER['class'] > UC_POWER_USER)
					$body = '�eir sem eru stj�rnendur eru ekki taldir sem "Mj�g virkir notendur" �ar sem �eir nj�ta �eirra kjara n� �egar � n�verandi st��u.';
				elseif($CURUSER['class'] == UC_POWER_USER)
					$body = '�� ert n� �egar talin(n) gegna �essari st��u.';
				else {
					$body = 'Kerfi� fer n� yfir skilyr�i til �ess a� vera me� st��una og nefnir hvort �� stenst ��r kr�fur...<br />';
					@$ratio = $CURUSER['uploaded']/$CURUSER['downloaded'];
					$body .= 'Hlutf�ll jafnt og e�a h�rri en 4,00 - ';
					if($ratio >= 4.00)
						$body .= 'Uppfyllt<br />';
					else {
						if(round($ratio,2) === '4.00')
							$body .= '<b>�uppfyllt (Hlutfalli� sem �� s�r� er n�munda� upp � 4.00 svo �a� er stutt � a� �� uppfyllir �� kr�fu)</b><br />';
						else
							$body .= '<b>�uppfyllt</b><br />';
					}
					$t_medlimur = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400*14)));
				        $t_medlimur2 = str_replace(array(' ',':','-'),'',$CURUSER['added']);
					$body .= 'Hafa veri� me�limur Istorrent � 2 vikur e�a lengur - ';
					if($t_medlimur > $t_medlimur2)
						$body .= 'Uppfyllt<br />';
					else
						$body .= '<b>�uppfyllt</b><br />';
					$body .= 'Hafa deilt 100 g�gab�tum e�a meira - ';
					if($CURUSER['uploaded'] >= (100*1024*1024*1024))
						$body .= 'Uppfyllt<br />';
					else
						$body .= '<b>�uppfyllt</b><br />';
					$body .= '<br />Uppfylla �arf �ll �essi skilyr�i til a� f� �essa st��u. Kerfi� l�tur yfir notendur � 15 m�n�tna fresti og veitir �eim st��uh�kkun sem uppfylla skilyr�in svo a� �a� er m�guleiki a� �a� ferli eigi eftir a� fara � gang.<br />';
					$body .= 'Einnig ber a� geta �ess a� hlutfallat�lur sem birtast notendum eru n�munda�ar a� 2 aukast�fum en kerfi� sem veitir st��uh�kkanir gerir �a� ekki.';
				}
			break;
			case '8':
				$title = 'Af hverju hef �g enga bo�slykla?';
				$lyklar = inviteleft($CURUSER['id'],$CURUSER['uploaded'],$CURUSER['downloaded'],$CURUSER['warned'],$CURUSER['added']);
				if($lyklar > '0')
					$body = '�� �tt a� hafa '.$lyklar.' n�tanlega bo�slykla. Sl��in sem �arf a� fara � til a� b�a til bo�slykla er <a href="/invites.php">'.$BASEURL.'/invites.php</a>';
				elseif($CURUSER['class'] < UC_USER && $CURUSER['donor'] === 'no')
					$body = '�� gegnir ekki n�gu h�rri st��u til a� geta gefi� �t bo�slykla.';
				else {
					$body = 'Kerfi� mun reikna �t af hverju �� getur ekki bo�i� inn...<br />';
					if($CURUSER['warned'] === 'yes')
						$body .= '<b>Notendur me� gilda vi�v�run geta ekki b�i� til bo�slykla.</b><br />';
					$t_medlimur = str_replace(array(' ',':','-','\''),'',sqlesc(get_date_time(gmtime() - 86400*14)));
				        $t_medlimur2 = str_replace(array(' ',':','-'),'',$CURUSER['added']);
					if($t_medlimur2 > $t_medlimur)
						$body .= '<b>�� ver�ur a� hafa veri� me�limur Istorrent � 2 vikur e�a lengur til a� geta bo�i� inn.</b><br />';
					else
						$body .= '�� uppfyllir skilyr�i� um a� hafa veri� me�limur Istorrent � 2 vikur e�a lengur.<br />';
					$body .= '�� hefur deilt: '.mksize($CURUSER['uploaded']).'<br />';
					$s = (25*1024*1024*1024);
					$body .= 'Kerfi� mun fyrst draga '.mksize($s).' fr� deilimagninu ��nu � �essum �treikningum og �� kemur � lj�s a� �� hefur '.mksizegb($CURUSER['uploaded']-$s).' eftir �a�.<br />';
					$body .= 'S� talan neikv��, �arftu a� vinna �a� upp en kerfi� l�tur �ig strax f� "0" bo�slykla ef h�n er �a�.<br />';
					if($CURUSER['uploaded']-$s > '0') {
						$lyklar = floor(($CURUSER['uploaded']-$s)/(5*1024*1024*1024));
						$body .= 'S��an er reikna� hve marga bo�slykla �� �ttir a� hafa eftir hver 5 g�gab�ti yfir �au '.mksize($s).' sem �� �arft a� hafa og � ��nu tilviki eru...'.$lyklar.'<br />';
						$sql_inv = 'SELECT COUNT(*) FROM users WHERE invitari = '.$CURUSER['id'].' AND deleted=0';
				                $s2 =  mysql_result(mysql_query($sql_inv),0);
						$body .= 'Fj�ldi notenda sem �� hefur bo�i� og hafa ekki veri� eyddir �t, '.$s2.', eru dregnir fr�<br />';
						$sql_inv2 = 'SELECT COUNT(*) FROM invites WHERE inviter_id='.$CURUSER['id'].' AND used=0';
						$s3 = mysql_result(mysql_query($sql_inv2),0);
						$body .= 'Notendur sem �� hefur b�i� til bo�slykla fyrir og hafa ekki nota� ��: '.$s3.'<br />';
						$body .= '�� �ttir �v� a� hafa '.($lyklar-$s2-$s3).' lykla � lausu. Kerfi� mun h�kka t�luna upp � 0 s� talan neikv�� e�a notandi hefur vi�v�run.';
					} else
						$body .= '�� getur s�� afganginn af �treikningunum eftir a� hafa unni� �etta upp og ef �� ert enn � vandr��um.';
				}
			break;
			case '9':
				$title = 'Af hverju get �g ekki lagt inn eftirspurn?';
				if(($CURUSER['class'] >= UC_GOOD_USER || $CURUSER['donor'] === 'yes') && requests_free($CURUSER['id']) > '0')
					$body = '�� getur �egar lagt inn eftirspurn me� �v� a� fara � tengilinn <a href="/requests.php">'.$BASEURL.'/requests.php</a>';
				elseif($CURUSER['class'] >= UC_GOOD_USER || $CURUSER['donor'] === 'yes')
					$body .= '�� hefur r�ttindin til a� setja inn eftirspurn en �� �arft a� vinna ��r �a� inn. H�gt er a� leggja inn eina eftirspurn fyrir hver 10GB sem �� deilir.';
				else
					$body = 'Eing�ngu �eir sem gegna st��unni "Mj�g virkur notandi" e�a h�rri geta lagt inn eftirspurn. �� ver�ur a� vinna ��r inn �essa st��u ��ur.<br /><br />Leggir �� inn eftirspurn annars sta�ar � Istorrent muntu f� 3ja daga vi�v�run.';
			break;
			case '10':
				$title = '�� hefur ekki n�gan h�lfafj�lda...';
				$body = '�essi skilabo� birtast �egar �� hefur ekki laus h�lf til a� gera eitthva�.<br /><br />';
				$body .= '�egar veri� er a� taka ��tt � torrenti, hvort sem �� ert a� s�kja e�a deila, tekur �a� �kve�inn h�lfafj�lda. �� getur s�� h�lfanotkun ��na � textanum sem er r�tt undir Istorrent merkinu efst til vinstri. �a� sem er � undan / merkinu er h�lfafj�ldinn sem �� ert a� nota og �a� sem er � eftir merkinu er s� h�marksfj�ldi h�lfa sem �� hefur til umr��a.<br /><br />';
				if(slots($CURUSER['id'],'free') > '1')
					$body .= '<b>�� �ttir a� hafa n�gan h�lfafj�lda til a� hefja ni�urhal e�a deilingu. Vinsamlegast pr�fa�u aftur �� a�ger� sem framkv�mdi �essi skilabo�.</b>';
				else
					$body .= '<b>Til a� byrja a� s�kja e�a deila meiru, �arftu a� sl�kkva � einhverju af �v� sem er � gangi.</b>';
			break;
			case '11':
				$title = 'Hva� ���a skilabo�in "�etta torrent tekur ekki h�lf, nj�ttu vel" ?';
				$body = 'H�lfakerfi� tekur eing�ngu torrent sem eru yngri en 48 klst.(2 s�larhringa) sem h�lf. �essi skilabo� ���a a� torrenti� sem �� ert a� sko�a muni ekki teljast sem nota� h�lf.';
			break;
			case '12':
				$title = '"�� ert ekki a� deila inn torrenti sem �� hefur sent inn..." kemur �egar �g reyni a� n� � torrent.';
				$body = '�etta ���ir a� torrent sem �� hefur sent inn ��ur er �n deilanda e�a �� ert ekki a� deila einhverju sem �� hefur sent inn seinustu 24 klukkustundirnar. N�nari uppl�singar eru � <a href="/faq.php#85">SOS f�rslu 85</a>.';
			break;
			case '13':
				$title = 'Hvernig breyti �g um titil?';
				if($CURUSER['class'] >= UC_POWER_USER || $CURUSER['donor'] === 'yes')
					$body = 'Titlum er breytt <a href="/my.php">� pr�f�l</a>';
				else
					$body = '�� �arft a� vera skr��ur gefandi e�a gegna st��unni "Mj�g virkur notandi" e�a ��ri til a� geta breytt um titil';
			break;
			case '14':
				$title = 'Hvernig hef �g umsj�n me� undirskriftinni minni?';
				if($CURUSER['class'] >= UC_USER || $CURUSER['donor'] === 'yes') {
					$body = 'H�gt er a� setja og breyta undirskrift <a href="/my.php">� pr�f�l</a>.<br /><br />';
					$body .= 'Til a� ey�a undirskriftinni er n�g a� t�ma undirskriftarreitinn og framkv�ma breytinguna.';
				} else
					$body = '�� �arft a� vera skr��ur gefandi e�a gegna st��unni "Notandi" e�a ��ri til a� geta sett og breytt �inni eigin undirskrift.';
			break;
			case '15':
				$title = 'Kerfi� kvartar yfir �v� a� �g s� ekki a� deila...';
				$body = 'Kerfi� mun lista h�r ��r skr�r sem �� �tt a� deila en gerir �a� ekki:<br /><br />';
				if(find_unseeded($CURUSER['id']) === '1') {
					$body .= find_unseeded($CURUSER['id'],'list');
					$body .= '��ur en �� getur ni�urhala� e�a sent inn fleiri skr�r �arftu a� breg�ast vi� �eim villum sem eru nefndir fyrir aftan hvert innsent torrent.<br /><br />';
				} else
					$body .= '<b>�� ert a� deila �llu sem �� �tt a� deila �essa stundina.</b>';
				$body .= 'N�nari l�singu � �v� hvernig <a href="/faq.php#85">�essi villubo� virka og m�gulegar lausnir</a> er h�gt a� finna � Spurt og svara�.';
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
