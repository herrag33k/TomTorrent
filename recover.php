<?

require "include/bittorrent.php";

dbconn();

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
  $email = trim($_POST["email"]);
  if (!$email)
    stderr("Villa", "Þú verður að slá inn netfang");
  $res = mysql_query("SELECT * FROM users WHERE email=" . sqlesc($email) . " LIMIT 1") or sqlerr();
  $arr = mysql_fetch_assoc($res) or stderr("Villa", "Netfangið <b>$email</b> fannst ekki í gagnagrunninum.\n");
	if($arr['status'] === 'pending')
		die('Þetta form er til að endurheimta lykilorð aðganga sem eru þegar virkir. Þú kemst ekki á aðganginn því þú hefur ekki staðfest hann með kóðanum sem þú fékkst í tölvupósti. Hafir þú ekki fengið póstinn geturðu fengið hann endursendan á forminu <a href="/reconfirm.php">"Endursenda staðfestingarpóst"</a>.');

	$sec = mksecret();

  mysql_query("UPDATE users SET editsecret=" . sqlesc($sec) . " WHERE id=" . $arr["id"]) or sqlerr();
  if (!mysql_affected_rows())
	  stderr("Villa", "Gagnagrunnsvilla. Gjörðu svo vel að hafa samband við stjórnanda um hana.");

  $hash = md5($sec.$email.$arr['passhash'].$sec);

  $body = <<<EOD
Einhver, vonandi þú, hefur beðið um að lykilorðið fyrir aðganginn
tengdan við þetta netfang ($email) verði endursett.

Beiðnin kom frá {$HTTP_SERVER_VARS["REMOTE_ADDR"]}.

Ef þú baðst ekki um þetta, hunsaðu þennan tölvupóst. Gjörðu svo vel að svara ekki.


Viljir þú staðfesta þessa beiðni, gjörðu svo vel að fara á eftirfarandi slóð:

$DEFAULTBASEURL/recover.php?id={$arr["id"]}&secret=$hash


Eftir að þú hefur gert þetta, mun lykilorðið verða endursett og sent til þín.

--
$SITENAME
EOD;

  mail($arr["email"], "$SITENAME - Staðfesting á endursetningu lykilorðs", $body, "From: $SITEEMAIL\r\nReply-To:$SITEEMAIL")
//, "-f$SITEEMAIL")
    or stderr("Villa", "Gat ekki sent tölvupóst. Gjörðu svo vel að hafa samband við stjórnendur um þessa villu.");
  stderr("Aðgerð tókst", "Staðfesting hefur verið send til <b>$email</b>.\n" .
    "Gjörið svo vel að gera ráð fyrir nokkrum mínútum þangað til hún kemur.");
}
elseif($_GET)
{
//	if (!preg_match(':^/(\d{1,10})/([\w]{32})/(.+)$:', $_SERVER["PATH_INFO"], $matches))
//	  httperr();

//	$id = 0 + $matches[1];
//	$md5 = $matches[2];

	$id = 0 + $_GET["id"];
  $md5 = $_GET["secret"];

	if (!$id) {
	stderr('Villa','ID ekki sett');
	  httperr();
	}

	$res = mysql_query("SELECT username, email, passhash, editsecret FROM users WHERE id = $id");
	$arr = mysql_fetch_array($res) or httperr();

  $email = $arr["email"];

	$sec = hash_pad($arr["editsecret"]);
	if (preg_match('/^ *$/s', $sec)) {
	stderr('Villa','preg_match villa - "'.$sec.'"');
	  httperr();
	}
	if ($md5 != md5($sec.$email.$arr["passhash"].$sec)) {
		stderr('Villa','md5 villa');
		httperr();
	}

	// generate new password;
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

  $newpassword = "";
  for ($i = 0; $i < 10; $i++)
    $newpassword .= $chars[mt_rand(0, strlen($chars) - 1)];

 	$sec = mksecret();

  $newpasshash = md5($sec.$newpassword.$sec);

	mysql_query("UPDATE users SET secret=" . sqlesc($sec) . ", editsecret='', passhash=" . sqlesc($newpasshash) . " WHERE id=$id AND editsecret=" . sqlesc($arr["editsecret"]));

	if (!mysql_affected_rows())
		stderr("Villa", "Get ekki uppfært gögn notanda. Gjörðu svo vel að láta stjórnanda vita.");

  $body = <<<EOD
Samkvæmt beiðni yðar höfum við endursett lykilorðið fyrir aðganginn þinn.

Hér eru upplýsingarnar sem við höfum um þennan aðgang:

    Notandanafn: {$arr["username"]}
    Lykilorð:  $newpassword

Þú getur innskráð þig á $DEFAULTBASEURL/login.php

--
$SITENAME
EOD;
  @mail($email, mb_encode_mimeheader("$SITENAME - Upplýsingar um aðgang"), $body, "From: $SITEEMAIL\r\nReply-To:$SITEEMAIL")
    or stderr("Villa", "Gat ekki sent póst. Gjörðu svo vel að hafa samband við stjórnanda.");
  stderr("Aðgerð tókst", "Nýju aðgangsupplýsingarnar hafa verið sendar til <b>$email</b>.\n" .
    "Gjörið svo vel að leyfa nokkrar mínútur fyrir sendinguna að komast til skila.");
}
else
{
 	stdhead();
	?>
	<h1>Endurvekja tapað notandanafn eða lykilorð</h1>
	<p>Notaðu formið hér að neðan til að endursetja lykilorðið og fá aðgangsupplýsingar sendar til 	þín.<br>
  (Þú verður að ansa staðfestingarpósti.)</p>
	<p><b>Hotmail notendur fá ekki staðfestingarpóst af óskiljanlegum ástæðum.</b><br>
Í þeim tilvikum, sendið annað netfang (sem endar ekki á msn.com eða hotmail.com) á torrent@torrent.is.<br>
Munið að senda póstinn frá netfanginu sem er skráð fyrir aðgangnum.</p>
	<form method=post action=recover.php>
	<table border=1 cellspacing=0 cellpadding=10>
	<tr><td class=rowhead>Skráð netfang</td>
	<td><input type=text size=40 name=email></td></tr>
	<tr><td colspan=2 align=center><input type=submit value='Framkvæma!' class=btn></td></tr>
	</table>
	<?
	stdfoot();
}

?>
