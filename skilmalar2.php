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
<br><table width=400><tr><td>S� sem fyrir tilviljun, mist�k e�a �n 
s�rstakrar heimildar tekur vi� s�mskeytum, myndum e�a ��rum 
fjarskiptamerkjum og t�knum e�a hlustar � s�mt�l m� ekki skr� neitt 
sl�kt hj� s�r e�a notf�ra s�r �a� � nokkurn h�tt. Jafnframt ber honum a� 
tilkynna sendanda a� uppl�singar hafi ranglega borist s�r. Skylt er a� 
g�ta fyllsta tr�na�ar � sl�kum tilfellum.</td></tr></table>
Samkv�mt 5. m�lsgrein 47. greinar laga um fjarskipti<br><br>
      <input name="samtyk" type="checkbox" id="samtyk" value="ja">
    &Eacute;g sam&thorn;ykki &thorn;essa skilm&aacute;la
    <p>
      <input type="submit" name="Submit" value="�fram">
    </p>
  </form>
  <p>&nbsp; </p>
</div>
