<?
if(!file_exists($sign_dir.'/'.$CURUSER['id']))
	$sign_tmp = '<input value="1" name="sign_new" type="hidden" />';
else {
	$signiture = substr(file_get_contents($sign_dir.'/'.$CURUSER['id']), 0, 200);
	$sign_tmp = '<br />Svona lítur undirskriftin þín út:<br /><br />'.format_comment($signiture);
}

tr("Undirskrift", 'Hámarkslengd eru 200 slög.<br />Til að eyða undirskrift, tæmdu reitin alveg.<br />Ef þið setjið inn myndaskrár (með [img]), passið upp á að hafa þær ekki of stórar, þetta er jú bara undirskrift en ekki myndasafn.<br />BB kóði virkar í undirskriftum<br /><textarea name="signiture" cols="80" rows="5" maxlength="200">'.$signiture.'</textarea>'.$sign_tmp, 1);

$sign_tmp = '<input type="checkbox" name="undirskrift"';
if($CURUSER["undirskrift"] == '1')
	$sign_tmp .= 'checked="checked"';
$sign_tmp .= ' value="1" /> Ef hakað er í reitinn, þá sérðu undirskriftir á spjallborðinu og athugasemdum við torrent innsendingar.';

tr('Sjá undirskriftir', $sign_tmp,1);
?>
