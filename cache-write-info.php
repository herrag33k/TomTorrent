<?

require_once("include/bittorrent.php");

dbconn();

$registered = get_row_count('users', 'WHERE deleted=0');
$unverified = get_row_count('users', 'WHERE status=\'pending\'');
$torrents = get_row_count('torrents');
$dead = get_row_count('torrents', 'WHERE visible=\'no\'');
$askilmala = get_row_count('users', 'WHERE skilmalar=1 AND deleted=0');
$seeders = get_row_count('peers', 'WHERE seeder=\'yes\'');
$leechers = get_row_count('peers', 'WHERE seeder=\'no\'');

if($leechers != '0')
	$ratio = ($seeders/$leechers*100);
else
	$ratio = '0';
$peers = $seeders+$leechers;

$output = '<h2>Uppl�singar</h2>';
$output .= 'Uppl�singarnar � ne�angreindri t�flu eru uppf�r�ar sj�lfkrafa � 15 m�n�tna fresti.<br />';
$output .= '<table width="100%" border="1" cellspacing="0" cellpadding="10"><tr><td align="center">';
$output .= '<table class="main" border="1" cellspacing="0" cellpadding="5">';
$output .= '<tr><td class="rowhead">Fj�ldi notenda</td><td align="right">'.number_format($registered).'</td></tr>';
$output .= '<tr><td class="rowhead">�ar af �sta�festir notendur</td><td align="right">'.number_format($unverified).'</td></tr>';
$output .= '<tr><td class="rowhead">Fj�ldi torrenta</td><td align="right">'.number_format($torrents).'</td></tr>';
$output .= '<tr><td class="rowhead">�ar af �virk...</td><td align="right">'.number_format($dead).'</td></tr>';
if(isset($peers)) {
	$output .= '<tr><td class="rowhead">Jafnokar</td><td align="right">'.number_format($peers).'</td></tr>';
	$output .= '<tr><td class="rowhead">Deilendur</td><td align="right">'.number_format($seeders).'</td></tr>';
	$output .= '<tr><td class="rowhead">Skr�arsugur</td><td align="right">'.number_format($leechers).'</td></tr>';
	$output .= '<tr><td class="rowhead">Hlutfall deilenda mi�a� vi� skr�arsugur (%)</td><td align="right">'.number_format($ratio,2).'% </td></tr>';
}
$output .= '<tr><td class="rowhead">�eir sem hafa sam�ykkt skilm�la</td><td align="right">'.number_format($askilmala).'</td></tr>';
$output .= '<table>';
$output .= '</td></tr></table>';

file_put_contents('cache-info.txt', $output);

?>
