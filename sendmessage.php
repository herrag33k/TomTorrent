<?php
require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

// Standard Administrative PM Replies
$pm_std_reply[1] = 'Lestu [url="'.$BASEURL.'/faq.php"]Spurt og svarað[/url]. Svarið er að finna þar. Leitaðu betur ef þú hefur litið á það.';
$pm_std_reply[2] = 'Fyrirspurn þín tilheyrir öðru verksviði en ég sé um. Vinsamlegast farðu á [url='.$BASEURL.'/staff.php]stjórnendasíðuna[/url] og flettu upp á viðeigandi sviðstjóra eftir því hvert erindi þitt er. Finnir þú ekki sviðið sem málið tengist beint, þá geturðu haft samband við Notendasvið og spurt um leiðbeiningar.';
$pm_std_reply[3] = '1 dags viðvörun fyrir ófullnægjandi lýsingu á eftirfarandi torrenti:

(slóð)

Vinsamlegast farðu eftir leiðbeiningunum á http://torrent.is/forums.php?action=viewtopic&topicid=7000 að bestu getu við gerð lýsinga á torrentum í framtíðinni til að forðast að svona atvik endurtaki sig.';

// Standard Administrative PMs
$pm_template['1'] = array("Ratio warning","Hi,\n
You may have noticed, if you have visited the forum, that TB is disabling the accounts of all users with low share ratios.\n
I am sorry to say that your ratio is a little too low to be acceptable.\n
If you would like your account to remain open, you must ensure that your ratio increases dramatically in the next day or two, to get as close to 1.0 as possible.\n
I am sure that you will appreciate the importance of sharing your downloads.
You may PM any Moderator, if you believe that you are being treated unfairly.\n
Thank you for your cooperation.");
$pm_template['2'] = array("Avatar warning", "Hi,\n
You may not be aware that there are new guidelines on avatar sizes in the [url=http://torrentbits.org/rules.php]rules[/url], in particular \"Resize
your images to a width of 150 px and a size of [b]no more than 150 KB[/b].\"\n
I'm sorry to say your avatar doesn't conform to them. Please change it as soon as possible.\n
We understand this may be an inconvenience to some users but feel it is in the community's best interest.\n
Thanks for the cooperation.");

// Standard Administrative MMs
$mm_template['1'] = $pm_template['1'];
$mm_template['2'] = array("Downtime warning","We'll be down for a few hours");
$mm_template['3'] = array("Change warning","The tracker has been updated. Read
the forums for details.");

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{						          ////////  MM  //
	if (get_user_class() < UC_MODERATOR)
		stderr("Error", "Permission denied");

  $n_pms = $_POST['n_pms'];
  $pmees = $_POST['pmees'];
  $auto = $_POST['auto'];

  if ($auto)
  	$body=$mm_template[$auto][1];

  stdhead("Senda skilaboð", false);
	?>
  <table class=main width=750 border=0 cellspacing=0 cellpadding=0>
	<tr><td class=embedded><div align=center>
	<h1>Fjöldaskilaboð til <?=$n_pms?> user<?=($n_pms>1?"s":"")?>!</h1>
	<form method=post action=takemessage.php>
	<? if ($_SERVER["HTTP_REFERER"]) { ?>
	<input type=hidden name=returnto value=<?=$_SERVER["HTTP_REFERER"]?>>
	<? } ?>
	<table border=1 cellspacing=0 cellpadding=5>
	<tr><td colspan="2"><div align="center">
	<textarea name=msg cols=80 rows=15><?=$body?></textarea>
	</div></td></tr>
	<tr><td colspan="2"><div align="center"><b>Athugasemd:&nbsp;&nbsp;</b>
  <input name="comment" type="text" size="70">
	</div></td></tr>
  <tr><td><div align="center"><b>Frá:&nbsp;&nbsp;</b>
	<?=$CURUSER['username']?>
	<input name="sender" type="radio" value="self" checked>
	&nbsp; Kerfið
	<input name="sender" type="radio" value="system">
	</div></td>
  <td><div align="center"><b>Take snapshot:</b>&nbsp;<input name="snap" type="checkbox" value="1">
  </div></td></tr>
	<tr><td colspan="2" align=center><input type=submit value="Senda!" class=btn>
	</td></tr></table>
	<input type=hidden name=pmees value="<?=$pmees?>">
	<input type=hidden name=n_pms value=<?=$n_pms?>>
	</form><br><br>
	<form method=post action=<?=$_SERVER['PHP_SELF']?>>
	<table border=1 cellspacing=0 cellpadding=5>
	<tr><td>
	<b>Snið:</b>
	<select name="auto">
	<?
	for ($i = 1; $i <= count($mm_template); $i++)	{
		echo "<option value=$i ".($auto == $i?"selected":"").
    		">".$mm_template[$i][0]."</option>\n";}
  ?>
	</select>
	<input type=submit value="Nota" class=btn>
	</td></tr></table>
	<input type=hidden name=pmees value="<?=$pmees?>">
	<input type=hidden name=n_pms value=<?=$n_pms?>>
	</form></div></td></tr></table>
  <?
} else {                                                        ////////  PM  //
	$receiver = $_GET["receiver"];
	if (!is_valid_id($receiver))
	  die;

	$replyto = $_GET["replyto"];
	if ($replyto && !is_valid_id($replyto))
	  die;

	$auto = $_GET["auto"];
	$std = $_GET["std"];

	if (($auto || $std ) && get_user_class() < UC_MODERATOR)
	  die("Permission denied.");

	$res = mysql_query("SELECT * FROM users WHERE id=$receiver") or die(mysql_error());
	$user = mysql_fetch_assoc($res);
	if (!$user)
	  die("Enginn notandi með þetta auðkenni.");

  if ($auto)
 		$body = $pm_std_reply[$auto];
  if ($std)
		$body = $pm_template[$std][1];

	if ($replyto)
	{
	  $res = mysql_query("SELECT * FROM messages WHERE id=$replyto") or sqlerr();
	  $msga = mysql_fetch_assoc($res);
	  if ($msga["receiver"] != $CURUSER["id"])
	    die;
	  $res = mysql_query("SELECT username FROM users WHERE id=" . $msga["sender"]) or sqlerr();
	  $usra = mysql_fetch_assoc($res);
	  $body .= "\n\n\n-------- $usra[username] wrote: --------\n$msga[msg]\n";
	}
	stdhead("Senda skilaboð", false);
	?>
	<table class=main width=750 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
	<div align=center>
	<h1>Skilaboð til <a href=userdetails.php?id=<?=$receiver?>><?=$user["username"]?></a></h1>
<?
	if($CURUSER['class'] < UC_MODERATOR && $user['class'] == UC_SYSOP) {
		begin_frame();
		echo '<span style="color:red"><font size="5">Mikilvægur lestur áður en þú sendir til 
Kjarrvals!</font><br />
			* Þar sem ég fæ mörg skilaboð sem ættu ekki að eiga erindi til mín verð ég að nefna eftirfarandi:<br />
			* Þú græðir ekkert á því að hunsa það sem stendur hér fyrir neðan og senda samt skilaboðin.<br />
			* Vertu viss um að hafa lesið þér til um á Istorrent áður en þú sendir mér skilaboð. Ég er engin persónuleg leitarvél fyrir þig.<br />
			* Textinn sem er á tenglinum <a href="/vandamal.php">Vandamál?</a> er mér nokkuð mikilvægur. Ef ég sé merki um að þú hafir samband við mig án þess að hafa farið eftir honum, þá er mjög líklegt að þú fáir viðvörun fyrir atvikið.<br />
			* Ekki senda á mig skilaboð sem annar stjórnandi getur svarað.<br />
			* Ekki senda skilaboð á mig sem stjórnanda bara því að annar notandi vísaði þér á mig. Hafðu fyrst samband við þann stjórnanda sem hefur umsjón yfir þeim verkum sem erindið á við. Listi yfir þau er á <a href="/staff.php">stjórnendasíðunni</a>. Ef það er ekki ég, þá færðu eingöngu þau svör að hafa samband við annað svið svo þú græðir ekkert á því að hafa samband við mig í leyfisleysi.<br />
			* Ef það er í persónulegum tilgangi <b>og tengist ekki Istorrent</b>, þá máttu senda mér skilaboð.<br />
			</span>';
		end_frame();
	}
	if($CURUSER['class'] < UC_MODERATOR && $user['class'] >= UC_MODERATOR && $user['class'] != UC_SYSOP) {
	begin_frame();
		echo '
		Ef þú ert í vandræðum með notkun vefsins eða forvitin(n) vegna einhvers á vefnum, farðu eftir eftirfarandi skrefum (í röð) áður en þú hefur samband:<br />
		1. Skoða opinbera texta á vefnum<br />
		2. Spyrja bjóðanda.<br />
		3. Spyrja um hjálp á spjallborðinu og/eða spjallrásinni #istorrent.<br />
		4. Spyrja viðeigandi hjálpara.<br />
		5. Spyrja aðalstjórnanda.<br />
		Þessi skref eru ekki listuð í gríni. 3ja daga viðvörun er sett ef stjórnandi er spurður út í eitthvað sem er listað í SOS eða auðveldlega hægt að fá að vita í fyrri skrefum.
		';
	end_frame();
	}
?>
	<form method=post action=takemessage.php>
	<? if ($_SERVER["HTTP_REFERER"]) { ?>
	<input type=hidden name=returnto value=<?=$_GET["returnto"]?$_GET["returnto"]:$_SERVER["HTTP_REFERER"]?>>
	<? } ?>
	<table border=1 cellspacing=0 cellpadding=5>
	<tr><td<?=$replyto?" colspan=2":""?>><textarea name=msg cols=100 rows=25><?=$body?></textarea></td></tr>
	<tr>
	<? if ($replyto) { ?>
	<td align=center><input type=checkbox name='delete' value='yes' <?=$CURUSER['deletepms'] == 'yes'?"checked":""?>>Eyða skilaboðunum sem þú ert að svara
	<input type=hidden name=origmsg value=<?=$replyto?>></td>
	<? } ?>
	<td align=center><input type=checkbox name='save' value='yes' <?=$CURUSER['savepms'] == 'yes'?"checked":""?>>Vista skilaboð í skjóðuna</td></tr>
	<tr><td<?=$replyto?" colspan=2":""?> align=center><input type=submit value="Senda!" class=btn></td></tr>
	</table>
	<input type=hidden name=receiver value=<?=$receiver?>>
	</form>
<!--
  <?
  if (get_user_class() >= UC_MODERATOR)
  {
  ?>
  	<br><br>
  	<form method=get action=<?=$_SERVER['PHP_SELF']?>>
	  <table border=1 cellspacing=0 cellpadding=5>
	  <tr><td>
	  <b>Einkaskilaboðsnið:</b>
	  <select name="std"><?
	  for ($i = 1; $i <= count($pm_template); $i++)
	  {
	    echo "<option value=$i ".($std == $i?"selected":"").
	      ">".$pm_template[$i][0]."</option>\n";
	  }?>
	  </select>
		<? if ($_SERVER["HTTP_REFERER"]) { ?>
		<input type=hidden name=returnto value=<?=$_GET["returnto"]?$_GET["returnto"]:$_SERVER["HTTP_REFERER"]?>>
    <? } ?>
  	<input type=hidden name=receiver value=<?=$receiver?>>
		<input type=hidden name=replyto value=<?=$replyto?>>
	  <input type=submit value="Nota" class=btn>
	  </td></tr></table></form>
	<?
  }
	?>
-->
 	</div></td></tr></table>
	<?
}
stdfoot();
?>
