<?

require_once("include/bittorrent.php");
hit_start();

dbconn(false);

hit_count();

loggedinorreturn();

stdhead("Deila skr�");
if($CURUSER['class'] == UC_BEGINNER && $CURUSER['donor'] !== 'yes')
	exit ("�eir sem gegna st��unni Byrjandi geta ekki sent inn torrent. ��r er frj�lst a� s�kja torrent og taka ��tt � deilingunni.<br /> �a� sem �� �arft a� gera er a� s�kja eitthva� torrent h��an af Istorrent og halda �v� virku eftir a� �� hefur n�� � �a�.");
if(slots($CURUSER['id'],'free') < '1')
	exit("�� hefur ekki n�g af lausum h�lfum til a� b�a til torrent.");
if(find_unseeded($CURUSER['id']) === '1')
	exit('�� m�tt ekki senda inn torrent skr� � �essari stundu. Anna� hvort er eitthva� af �v� sem �� hefur sent inn �n deilanda e�a �� ert eini deilandinn � torrenti sem �� hefur sent inn seinustu 24 klukkustundirnar.');

begin_frame("Athugi�");
echo '<h5 align=center>Deili� a�eins �v� sem �i� haldi� a� a�rir hafi �huga �.</h5>';
echo '<h5 style="color:red;text-align:center">Torrent skr�nni sem �� munt senda inn ver�ur breytt, n��u � hana aftur fr� Istorrent og nota�u �a� eintak � sta�inn fyrir �a� sem �� sendir inn.</h5>';
echo '<h5 style="color:red;text-align:center">Athuga�u hvort a� efni� sem �� �tlar a� senda inn s� �egar h�rna inni. Ef �a� er, taktu ��tt � a� deila �v� frekar en a� stofna n�tt!</h5>';
echo '<h5 style="color:red;text-align:center">�� ber� �byrg� � �v� a� torrenti� �itt s� � l�fi og hafi einhvern deilanda �t l�ft�ma �ess � Istorrent (28 dagar).</h5>';
end_frame();
?>
<div align=Center>
<form enctype="multipart/form-data" action="takeupload.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_torrent_size?>" />
<p>Tilkynningarsl��in er <b><?= $announce_urls[0] ?></b> (passa�u a� afrita ekki me� bilin � kringum sl��ina)</p>
<table border="1" cellspacing="0" cellpadding="10">
<?

tr("Torrent skr�", "<input type=file name=file size=80>\n <br /> Sum torrent eru alltaf b�nnu� <b>undir �llum kringumst��um</b> � Istorrent.<br />Lista yfir ��r tegundir er vi�haldi� � <a href=\"http://torrent.is/forums.php?action=viewtopic&topicid=7670\">vi�eigandi spjall�r��i</a> � spjallbor�inu", 1);
tr("Nafn", "<input type=\"text\" name=\"name\" size=\"80\" /><br />(Ef t�mt �� er �a� sama og nafn � torrent skr�. <b>Noti� l�sandi n�fn.</b>)\n<br />ATH: Ekki nota *reseed*, *eftirspurn* e�a svolei�is texta � nafninu\n", 1);
if($CURUSER['class'] >= UC_GOOD_USER || $CURUSER['donor'] === 'yes')
	tr("Senda nafnlaust", "<input type=\"checkbox\" name=\"anonymous\" value=\"1\">Senda torrent inn nafnlaust.<br /> 
	S� haka� � kassann munu eing�ngu stj�rnendur sj� notandanafn innsendanda.<br />", 1);
tr("NFO skr� (Bara ef �� �tt hana)", "<input type=file name=nfo size=80><br>\n", 1);
tr("L�sing (veri� n�kv�m)", "G��ar l�singar geta leitt af s�r miklar vins�ldir og �araflei�andi
g��a m�guleika � myndarlegri h�kkun � hlutfalli<br /><textarea 
name=\"descr\" rows=\"10\" cols=\"80\"></textarea>" .
  "<br>(HTML er <b>ekki</b> leyft).", 1);

$s = "<select name=\"type\">\n<option value=\"0\">(Velji�)</option>\n";

$cats = genrelist();
foreach ($cats as $row)
	$s .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$s .= "</select>\n";
tr("Aldur","<input type=radio name=gamalt value=no>N�tt (Gefi� �t innan 14 daga)<br><input 
type=radio name=gamalt checked=checked value=yes>Gamalt (Eldra en 14 daga)<br />Merki notendur a� 
skr� s� n� �egar h�n er g�mul, eiga �eir � h�ttu � a� f� vi�v�run me� vi�eigandi refsingu.",1); 

tr("Scene �tg�fa","Scene �tg�fur eru �tg�fur sem fylgja f�stum formerkjum hva� var�ar p�kkun 
og merkingu efnis.<br />Ef �� veist ekki hva� \"Scene �tg�fa\" er, ekki merkja a� h�n s� �a�.<br />
<input type=\"radio\" name=scene value=\"y\">\"Scene �tg�fa\"<br>
<input type=radio name=scene checked=\"checked\" value=\"n\">Ekki \"scene �tg�fa\".",1); 
tr("Flokkur", $s, 1); ?>
<tr><td align="center" colspan="2"><input type="submit" class=btn value="Senda inn!" /></td></tr>
</table>
</form>
<?

stdfoot();

hit_end();

?>
