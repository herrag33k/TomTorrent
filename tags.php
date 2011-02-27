<?
require "include/bittorrent.php";
dbconn();

function insert_tag($name, $description, $syntax, $example, $remarks)
{
	$result = format_comment($example);
	echo '<p class="sub"><b>'.$name.'</b></p>'."\n";
	echo '<table class="main" width="100%" border="1" cellspacing="0" cellpadding="5">'."\n";
	echo '<tr valign="top"><td width="25%">Lýsing:</td><td>'.$description."\n";
	echo '<tr valign="top"><td>Kóði:</td><td><tt>'.$syntax.'</tt>'."\n";
	echo '<tr valign="top"><td>Dæmi:</td><td><tt>'.$example.'</tt>'."\n";
	echo '<tr valign="top"><td>Úr því kemur:</td><td>'.$result."\n";
	if ($remarks != '')
		echo '<tr><td>Athugasemdir:</td><td>'.$remarks."\n";
	echo '</table>'."\n";
}

stdhead("BB kóði");
begin_main_frame();
begin_frame("Kóði");
$test = $_POST["test"];
?>
<p>Istorrent spjallborðið styður fjölda <i>BB kóða</i> sem þú getur sett inn á spjallpóstana þína 
til að sníða þá.</p>

<form method="post" action="?">
<textarea name="test" cols="60" rows="3"><? print($test ? htmlspecialchars($test) : "")?></textarea>
<input type=submit value="Prófa þennan kóða!" style='height: 23px; margin-left: 5px'>
</form>
<?

if ($test != "")
  print("<p><hr>" . format_comment($test) . "<hr></p>\n");

insert_tag(
	"Feitletur",
	"Lætur textann á milli verða feitletraðan.",
	"[b]<i>Texti</i>[/b]",
	"[b]Þetta er feitletraður texti.[/b]",
	""
);

insert_tag(
	"Skáletur",
	"Lætur textann á milli verða skáletraðan.",
	"[i]<i>Texti</i>[/i]",
	"[i]Þetta er skráletraður texti.[/i]",
	""
);

insert_tag(
	"Undirstrik",
	"Lætur textann á milli verða undirstrikaðan.",
	"[u]<i>Texti</i>[/u]",
	"[u]Þetta er undirstrikaður texti.[/u]",
	""
);

insert_tag(
	"Miðjun",
	"Lætur textann á milli verða miðjujafnaðan.",
	"<i>[center]Þessi texti er miðjujafnaður[/center]</i>",
	"[center]Þessi texti er miðjujafnaður[/center]",
	""
);


insert_tag(
	"Litur (aðferð 1)",
	"Lætur textann á milli fá ákveðinn lit.",
	"[color=<i>Litur</i>]<i>texti</i>[/color]",
	"[color=blue]Þetta er blár texti.[/color]",
	"Það fer eftir vafranum þínum hvaða litir eru gildir. Þú ættir að vera örugg(ur) með grunnlitina (rauður, grænn, blár, gulur, bleikur og svo framvegis)."
);

insert_tag(
	"Litur (aðferð 2)",
	"Lætur textann á milli fá ákveðinn lit.",
	"[color=#<i>RGB</i>]<i>Texti</i>[/color]",
	"[color=#0000ff]Þetta er blár texti.[/color]",
	'<i>RGB</i> verður að vera sex stafa tala í <a href="http://is.wikipedia.org/wiki/Sextándakerfið" 
target="_new">sextándakerfinu</a>'
);

insert_tag(
	"Stærð",
	"Breytir stærð textans á milli.",
	"[size=<i>n</i>]<i>texti</i>[/size]",
	"[size=4]Þetta er stærð 4.[/size]",
	"<i>n</i> verður að vera tölustafur á milli 1 (minnst) til 7 (stærst). Sjálfgefna stærðin er 2."
);

insert_tag(
	"Leturgerð",
	"Setur leturgerð textans á milli.",
	"[font=<i>Leturgerð</i>]<i>Texti</i>[/font]",
	"[font=Impact]Halló heimur![/font]",
	"Þú getur skilgreint vara-leturgerðir með kommu."
);

insert_tag(
	"Tengill (aðferð 1)",
	"Setur inn tengil.",
	"[url]<i>Slóð</i>[/url]",
	"[url]http://torrent.is/[/url]",
	"Þessi kóði er úreldur; Öllum slóðum er sjálfkrafa umbreytt í tengla."
);

insert_tag(
	"Tengill (aðferð 2)",
	"Setur inn tengil.",
	"[url=<i>Slóð</i>]<i>Texti tengils</i>[/url]",
	"[url=http://torrent.is/]Istorrent[/url]",
	"Þú þarft ekki að nota þennan kóða nema þú viljir hafa texta í tenglinum; Öllum slóðum er sjálfkrafa umbreytt í tengla."
);

insert_tag(
	"Mynd (aðferð 1)",
	"Setur inn mynd.",
	"[img=<i>Slóð</i>]",
	"[img=http://torrent.is/pic/logo2.gif]",
	"Slóðin verður að enda á <b>.gif</b>, <b>.jpg</b> eða <b>.png</b>."
);

insert_tag(
	"Mynd (aðferð 2)",
	"Setur inn mynd.",
	"[img]<i>Slóð</i>[/img]",
	"[img]http://torrent.is/pic/logo2.gif[/img]",
	"Slóðin verður að enda á <b>.gif</b>, <b>.jpg</b> eða <b>.png</b>."
);

insert_tag(
	"Tilvitnun (aðferð 1)",
	"Setur inn tilvitnun.",
	"[quote]<i>Texti sem er vitnað í</i>[/quote]",
	"[quote]Ég ætla að vera borgarstjóri næstu fjögur árin.[/quote]",
	""
);

insert_tag(
	"Tilvitnun (aðferð 2)",
	"Setur inn tilvitnun.",
	"[quote=<i>Höfundur</i>]<i>Texti sem er vitnað í</i>[/quote]",
	"[quote=Ingibjörg Sólrún Gísladóttir]Ég ætla að vera borgarstjóri næstu fjögur árin.[/quote]",
	"(Hún hætti sem borgarstjóri næsta árið)"
);

insert_tag(
	"Listar",
	"Setur inn atriðalista.",
	"[*]<i>Texti</i>",
	"[*] Þetta er atriði 1\n[*] Þetta er atriði 2",
	""
);

insert_tag(
	"Þverlína",
	"Setur inn þverlínu.",
	"<i>[hr]</i>",
	"[hr]",
	""
);

insert_tag(
	"Forsniðinn texti",
	"Forsniðinn (monospace) texti. Er ekki skipt sjálfkrafa milli lína.",
	"[pre]<i>Texti</i>[/pre]",
	"[pre]Þessi texti hefur verið forsniðinn.[/pre]",
	""
);

end_frame();
end_main_frame();
stdfoot();
?>
