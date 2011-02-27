<?
require "include/bittorrent.php";
dbconn();

function insert_tag($name, $description, $syntax, $example, $remarks)
{
	$result = format_comment($example);
	echo '<p class="sub"><b>'.$name.'</b></p>'."\n";
	echo '<table class="main" width="100%" border="1" cellspacing="0" cellpadding="5">'."\n";
	echo '<tr valign="top"><td width="25%">L�sing:</td><td>'.$description."\n";
	echo '<tr valign="top"><td>K��i:</td><td><tt>'.$syntax.'</tt>'."\n";
	echo '<tr valign="top"><td>D�mi:</td><td><tt>'.$example.'</tt>'."\n";
	echo '<tr valign="top"><td>�r �v� kemur:</td><td>'.$result."\n";
	if ($remarks != '')
		echo '<tr><td>Athugasemdir:</td><td>'.$remarks."\n";
	echo '</table>'."\n";
}

stdhead("BB k��i");
begin_main_frame();
begin_frame("K��i");
$test = $_POST["test"];
?>
<p>Istorrent spjallbor�i� sty�ur fj�lda <i>BB k��a</i> sem �� getur sett inn � spjallp�stana ��na 
til a� sn��a ��.</p>

<form method="post" action="?">
<textarea name="test" cols="60" rows="3"><? print($test ? htmlspecialchars($test) : "")?></textarea>
<input type=submit value="Pr�fa �ennan k��a!" style='height: 23px; margin-left: 5px'>
</form>
<?

if ($test != "")
  print("<p><hr>" . format_comment($test) . "<hr></p>\n");

insert_tag(
	"Feitletur",
	"L�tur textann � milli ver�a feitletra�an.",
	"[b]<i>Texti</i>[/b]",
	"[b]�etta er feitletra�ur texti.[/b]",
	""
);

insert_tag(
	"Sk�letur",
	"L�tur textann � milli ver�a sk�letra�an.",
	"[i]<i>Texti</i>[/i]",
	"[i]�etta er skr�letra�ur texti.[/i]",
	""
);

insert_tag(
	"Undirstrik",
	"L�tur textann � milli ver�a undirstrika�an.",
	"[u]<i>Texti</i>[/u]",
	"[u]�etta er undirstrika�ur texti.[/u]",
	""
);

insert_tag(
	"Mi�jun",
	"L�tur textann � milli ver�a mi�jujafna�an.",
	"<i>[center]�essi texti er mi�jujafna�ur[/center]</i>",
	"[center]�essi texti er mi�jujafna�ur[/center]",
	""
);


insert_tag(
	"Litur (a�fer� 1)",
	"L�tur textann � milli f� �kve�inn lit.",
	"[color=<i>Litur</i>]<i>texti</i>[/color]",
	"[color=blue]�etta er bl�r texti.[/color]",
	"�a� fer eftir vafranum ��num hva�a litir eru gildir. �� �ttir a� vera �rugg(ur) me� grunnlitina (rau�ur, gr�nn, bl�r, gulur, bleikur og svo framvegis)."
);

insert_tag(
	"Litur (a�fer� 2)",
	"L�tur textann � milli f� �kve�inn lit.",
	"[color=#<i>RGB</i>]<i>Texti</i>[/color]",
	"[color=#0000ff]�etta er bl�r texti.[/color]",
	'<i>RGB</i> ver�ur a� vera sex stafa tala � <a href="http://is.wikipedia.org/wiki/Sext�ndakerfi�" 
target="_new">sext�ndakerfinu</a>'
);

insert_tag(
	"St�r�",
	"Breytir st�r� textans � milli.",
	"[size=<i>n</i>]<i>texti</i>[/size]",
	"[size=4]�etta er st�r� 4.[/size]",
	"<i>n</i> ver�ur a� vera t�lustafur � milli 1 (minnst) til 7 (st�rst). Sj�lfgefna st�r�in er 2."
);

insert_tag(
	"Leturger�",
	"Setur leturger� textans � milli.",
	"[font=<i>Leturger�</i>]<i>Texti</i>[/font]",
	"[font=Impact]Hall� heimur![/font]",
	"�� getur skilgreint vara-leturger�ir me� kommu."
);

insert_tag(
	"Tengill (a�fer� 1)",
	"Setur inn tengil.",
	"[url]<i>Sl��</i>[/url]",
	"[url]http://torrent.is/[/url]",
	"�essi k��i er �reldur; �llum sl��um er sj�lfkrafa umbreytt � tengla."
);

insert_tag(
	"Tengill (a�fer� 2)",
	"Setur inn tengil.",
	"[url=<i>Sl��</i>]<i>Texti tengils</i>[/url]",
	"[url=http://torrent.is/]Istorrent[/url]",
	"�� �arft ekki a� nota �ennan k��a nema �� viljir hafa texta � tenglinum; �llum sl��um er sj�lfkrafa umbreytt � tengla."
);

insert_tag(
	"Mynd (a�fer� 1)",
	"Setur inn mynd.",
	"[img=<i>Sl��</i>]",
	"[img=http://torrent.is/pic/logo2.gif]",
	"Sl��in ver�ur a� enda � <b>.gif</b>, <b>.jpg</b> e�a <b>.png</b>."
);

insert_tag(
	"Mynd (a�fer� 2)",
	"Setur inn mynd.",
	"[img]<i>Sl��</i>[/img]",
	"[img]http://torrent.is/pic/logo2.gif[/img]",
	"Sl��in ver�ur a� enda � <b>.gif</b>, <b>.jpg</b> e�a <b>.png</b>."
);

insert_tag(
	"Tilvitnun (a�fer� 1)",
	"Setur inn tilvitnun.",
	"[quote]<i>Texti sem er vitna� �</i>[/quote]",
	"[quote]�g �tla a� vera borgarstj�ri n�stu fj�gur �rin.[/quote]",
	""
);

insert_tag(
	"Tilvitnun (a�fer� 2)",
	"Setur inn tilvitnun.",
	"[quote=<i>H�fundur</i>]<i>Texti sem er vitna� �</i>[/quote]",
	"[quote=Ingibj�rg S�lr�n G�slad�ttir]�g �tla a� vera borgarstj�ri n�stu fj�gur �rin.[/quote]",
	"(H�n h�tti sem borgarstj�ri n�sta �ri�)"
);

insert_tag(
	"Listar",
	"Setur inn atri�alista.",
	"[*]<i>Texti</i>",
	"[*] �etta er atri�i 1\n[*] �etta er atri�i 2",
	""
);

insert_tag(
	"�verl�na",
	"Setur inn �verl�nu.",
	"<i>[hr]</i>",
	"[hr]",
	""
);

insert_tag(
	"Forsni�inn texti",
	"Forsni�inn (monospace) texti. Er ekki skipt sj�lfkrafa milli l�na.",
	"[pre]<i>Texti</i>[/pre]",
	"[pre]�essi texti hefur veri� forsni�inn.[/pre]",
	""
);

end_frame();
end_main_frame();
stdfoot();
?>
