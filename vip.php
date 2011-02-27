<?
ob_start();
require_once("include/bittorrent.php");
dbconn();
stdhead("Tilkynna styrk");
begin_main_frame();
loggedinorreturn();
begin_frame('Tilkynning um styrk');
echo 'Þetta form er til að tilkynna að þú hafir styrkt Istorrent. Eftir að búið er að tilkynningin sé rétt og að fjárhæðin sé komin yfir á bankareikning Istorrent, þá er þér veitt merkismannastaðan.<br />';
echo '<b>Aðgangurinn sem þú ert á núna mun fá merkismannastöðuna!</b><br />';
echo '<b>Eftir að þú hefur fengið stöðuna, þá geturðu breytt titlinum í prófíl.</b>';
?>
<form method="post" action="/vip.php">
Notandi: <input type="text" name="notandi" readonly="readonly" value="<?=$CURUSER['username']?>" /><br />
Fullt nafn: <input type="text" name="nafn" /><br />
Kennitalan þín: <input type="text" name="kt" /><br />
---------------------------<br />
Skráður eigandi bankareiknings (ef annar en þú): <input type="text" name="nafn" /><br />
Kennitalan þess aðila (ef annar en þú): <input type="text" name="kt" /><br />
---------------------------<br />
Upphæð sem var styrkt um: <input type="text" name="upphaed">Tilkynningu er hafnað ef fjárhæðin er ekki sú sama og var millifærð.<br />
<input type="submit" value="Senda inn" />
</form>
Ef þú lendir í vandræðum með formið, þá er hægt að senda tölvupóst til <a href="mailto:torrent@torrent.is">torrent@torrent.is</a> og tilkynna í bréfinu hvað stendur í þeim reitum sem þú þarft að hafa fyllta út.<br />
Fíflagangur með formið þýðir viðvörun.<br />
<?
end_frame();
end_main_frame();
stdfoot();
ob_flush();
?>
