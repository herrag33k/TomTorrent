<?

require "include/bittorrent.php";

dbconn();

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
  $email = trim($_POST["email"]);
  if (!$email)
    stderr("Villa", "�� ver�ur a� sl� inn netfang");
  $res = mysql_query("SELECT * FROM users WHERE email=" . sqlesc($email) . " LIMIT 1") or sqlerr();
  $arr = mysql_fetch_assoc($res) or stderr("Villa", "Netfangi� <b>$email</b> fannst ekki � gagnagrunninum.\n");
	if($arr['status'] === 'pending')
		die('�etta form er til a� endurheimta lykilor� a�ganga sem eru �egar virkir. �� kemst ekki � a�ganginn �v� �� hefur ekki sta�fest hann me� k��anum sem �� f�kkst � t�lvup�sti. Hafir �� ekki fengi� p�stinn getur�u fengi� hann endursendan � forminu <a href="/reconfirm.php">"Endursenda sta�festingarp�st"</a>.');

	$sec = mksecret();

  mysql_query("UPDATE users SET editsecret=" . sqlesc($sec) . " WHERE id=" . $arr["id"]) or sqlerr();
  if (!mysql_affected_rows())
	  stderr("Villa", "Gagnagrunnsvilla. Gj�r�u svo vel a� hafa samband vi� stj�rnanda um hana.");

  $hash = md5($sec.$email.$arr['passhash'].$sec);

  $body = <<<EOD
Einhver, vonandi ��, hefur be�i� um a� lykilor�i� fyrir a�ganginn
tengdan vi� �etta netfang ($email) ver�i endursett.

Bei�nin kom fr� {$HTTP_SERVER_VARS["REMOTE_ADDR"]}.

Ef �� ba�st ekki um �etta, hunsa�u �ennan t�lvup�st. Gj�r�u svo vel a� svara ekki.


Viljir �� sta�festa �essa bei�ni, gj�r�u svo vel a� fara � eftirfarandi sl��:

$DEFAULTBASEURL/recover.php?id={$arr["id"]}&secret=$hash


Eftir a� �� hefur gert �etta, mun lykilor�i� ver�a endursett og sent til ��n.

--
$SITENAME
EOD;

  mail($arr["email"], "$SITENAME - Sta�festing � endursetningu lykilor�s", $body, "From: $SITEEMAIL\r\nReply-To:$SITEEMAIL")
//, "-f$SITEEMAIL")
    or stderr("Villa", "Gat ekki sent t�lvup�st. Gj�r�u svo vel a� hafa samband vi� stj�rnendur um �essa villu.");
  stderr("A�ger� t�kst", "Sta�festing hefur veri� send til <b>$email</b>.\n" .
    "Gj�ri� svo vel a� gera r�� fyrir nokkrum m�n�tum �anga� til h�n kemur.");
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
		stderr("Villa", "Get ekki uppf�rt g�gn notanda. Gj�r�u svo vel a� l�ta stj�rnanda vita.");

  $body = <<<EOD
Samkv�mt bei�ni y�ar h�fum vi� endursett lykilor�i� fyrir a�ganginn �inn.

H�r eru uppl�singarnar sem vi� h�fum um �ennan a�gang:

    Notandanafn: {$arr["username"]}
    Lykilor�:  $newpassword

�� getur innskr�� �ig � $DEFAULTBASEURL/login.php

--
$SITENAME
EOD;
  @mail($email, mb_encode_mimeheader("$SITENAME - Uppl�singar um a�gang"), $body, "From: $SITEEMAIL\r\nReply-To:$SITEEMAIL")
    or stderr("Villa", "Gat ekki sent p�st. Gj�r�u svo vel a� hafa samband vi� stj�rnanda.");
  stderr("A�ger� t�kst", "N�ju a�gangsuppl�singarnar hafa veri� sendar til <b>$email</b>.\n" .
    "Gj�ri� svo vel a� leyfa nokkrar m�n�tur fyrir sendinguna a� komast til skila.");
}
else
{
 	stdhead();
	?>
	<h1>Endurvekja tapa� notandanafn e�a lykilor�</h1>
	<p>Nota�u formi� h�r a� ne�an til a� endursetja lykilor�i� og f� a�gangsuppl�singar sendar til 	��n.<br>
  (�� ver�ur a� ansa sta�festingarp�sti.)</p>
	<p><b>Hotmail notendur f� ekki sta�festingarp�st af �skiljanlegum �st��um.</b><br>
� �eim tilvikum, sendi� anna� netfang (sem endar ekki � msn.com e�a hotmail.com) � torrent@torrent.is.<br>
Muni� a� senda p�stinn fr� netfanginu sem er skr�� fyrir a�gangnum.</p>
	<form method=post action=recover.php>
	<table border=1 cellspacing=0 cellpadding=10>
	<tr><td class=rowhead>Skr�� netfang</td>
	<td><input type=text size=40 name=email></td></tr>
	<tr><td colspan=2 align=center><input type=submit value='Framkv�ma!' class=btn></td></tr>
	</table>
	<?
	stdfoot();
}

?>
