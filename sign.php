<?
if(!file_exists($sign_dir.'/'.$CURUSER['id']))
	$sign_tmp = '<input value="1" name="sign_new" type="hidden" />';
else {
	$signiture = substr(file_get_contents($sign_dir.'/'.$CURUSER['id']), 0, 200);
	$sign_tmp = '<br />Svona l�tur undirskriftin ��n �t:<br /><br />'.format_comment($signiture);
}

tr("Undirskrift", 'H�markslengd eru 200 sl�g.<br />Til a� ey�a undirskrift, t�mdu reitin alveg.<br />Ef �i� setji� inn myndaskr�r (me� [img]), passi� upp � a� hafa ��r ekki of st�rar, �etta er j� bara undirskrift en ekki myndasafn.<br />BB k��i virkar � undirskriftum<br /><textarea name="signiture" cols="80" rows="5" maxlength="200">'.$signiture.'</textarea>'.$sign_tmp, 1);

$sign_tmp = '<input type="checkbox" name="undirskrift"';
if($CURUSER["undirskrift"] == '1')
	$sign_tmp .= 'checked="checked"';
$sign_tmp .= ' value="1" /> Ef haka� er � reitinn, �� s�r�u undirskriftir � spjallbor�inu og athugasemdum vi� torrent innsendingar.';

tr('Sj� undirskriftir', $sign_tmp,1);
?>
