<?

require_once("include/bittorrent.php");

hit_start();

dbconn();

hit_count();

if (!mkglobal("type"))
	die();

if ($type == "signup" && mkglobal("email")) {
	stdhead("Skr�ning notanda");
        stdmsg("Skr�ning t�kst!",
	"�a� hefur veri� sendur p�stur � netfangi� sem �� skr��ir, fylgi� hlekknum sem �ar er til a� virkja a�ganginn.");
	stdfoot();
}
elseif ($type == "confirmed") {
	stdhead("A�gangur n� �egar virkur");
	print("<h1>A�gangur n� �egar virkur</h1>\n");
	print("<p>�essi a�gangur hefur n� �egar veri� virkur, �� getur skr�� �ig inn � <a href=\"login.php\">innskr�ningars��unni</a></p>\n");
	stdfoot();
}
elseif ($type == "confirm") {
	if (isset($CURUSER)) {
		stdhead("Virkjun a�gangs");
		print("<h1>Virkjun � a�gangi ��num t�kst!</h1>\n");
		print("<p>A�gangur �inn hefur veri� ger�ur virkur! �� varst skr��ur inn sj�lfkrafa. �� getur n� fari� � <a href=\"/\"><b>a�als��una</b></a> og byrja� a� nota a�ganginn �inn.</p>\n");
		print("<p>��ur en �� notar Istorrent ber ��r skylda a� lesa <a href=\"rules.php\"><b>reglurnar</b></a> og <a href=\"faq.php\"><b>SOS</b></a>.</p>\n");
		stdfoot();
	}
	else {
		stdhead("Virkjun a�gangs");
		print("<h1>Virkjun � a�gangi ��num t�kst</h1>\n");
		print("<p>A�gangur �inn hefur veri� ger�ur virkur! Hinsvegar var ekki h�gt a� skr� �ig inn sj�lfkrafa. Hugsanlegt er a� �� s�rt me� k�kur �virkar. �� ver�ur a� nota k�kur til a� <a href=\"login.php\">skr� �ig inn</a>.</p>\n");
		stdfoot();
	}
}
else
	die();

hit_end();

?>
