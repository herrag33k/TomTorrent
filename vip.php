<?
ob_start();
require_once("include/bittorrent.php");
dbconn();
stdhead("Tilkynna styrk");
begin_main_frame();
loggedinorreturn();
begin_frame('Tilkynning um styrk');
echo '�etta form er til a� tilkynna a� �� hafir styrkt Istorrent. Eftir a� b�i� er a� tilkynningin s� r�tt og a� fj�rh��in s� komin yfir � bankareikning Istorrent, �� er ��r veitt merkismannasta�an.<br />';
echo '<b>A�gangurinn sem �� ert � n�na mun f� merkismannast��una!</b><br />';
echo '<b>Eftir a� �� hefur fengi� st��una, �� getur�u breytt titlinum � pr�f�l.</b>';
?>
<form method="post" action="/vip.php">
Notandi: <input type="text" name="notandi" readonly="readonly" value="<?=$CURUSER['username']?>" /><br />
Fullt nafn: <input type="text" name="nafn" /><br />
Kennitalan ��n: <input type="text" name="kt" /><br />
---------------------------<br />
Skr��ur eigandi bankareiknings (ef annar en ��): <input type="text" name="nafn" /><br />
Kennitalan �ess a�ila (ef annar en ��): <input type="text" name="kt" /><br />
---------------------------<br />
Upph�� sem var styrkt um: <input type="text" name="upphaed">Tilkynningu er hafna� ef fj�rh��in er ekki s� sama og var millif�r�.<br />
<input type="submit" value="Senda inn" />
</form>
Ef �� lendir � vandr��um me� formi�, �� er h�gt a� senda t�lvup�st til <a href="mailto:torrent@torrent.is">torrent@torrent.is</a> og tilkynna � br�finu hva� stendur � �eim reitum sem �� �arft a� hafa fyllta �t.<br />
F�flagangur me� formi� ���ir vi�v�run.<br />
<?
end_frame();
end_main_frame();
stdfoot();
ob_flush();
?>
