<?php
require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();

// Standard Administrative PM Replies
$pm_std_reply[1] = 'Lestu [url="'.$BASEURL.'/faq.php"]Spurt og svara�[/url]. Svari� er a� finna �ar. Leita�u betur ef �� hefur liti� � �a�.';
$pm_std_reply[2] = 'Fyrirspurn ��n tilheyrir ��ru verksvi�i en �g s� um. Vinsamlegast far�u � [url='.$BASEURL.'/staff.php]stj�rnendas��una[/url] og flettu upp � vi�eigandi svi�stj�ra eftir �v� hvert erindi �itt er. Finnir �� ekki svi�i� sem m�li� tengist beint, �� getur�u haft samband vi� Notendasvi� og spurt um lei�beiningar.';
$pm_std_reply[3] = '1 dags vi�v�run fyrir �fulln�gjandi l�singu � eftirfarandi torrenti:

(sl��)

Vinsamlegast far�u eftir lei�beiningunum � http://torrent.is/forums.php?action=viewtopic&topicid=7000 a� bestu getu vi� ger� l�singa � torrentum � framt��inni til a� for�ast a� svona atvik endurtaki sig.';

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

  stdhead("Senda skilabo�", false);
	?>
  <table class=main width=750 border=0 cellspacing=0 cellpadding=0>
	<tr><td class=embedded><div align=center>
	<h1>Fj�ldaskilabo� til <?=$n_pms?> user<?=($n_pms>1?"s":"")?>!</h1>
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
  <tr><td><div align="center"><b>Fr�:&nbsp;&nbsp;</b>
	<?=$CURUSER['username']?>
	<input name="sender" type="radio" value="self" checked>
	&nbsp; Kerfi�
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
	<b>Sni�:</b>
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
	  die("Enginn notandi me� �etta au�kenni.");

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
	stdhead("Senda skilabo�", false);
	?>
	<table class=main width=750 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
	<div align=center>
	<h1>Skilabo� til <a href=userdetails.php?id=<?=$receiver?>><?=$user["username"]?></a></h1>
<?
	if($CURUSER['class'] < UC_MODERATOR && $user['class'] == UC_SYSOP) {
		begin_frame();
		echo '<span style="color:red"><font size="5">Mikilv�gur lestur ��ur en �� sendir til 
Kjarrvals!</font><br />
			* �ar sem �g f� m�rg skilabo� sem �ttu ekki a� eiga erindi til m�n ver� �g a� nefna eftirfarandi:<br />
			* �� gr��ir ekkert � �v� a� hunsa �a� sem stendur h�r fyrir ne�an og senda samt skilabo�in.<br />
			* Vertu viss um a� hafa lesi� ��r til um � Istorrent ��ur en �� sendir m�r skilabo�. �g er engin pers�nuleg leitarv�l fyrir �ig.<br />
			* Textinn sem er � tenglinum <a href="/vandamal.php">Vandam�l?</a> er m�r nokku� mikilv�gur. Ef �g s� merki um a� �� hafir samband vi� mig �n �ess a� hafa fari� eftir honum, �� er mj�g l�klegt a� �� f�ir vi�v�run fyrir atviki�.<br />
			* Ekki senda � mig skilabo� sem annar stj�rnandi getur svara�.<br />
			* Ekki senda skilabo� � mig sem stj�rnanda bara �v� a� annar notandi v�sa�i ��r � mig. Haf�u fyrst samband vi� �ann stj�rnanda sem hefur umsj�n yfir �eim verkum sem erindi� � vi�. Listi yfir �au er � <a href="/staff.php">stj�rnendas��unni</a>. Ef �a� er ekki �g, �� f�r�u eing�ngu �au sv�r a� hafa samband vi� anna� svi� svo �� gr��ir ekkert � �v� a� hafa samband vi� mig � leyfisleysi.<br />
			* Ef �a� er � pers�nulegum tilgangi <b>og tengist ekki Istorrent</b>, �� m�ttu senda m�r skilabo�.<br />
			</span>';
		end_frame();
	}
	if($CURUSER['class'] < UC_MODERATOR && $user['class'] >= UC_MODERATOR && $user['class'] != UC_SYSOP) {
	begin_frame();
		echo '
		Ef �� ert � vandr��um me� notkun vefsins e�a forvitin(n) vegna einhvers � vefnum, far�u eftir eftirfarandi skrefum (� r��) ��ur en �� hefur samband:<br />
		1. Sko�a opinbera texta � vefnum<br />
		2. Spyrja bj��anda.<br />
		3. Spyrja um hj�lp � spjallbor�inu og/e�a spjallr�sinni #istorrent.<br />
		4. Spyrja vi�eigandi hj�lpara.<br />
		5. Spyrja a�alstj�rnanda.<br />
		�essi skref eru ekki listu� � gr�ni. 3ja daga vi�v�run er sett ef stj�rnandi er spur�ur �t � eitthva� sem er lista� � SOS e�a au�veldlega h�gt a� f� a� vita � fyrri skrefum.
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
	<td align=center><input type=checkbox name='delete' value='yes' <?=$CURUSER['deletepms'] == 'yes'?"checked":""?>>Ey�a skilabo�unum sem �� ert a� svara
	<input type=hidden name=origmsg value=<?=$replyto?>></td>
	<? } ?>
	<td align=center><input type=checkbox name='save' value='yes' <?=$CURUSER['savepms'] == 'yes'?"checked":""?>>Vista skilabo� � skj��una</td></tr>
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
	  <b>Einkaskilabo�sni�:</b>
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
