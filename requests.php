<?
ob_start("ob_gzhandler");
require "include/bittorrent.php";

dbconn();

loggedinorreturn();

stdhead("Bei�niss��a");

if (get_user_class() < UC_GOOD_USER)
{
	print("<h1>Fyrirgef�u</h1><p>�� ver�ur a� gegna st��unni virkur notandi e�a betri, sj��u <a href=/faq.php#23><b>SOS</b></a> fyrir uppl�singar um st��ur. A� gefnu tilefni er banna� a� leggja inn eftirspurnir annars sta�ar � Istorrent vefnum.</p>");
	die();
}
if(requests_free($CURUSER['id']) > '0') {
	echo '<h1>Leggja inn bei�ni</h1>';
	echo 'Til a� sko�a bei�nir, <a href="/viewrequests.php">klikka�u h�r</a><br />'."\n";

	/*

	$res = mysql_query("SELECT users.username, requests.id, requests.userid, requests.request, requests.added, categories.name as cat FROM requests inner join categories on requests.cat = categories.id inner join users on requests.userid = users.id order by requests.id desc LIMIT 10") or sqlerr();
	$num = mysql_num_rows($res);

	print("<table border=1 width=800 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead align=left width=400>Be�i� um</td><td class=colhead align=center width=100>Flokkur</td><td class=colhead align=center>B�tt vi�</td><td class=colhead align=center width=100>Be�i� af</td></tr>\n");
	for ($i = 0; $i < $num; ++$i)
	{
		$arr = mysql_fetch_assoc($res);
	{

	$addedby = "<td style='padding: 0px' align=center><b><a href=userdetails.php?id=$arr[userid]>$arr[username]</a></b></td>";
	}

	print("<tr><td align=left><a href=reqdetails.php?id=$arr[id]><b>$arr[request]</b></a></td><td align=left>$arr[cat]</td>" . "<td align=center>$arr[added]</td>"."$addedby</tr>\n");
	}
	print("<tr><td align=center colspan=4><form method=\"get\" action=viewrequests.php><input type=\"submit\" value=\"S�na allt\" style='height: 22px' /></form></td></tr>\n");
	print("</table>\n");

	*/

	print("<br>\n");

	$where = "WHERE userid = " . $CURUSER["id"] . "";
	$res2 = mysql_query("SELECT * FROM requests $where") or sqlerr();
	$num2 = mysql_num_rows($res2);

	/*print("<table border=1 width=800 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead align=left width=400>��nar bei�nir</td><td class=colhead align=center>Flokkur</td><td class=colhead align=center>B�tt vi�</td></tr>\n");
	for ($i = 0; $i < $num; ++$i)
	{
	$arr = mysql_fetch_assoc($res2);

	print("<tr><td align=left><b>$arr[request]</b></td><td align=left>$arr[cat]</td>" .
	"<td align=center>$arr[added]</td>".
	"</tr>\n");
	}
	print("</table>");

	print("<br>\n");
	*/
?>



	<table border=1 width=800 cellspacing=0 cellpadding=5>
	<tr><td class=colhead align=left>Leita a� torrentum (t.d. a� torrentinu ��ur en �� leggur inn bei�ni)</td></tr>
	<tr><td align=left><form method="get" action=browse.php>
	<input type="text" name="search" size="40" value="<?= htmlspecialchars($searchstr) ?>" />in
	<select name="cat">
	<option value="0">(allir flokkar)</option>
<?


	$cats = genrelist();
	$catdropdown = "";
	foreach ($cats as $cat) {
	$catdropdown .= "<option value=\"" . $cat["id"] . "\"";
	if ($cat["id"] == $_GET["cat"])
	$catdropdown .= " selected=\"selected\"";
	$catdropdown .= ">" . htmlspecialchars($cat["name"]) . "</option>\n";
	}

	$deadchkbox = "<input type=\"checkbox\" name=\"incldead\" value=\"1\"";
	if ($_GET["incldead"])
		$deadchkbox .= " checked=\"checked\"";
	$deadchkbox .= " /> innihalda dau� torrent\n";

?>
	<?= $catdropdown ?>
	</select>
	<?= $deadchkbox ?>
	<input type="submit" value="Leita!" style='height: 18px' />
	</form>
	</td></tr></table>

	<? print("<br>\n");

	print("<form method=post action=takerequest.php><a name=add id=add></a>\n");
	print("<table border=1 width=800 cellspacing=0 cellpadding=5>\n");
	print("<tr><td align=center><b>Heiti skr�ar e�a �tg�fu: </b><input type=text size=40 name=requestartist>");
	/*print("<b> Titill: </b><input type=text size=40 name=requesttitle>");*/
?>

	<select name="category">
	<option value="0">(Velja flokk)</option>
<?

	$res2 = mysql_query("SELECT id, name FROM categories order by name");
	$num = mysql_num_rows($res2);
	$catdropdown2 = "";
	for ($i = 0; $i < $num; ++$i)
	{
		$cats2 = mysql_fetch_assoc($res2);
		$catdropdown2 .= "<option value=\"" . $cats2["id"] . "\"";
		$catdropdown2 .= ">" . htmlspecialchars($cats2["name"]) . "</option>\n";
	}

?>
<?= $catdropdown2 ?>
	</select>

	<? print("<br>\n");

	echo '<tr><td align="center">N�nari uppl�singar (ekki skylda)<br><textarea name="descr" rows="5" cols="100"></textarea>'."\n";
	echo '<tr><td align="center">��ur en �� �tir � takkann, <a href="/faq.php#76" target="_blank">athuga�u hva� SOS nefnir um a� leggja inn eftirspurn</a> <input type="submit" value="Framkv�ma!" style="height: 22px">'."\n";
	echo '</form>'."\n";
	echo '</table>'."\n";
} else {
	echo '�� f�r� eina eftirspurn fyrir hver 10 g�gab�ti sem �� hefur deilt. �� �tt enga eftirspurn inni.<br />';
	echo 'N�nari uppl�singar er h�gt a� f� � <a href="/faq.php#84">SOS f�rslu 84</a>';
}
stdfoot();
die;

?>
