<?

require_once("include/bittorrent.php");
hit_start();

dbconn(false);

hit_count();

loggedinorreturn();

stdhead("Deila skrá");
if($CURUSER['class'] == UC_BEGINNER && $CURUSER['donor'] !== 'yes')
	exit ("Þeir sem gegna stöðunni Byrjandi geta ekki sent inn torrent. Þér er frjálst að sækja torrent og taka þátt í deilingunni.<br /> Það sem þú þarft að gera er að sækja eitthvað torrent héðan af Istorrent og halda því virku eftir að þú hefur náð í það.");
if(slots($CURUSER['id'],'free') < '1')
	exit("Þú hefur ekki nóg af lausum hólfum til að búa til torrent.");
if(find_unseeded($CURUSER['id']) === '1')
	exit('Þú mátt ekki senda inn torrent skrá á þessari stundu. Annað hvort er eitthvað af því sem þú hefur sent inn án deilanda eða þú ert eini deilandinn á torrenti sem þú hefur sent inn seinustu 24 klukkustundirnar.');

begin_frame("Athugið");
echo '<h5 align=center>Deilið aðeins því sem þið haldið að aðrir hafi áhuga á.</h5>';
echo '<h5 style="color:red;text-align:center">Torrent skránni sem þú munt senda inn verður breytt, náðu í hana aftur frá Istorrent og notaðu það eintak í staðinn fyrir það sem þú sendir inn.</h5>';
echo '<h5 style="color:red;text-align:center">Athugaðu hvort að efnið sem þú ætlar að senda inn sé þegar hérna inni. Ef það er, taktu þátt í að deila því frekar en að stofna nýtt!</h5>';
echo '<h5 style="color:red;text-align:center">Þú berð ábyrgð á því að torrentið þitt sé á lífi og hafi einhvern deilanda út líftíma þess á Istorrent (28 dagar).</h5>';
end_frame();
?>
<div align=Center>
<form enctype="multipart/form-data" action="takeupload.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_torrent_size?>" />
<p>Tilkynningarslóðin er <b><?= $announce_urls[0] ?></b> (passaðu að afrita ekki með bilin í kringum slóðina)</p>
<table border="1" cellspacing="0" cellpadding="10">
<?

tr("Torrent skrá", "<input type=file name=file size=80>\n <br /> Sum torrent eru alltaf bönnuð <b>undir öllum kringumstæðum</b> á Istorrent.<br />Lista yfir þær tegundir er viðhaldið á <a href=\"http://torrent.is/forums.php?action=viewtopic&topicid=7670\">viðeigandi spjallþræði</a> á spjallborðinu", 1);
tr("Nafn", "<input type=\"text\" name=\"name\" size=\"80\" /><br />(Ef tómt þá er það sama og nafn á torrent skrá. <b>Notið lýsandi nöfn.</b>)\n<br />ATH: Ekki nota *reseed*, *eftirspurn* eða svoleiðis texta í nafninu\n", 1);
if($CURUSER['class'] >= UC_GOOD_USER || $CURUSER['donor'] === 'yes')
	tr("Senda nafnlaust", "<input type=\"checkbox\" name=\"anonymous\" value=\"1\">Senda torrent inn nafnlaust.<br /> 
	Sé hakað í kassann munu eingöngu stjórnendur sjá notandanafn innsendanda.<br />", 1);
tr("NFO skrá (Bara ef þú átt hana)", "<input type=file name=nfo size=80><br>\n", 1);
tr("Lýsing (verið nákvæm)", "Góðar lýsingar geta leitt af sér miklar vinsældir og þarafleiðandi
góða möguleika á myndarlegri hækkun á hlutfalli<br /><textarea 
name=\"descr\" rows=\"10\" cols=\"80\"></textarea>" .
  "<br>(HTML er <b>ekki</b> leyft).", 1);

$s = "<select name=\"type\">\n<option value=\"0\">(Veljið)</option>\n";

$cats = genrelist();
foreach ($cats as $row)
	$s .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$s .= "</select>\n";
tr("Aldur","<input type=radio name=gamalt value=no>Nýtt (Gefið út innan 14 daga)<br><input 
type=radio name=gamalt checked=checked value=yes>Gamalt (Eldra en 14 daga)<br />Merki notendur að 
skrá sé ný þegar hún er gömul, eiga þeir í hættu á að fá viðvörun með viðeigandi refsingu.",1); 

tr("Scene útgáfa","Scene útgáfur eru útgáfur sem fylgja föstum formerkjum hvað varðar pökkun 
og merkingu efnis.<br />Ef þú veist ekki hvað \"Scene útgáfa\" er, ekki merkja að hún sé það.<br />
<input type=\"radio\" name=scene value=\"y\">\"Scene útgáfa\"<br>
<input type=radio name=scene checked=\"checked\" value=\"n\">Ekki \"scene útgáfa\".",1); 
tr("Flokkur", $s, 1); ?>
<tr><td align="center" colspan="2"><input type="submit" class=btn value="Senda inn!" /></td></tr>
</table>
</form>
<?

stdfoot();

hit_end();

?>
