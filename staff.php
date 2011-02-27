<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
stdhead("Stjórnendur");
begin_main_frame();
begin_frame("");
?>


<?
$act = $_GET["act"];
if (!$act) {
// Get current datetime
$dt = gmtime() - 60;
$dt = sqlesc(get_date_time($dt));
// Search User Database for Moderators and above and display in alphabetical order
$res = mysql_query("SELECT * FROM users WHERE class>=".UC_MODERATOR.
" AND status='confirmed' ORDER BY username" ) or sqlerr();

while ($arr = mysql_fetch_assoc($res))
{

$staff_table[$arr['class']]=$staff_table[$arr['class']].
"<td class=embedded><a class=altlink href=userdetails.php?id=".$arr['id'].">".
$arr['username']."</a></td><td class=embedded> ".("'".$arr['last_access']."'">$dt?"<img src=".$pic_base_url."online.gif border=0 alt=\"online\">":"<img src=".$pic_base_url."offline.gif border=0 alt=\"offline\">" )."</td>".
"<td class=embedded><a href=sendmessage.php?receiver=".$arr['id'].">".
"<img src=".$pic_base_url."button_pm.gif border=0></a></td>".
" ";



// Show 3 staff per row, separated by an empty column
++ $col[$arr['class']];
if ($col[$arr['class']]<=2)
$staff_table[$arr['class']]=$staff_table[$arr['class']]."<td class=embedded>&nbsp;</td>";
else
{
$staff_table[$arr['class']]=$staff_table[$arr['class']]."</tr><tr height=15>";
$col[$arr['class']]=0;
}
}
begin_frame("Stjórnendur");
if($CURUSER['class'] >= UC_MODERATOR) {
?>

<table width=725 cellspacing=0>
<tr>
<!-- Define table column widths -->
<td class=embedded width="125">&nbsp;</td>
<td class=embedded width="25">&nbsp;</td>
<td class=embedded width="35">&nbsp;</td>
<td class=embedded width="85">&nbsp;</td>
<td class=embedded width="125">&nbsp;</td>
<td class=embedded width="25">&nbsp;</td>
<td class=embedded width="35">&nbsp;</td>
<td class=embedded width="85">&nbsp;</td>
<td class=embedded width="125">&nbsp;</td>
<td class=embedded width="25">&nbsp;</td>
<td class=embedded width="35">&nbsp;</td>
</tr>
<tr><td class=embedded colspan=11><b>Kerfisstjórar/Eigendur</b></td></tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>
<tr height=15>
<?=$staff_table[UC_SYSOP]?>
</tr>
<tr><td class="embedded" colspan"11">Hjálparbeiðnir eiga <b>ekki</b> að koma beint á þennan 
stjórnanda! Ráðfærðu þig við <b>aðra stjórnendur</b> áður.</td></tr>
<tr><td class=embedded colspan=11>&nbsp;</td></tr>
<tr><td class=embedded colspan=11><b>Stjórnendur (2. stigs)</b></td></tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>
<tr height=15>
<?=$staff_table[UC_ADMINISTRATOR]?>
</tr>
<tr><td class=embedded colspan=11>&nbsp;</td></tr>
<tr><td class=embedded colspan=11><b>Stjórnendur (1. stigs)</b></td></tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>
<tr height=15>
<?=$staff_table[UC_MODERATOR]?>
</tr>
</table>
<?
end_frame();
}
}
?>

<? if (get_user_class() >= UC_SYSOP) { ?>
<? begin_frame("Site Owner Tools<font color=#FF0000> - Viewable by SysOp only.</font>"); ?>
<table width=500 cellspacing=10 align=center>
<tr>
<td class=embedded><form method=get action=importpg.php><input type=submit value="PeerGuardian" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=staffmess.php><input type=submit value="Mass Messager" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=category.php><input type=submit value="Modify Categories" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=delacct.php><input type=submit value="Delete Account" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=bans.php><input type=submit value="Bad Users" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=status.php><input type=submit value="Server Status" style='height: 20px; width: 100px'></form></td>
</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR) { ?>
<? begin_frame("Site Owner Tools<font color=#009900> - Viewable by Administrators only.</font>"); ?>
<table width=500 cellspacing=10 align=center>
<tr>
<td class=embedded><form method=get action=unco.php><input type=submit value="Unconfirmed Users" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=delacctadmin.php><input type=submit value="Delete USERS" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=agentban.php><input type=submit value="Client Bans" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=topten.php><input type=submit value="Top 10" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=bitbucketlog.php><input type=submit value="Bitbucket Logs" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=findnotconnectable.php><input type=submit value="NON Connectable" style='height: 20px; width: 100px'></form></td>
</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_MODERATOR) { ?>
<? begin_frame("Staff tools - <font color=#004E98>Viewable by Mods only.</font>"); ?>


<table width=640 cellspacing=3>
<tr>
<? if (get_user_class() >= UC_MODERATOR) { ?>
</tr>
<tr>
<td class=embedded><a class=altlink href=staff.php?act=users>List users with ratio below 0.20</a></td>
<td class=embedded>Lists all the users that have an share ratio below 0.20</td>
</tr>
<tr>
<td class=embedded><a class=altlink href=staff.php?act=banned>List all banned users</a></td>
<td class=embedded>Lists all the users that have been banned from the site</td>
</tr>
<tr>
<td class=embedded><a class=altlink href=staff.php?act=last>Newest users</a></td>
<td class=embedded>100 newest user accounts</td>
</tr>
<tr>
<td class=embedded><a class=altlink href=log.php>Site log</a></td>
<td class=embedded>See whats been upped/deleted/etc</td>
</tr>
</table>

<? end_frame(); ?>
<br>
<? begin_frame("Moderators and Tools - <font color=#004E98>Viewable by Mods only.</font>"); ?>

<br>
<table width=500 cellspacing=3>
<tr>
<td class=embedded></td>

</tr>

</table>
<table width=500 cellspacing=10 align=center>
<tr>
<td class=embedded><form method=get action=warned.php><input type=submit value="Users Warned" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=adduser.php><input type=submit value="Add User" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=makepoll.php><input type=submit value="Create a Poll" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=recover.php><input type=submit value="Recover Account" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=uploaders.php><input type=submit value="Uploaders" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=polloverview.php><input type=submit value="Poll Overview" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=users.php><input type=submit value="User List" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=tags.php><input type=submit value="Forum Tags" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=smilies.php><input type=submit value="Smilies" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=pending.php><input type=submit value="Pending" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=stats.php><input type=submit value="Tracker Stats" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=testip.php><input type=submit value="Test IP" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=reports.php><input type=submit value="Reports" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=ipcheck.php><input type=submit value="Duplicate IPs" style='height: 20px; width: 100px'></form></td>
</tr>
</table>
<br>

<? end_frame(); ?>

<? begin_frame("Search user - <font color=#004E98>Viewable by Mods only.</font>"); ?>


<table width=640 cellspacing=3>
<tr>
<td class=embedded>
<form method=get action="users.php">
Search: <input type=text size=30 name=search>
<select name=class>
<option value='-'>(any class)</option>
<option value=0>User</option>
<option value=1>Power User</option>
<option value=2>VIP</option>
<option value=3>Uploader</option>
<option value=4>Moderator</option>
<option value=5>Administrator</option>
<option value=6>SysOp</option>
</select>
<input type=submit value='Okay'>
</form>
</td>
</tr>
<tr><td class=embedded><li><a href="usersearch.php">Advance user search</li></a></td></tr>
</table>

<? end_frame(); ?>
<br>
<? if ($act == "users") {
begin_frame("Users with ratio below 0.20");

echo '<table width="640" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>User</td><td class=colhead>Ratio</td><td class=colhead>IP</td><td class=colhead>Date Joined</td><td class=colhead>Last Access</td><td class=colhead>Download</td><td class=colhead>Upload</td></tr>";


$result = mysql_query ("SELECT * FROM users WHERE uploaded / downloaded < 0.20 AND enabled = 'yes' ORDER BY downloaded DESC ");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
end_frame(); }?>

<? if ($act == "last") {
begin_frame("Latest users");

echo '<table width="640" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>User</td><td class=colhead>Ratio</td><td class=colhead>IP</td><td class=colhead>Date Joined</td><td class=colhead>Last Access</td><td class=colhead>Download</td><td class=colhead>Upload</td></tr>";

$result = mysql_query ("SELECT * FROM users WHERE enabled = 'yes' AND status = 'confirmed' ORDER BY added DESC limit 100");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
end_frame(); }?>


<? if ($act == "banned") {
begin_frame("Banned users");

echo '<table width="640" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>User</td><td class=colhead>Ratio</td><td class=colhead>IP</td><td class=colhead>Date Joined</td><td class=colhead>Last Access</td><td class=colhead>Download</td><td class=colhead>Upload</td></tr>";
$result = mysql_query ("SELECT * FROM users WHERE enabled = 'no' ORDER BY last_access DESC ");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
end_frame(); } }



}
if (!$act) {
	$dt = gmtime() - 180;
	$dt = sqlesc(get_date_time($dt));
	// LIST ALL FIRSTLINE SUPPORTERS
	// Search User Database for Firstline Support and display in alphabetical order
	$res = mysql_query("SELECT * FROM users WHERE support='yes' AND status='confirmed' ORDER BY username LIMIT 10") or sqlerr();
	while ($arr = mysql_fetch_assoc($res)) {
		$land = mysql_query("SELECT name,flagpic FROM countries WHERE id=$arr[country]") or sqlerr();
		$arr2 = mysql_fetch_assoc($land);
		$firstline .= "<tr height=15><td class=embedded><a class=altlink href=userdetails.php?id=".$arr['id'].">".$arr['username']."</a></td>
		<td class=embedded> ".("'".$arr['last_access']."'">$dt?"<img src=".$pic_base_url."online.gif border=0 alt=\"online\">":"<img src=".$pic_base_url."offline.gif border=0 alt=\"offline\">" )."</td>".
		"<td class=embedded><a href=sendmessage.php?receiver=".$arr['id'].">"."<img src=".$pic_base_url."button_pm.gif border=0></a></td>".
		"<td class=embedded><img src=".$pic_base_url."/flag/$arr2[flagpic] border=0 width=19 height=12></td>".
		"<td class=embedded>".$arr['supportfor']."</td></tr>\n";
	}


begin_frame("Þjónustuver");
?>

<table width=725 cellspacing=0>
<tr>
<td class=embedded colspan=11>Venjulegum hjálparbeiðnum ætti helst að vera beint að þessum notendum. 
Vinsamlegast íhugaðu það að þetta eru sjálfboðaliðar sem gefa vinnu sína til að hjálpa þér.
Ekki láta illa við þá. (Allar hjálparbeiðnir ættu að fara fram á íslensku ef kostur er.)<br><br><br></td></tr>
<!-- Define table column widths -->
<tr>
<td class=embedded width="30"><b>Notandanafn</b></td>
<td class=embedded width="5"><b>Virkur</b></td>
<td class=embedded width="5"><b>Hafa samband</b></td>
<td class=embedded width="85"><b>Tungumál</b></td>
<td class=embedded width="200"><b>Hjálpar með:</b></td>
</tr>


<tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>

<?=$firstline?>

</tr>
</table>
<?
end_frame();

begin_frame('Svið');
?>
Geti hjálparar ekki aðstoðað eða hjálpin sem þú þarft er utan verksviðs þeirra, þá geturðu haft samband við þann 
stjórnanda sem hefur umsjón yfir því sem fyrirspurnin fjallar um. Ef þú hefur mörg mismunandi erindi, ekki senda þau 
öll á sama stjórnandann nema það vill svo til að þau eru öll á hans verksviði.<br />
Sum verksviðin eru ekki föst á einum ákveðnum stjórnanda svo að þessi listi mun breytast eftir því sem líður á 
tímann.<br />
<table><tr><td>Verksvið</td><td>Umsjónarmaður</td><td>Tegund erinda:</td></tr>

<tr><td>Eftirspurnarsvið</td>
<td>
<a href="/userdetails.php?id=141">svamli</a>
</td>
<td>- Dæmir um í vafamálum er varða eftirspurnir<br />
- Önnur mál er varða eftirspurnir</td>

</tr><tr>
<td>Fundarsvið</td>
<td><a href="/userdetails.php?id=2630">zofus</a><br />
<a href="/userdetails.php?id=2">Kjarrval</a>
</td>
<td>- Sér um almenna fundargerð funda sem Istorrent heldur<br />
- Skráir niður fundi og heldur til haga<br />
- Tekur ekki á móti skráningum frá öðrum en stjórnendum.
</td>

</tr><tr>
<td>Fríhelgarsvið</td>
<td>
<a href="/userdetails.php?id=991">Sennap</a>
</td>
<td>- Skipuleggur fríhelgar og hefur almenna umsjón með þeim.
</td>

</tr><tr>
<td>Hlutfallasvið</td>
<td>
<a href="/userdetails.php?id=2">Kjarrval</a>
</td>
<td>- Sér um að óvirkja notendur skv. hlutfallareglum<br />
- Sér um mál er varða vikufresti.
</td>

</tr><tr>
<td>Innsendingasvið</td>
<td>
<a href="/userdetails.php?id=65">DamnDude</a>
</td>
<td>- Sér um að innsend torrent séu yfirfarin<br />
- Mótar stefnu Istorrent um ásættanlegar lýsingar<br />
- Dæmir um vafamál varðandi innsendingar.
</td>

</tr><tr>
<td>Leiðbeiningasvið</td>
<td>
<a href="/userdetails.php?id=991">Sennap</a>
</td>
<td>- Sér um framleiðslu og þróun leiðbeininga.<br />
- Stjórnar því hvaða leiðbeiningar eiga rétt á sér á viðeigandi spjallflokki á spjallborðinu.
</td>

</tr><tr>
<td>Myndasvið</td>
<td>
<a href="/userdetails.php?id=263">konni</a>
</td>
<td>- Dæmir um vafamál er varða myndir (ekki kvikmyndir eða myndbönd).<br />
- Mótar stefnu Istorrent er varða særandi eða viðeigandi myndir.
</td>

</tr><tr>
<td>Notendasvið</td>
<td>
<a href="/userdetails.php?id=1468">tomaz</a>
</td>
<td>- Öll erindi er varða notendur en falla ekki undir önnur svið.<br />
- Sér um mál er varða persónuverndarstefnu Istorrent.<br />
- Svarar fyrirspurnum notenda er varða stöður.<br />
- Svarar fyrirspurnum notenda er varða nýskráningar.<br />
- Veitir stjórn Istorrent aðhald hvað varðar réttindi notenda ef þörf krefur.
</td>

</tr><tr>
<td>Spjallsvið</td>
<td>
<a href="/userdetails.php?id=3258">egerapi</a>
</td>
<td>- Sér um að spjallreglum Istorrent sé framfylgt.<br />
- Dæmir um vafamál er varða spjallreglur Istorrent.
</td>

</tr><tr>
<td>Stjórnendasvið</td>
<td>
<a href="/userdetails.php?id=2630">zofus</a><br />
<a href="/userdetails.php?id=3258">egerapi</a>
<td>- Tekur á móti kvörtunum og athugasemdum notenda er varða aðra stjórnendur.<br />
- Sér um að leggja kvartanirnar fyrir aðra stjórnendur.
</td>

</tr><tr>
<td>Styrkjasvið</td>
<td><a href="/userdetails.php?id=2">Kjarrval</a>
</td>
<td>- Tekur á móti skráningum og fyrirspurnum um styrki og skráir í kerfið þá sem hafa styrkt.
</td>

</tr><tr>
<td>Undantekningasvið</td>
<td><a href="/userdetails.php?id=1468">tomaz</a>
</td>
<td>- Sér um að taka á móti skráningum frá notendum varðandi undantekningar á reglum.<br />
- Sér um að dæma hvort umsækjendur uppfylli viðeigandi skilyrði fyrir undantekningu.<br />
- Veitir undantekningar þegar þörf er á og heimild sé til þess í reglunum.
</td>

</tr><tr>
<td>Viðvaranasvið</td>
<td>
<a href="/userdetails.php?id=1298">Zico</a></td>
</td>
<td>- Heldur utan um tímalengdir viðvarana og sér um að skilgreina nýjar þegar þær vantar.<br />
- Hefur eftirlit með gildum viðvörunum og athugar hvort þær séu samkvæmt settum stöðlum.<br />
- Dæmir um í vafamálum er varða viðvaranir.
</td>
</tr>

</table>
<?
end_frame();
end_frame();
end_main_frame();
stdfoot();
}
?>
