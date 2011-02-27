<?

require_once("include/bittorrent.php");

hit_start();

dbconn();

hit_count();

if (!mkglobal("type"))
	die();

if ($type == "signup" && mkglobal("email")) {
	stdhead("Skráning notanda");
        stdmsg("Skráning tókst!",
	"Það hefur verið sendur póstur á netfangið sem þú skráðir, fylgið hlekknum sem þar er til að virkja aðganginn.");
	stdfoot();
}
elseif ($type == "confirmed") {
	stdhead("Aðgangur nú þegar virkur");
	print("<h1>Aðgangur nú þegar virkur</h1>\n");
	print("<p>Þessi aðgangur hefur nú þegar verið virkur, þú getur skráð þig inn á <a href=\"login.php\">innskráningarsíðunni</a></p>\n");
	stdfoot();
}
elseif ($type == "confirm") {
	if (isset($CURUSER)) {
		stdhead("Virkjun aðgangs");
		print("<h1>Virkjun á aðgangi þínum tókst!</h1>\n");
		print("<p>Aðgangur þinn hefur verið gerður virkur! Þú varst skráður inn sjálfkrafa. Þú getur nú farið á <a href=\"/\"><b>aðalsíðuna</b></a> og byrjað að nota aðganginn þinn.</p>\n");
		print("<p>Áður en þú notar Istorrent ber þér skylda að lesa <a href=\"rules.php\"><b>reglurnar</b></a> og <a href=\"faq.php\"><b>SOS</b></a>.</p>\n");
		stdfoot();
	}
	else {
		stdhead("Virkjun aðgangs");
		print("<h1>Virkjun á aðgangi þínum tókst</h1>\n");
		print("<p>Aðgangur þinn hefur verið gerður virkur! Hinsvegar var ekki hægt að skrá þig inn sjálfkrafa. Hugsanlegt er að þú sért með kökur óvirkar. Þú verður að nota kökur til að <a href=\"login.php\">skrá þig inn</a>.</p>\n");
		stdfoot();
	}
}
else
	die();

hit_end();

?>
