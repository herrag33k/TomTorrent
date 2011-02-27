<?
ob_start("ob_gzhandler");

require "include/bittorrent.php";
dbconn(false);
stdhead("Port Stillingar");
begin_frame("Port Stillingar");
?>
<table width=730 border=1 cellpadding=3 cellspacing=2<tr>
<td class=colhead>
<b>Azureus</b></td></tr>
</td></tr><tr>
<td><b>1:</b> Hafi&eth; forriti&eth; opi&eth; og fari&eth; &iacute; Tools / Options
  <p><img border="0" src="/porthelp/azu1.gif"></p>
<p><b>2: </b>Skrifi&eth; inn &thorn;a&eth; port sem &thorn;i&eth; vilji&eth; nota &thorn;ar sem textinn er bl&aacute;r &aacute; myndinni (default 6881) </p>
<p><img border="0" src="/porthelp/azu2.gif"></p>
<p><b>3:</b>&nbsp; Smelli&eth; &aacute; Save </p>
<p><b>4:</b>&nbsp; Endurr&aelig;si&eth; Azureus (e&eth;a sl&ouml;kkvi&eth; &aacute; tengingum og opni&eth; aftur) </td></tr>
</table>

<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>

<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
<table width=730 border=1 cellpadding=3 cellspacing=2<tr>
  <td class=colhead>
<b>Bit Tornado</b></td>
</tr>
</td></tr><tr>
<td>
<b>1:</b> Hafi&eth; forriti&eth; opi&eth; og smelli&eth; &aacute; 'Prefs'
<p>
<img src="/porthelp/bittornado.jpg" border="0"></p>
<p><b>2:</b> Velji&eth; &thorn;a&eth; port 6881 a&eth; 6881. (e&eth;a &thorn;a&eth; sem &thorn;i&eth; vilji&eth;) </p>

<p><img src="/porthelp/tornadopref.jpg" border="0"> <br>
<b>3: </b>Endurr&aelig;si&eth; Bit Tornado</p>
</td></tr>
</table>

<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
<table width=730 border=1 cellpadding=3 cellspacing=2<tr>
<td class=colhead>
<b>ABC</a></b></td></tr>
</td></tr><tr>
<td>
<b> 1:</b> Hafi&eth; forriti&eth; opi&eth; og fari&eth; &iacute; action / ABC preferences
<p>

<img src="/porthelp/abc1.jpg" border="0"></p>
<p><b> 2:</b> Velji&eth; port 6881 a&eth; 6881. (e&eth;a &thorn;a&eth; sem &thorn;i&eth; vilji&eth;) </p>
<p><img src="/porthelp/abc2.jpg" border="0"> <br>
<b> 3: </b>Endurr&aelig;si&eth; ABC</p>
</td></tr>
</table>
<?
end_frame();
stdfoot();
?>