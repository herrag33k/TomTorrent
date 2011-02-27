<div align=center>
<p><span class="important">Skilm&aacute;lar</span></p>
<?
if(!empty($_GET['invite']))
	$invitekey = $_GET['invite'];
else
	$invitekey = '';
?>

<form name="form1" method="post" action="signup.php<? echo '?invite=' . $invitekey; ?>">
<textarea name="skilmalar" cols="80" rows="18" readonly="readonly" 
wrap="virtual" id="skilmalar"><? include("disclaimer.php"); ?></textarea>      
<br>
<br><table width=400><tr><td>Sá sem fyrir tilviljun, mistök eða án 
sérstakrar heimildar tekur við símskeytum, myndum eða öðrum 
fjarskiptamerkjum og táknum eða hlustar á símtöl má ekki skrá neitt 
slíkt hjá sér eða notfæra sér það á nokkurn hátt. Jafnframt ber honum að 
tilkynna sendanda að upplýsingar hafi ranglega borist sér. Skylt er að 
gæta fyllsta trúnaðar í slíkum tilfellum.</td></tr></table>
Samkvæmt 5. málsgrein 47. greinar laga um fjarskipti<br><br>
      <input name="samtyk" type="checkbox" id="samtyk" value="ja">
    &Eacute;g sam&thorn;ykki &thorn;essa skilm&aacute;la
    <p>
      <input type="submit" name="Submit" value="Áfram">
    </p>
  </form>
  <p>&nbsp; </p>
</div>
