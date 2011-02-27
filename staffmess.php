<?php
require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();
if($CURUSER['class'] < UC_MODERATOR)
	die('');

stdhead("Mass Mesaje", false);
?>
<table class=main width=750 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
<div align=center>
<h1>Mass Messager to all Staff members and users:</a></h1>
<form method=post action=takestaffmess.php>
<?

if ($_GET["returnto"] || $_SERVER["HTTP_REFERER"])
{
?>
<input type=hidden name=returnto value=<?=$_GET["returnto"] ? $_GET["returnto"] : $_SERVER["HTTP_REFERER"]?>>
<?
}
?>
<table cellspacing=0 cellpadding=5>
<tr>
<td><b>Send to:</b><br>
<table style="border: 0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="0">
</td>
<td style="border: 0">Byrjandi</td>

<td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="1">
</td>
<td style="border: 0">Notandi</td>
<td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="2">
</td>
<td style="border: 0">Virkur notandi</td>
<td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="3">
</td>
<td style="border: 0">Mjög virkur notandi</td>
</tr>
<tr>
<td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="4">
</td>
<td style="border: 0">Stjórnandi (1. stigs)</td>
<td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="5">
</td>
<td style="border: 0">Stjórnandi (2. stigs)</td>
<td style="border: 0" width="20"><input type="checkbox" name="clases[]" value="6">
</td>
<td style="border: 0">Kerfisstjóri</td>
<td style="border: 0">&nbsp;</td>
<td style="border: 0">&nbsp;</td>
</tr>
</table>
</td>
</tr>
<tr><td><textarea name=msg cols=80 rows=15><?=$body?></textarea></td></tr>
<tr>
<td colspan=1><div align="center"><b>Sender:&nbsp;&nbsp;</b>
<?=$CURUSER['username']?>
<input name="sender" type="radio" value="self" checked>
&nbsp; System
<input name="sender" type="radio" value="system">
</div></td></tr>
<tr><td colspan=1 align=center><input type=submit value="Send!" class=btn></td></tr>
</table>
<input type=hidden name=receiver value=<?=$receiver?>>
</form>

</div></td></tr></table>
<br>
NOTE: Do not user BB codes. (NO HTML)
<?
stdfoot();
?>
