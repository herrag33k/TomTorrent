<?
require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();
if($CURUSER['class'] < UC_MODERATOR)
        die('');

stdhead("Stats");
?>

<STYLE TYPE="text/css" MEDIA=screen>
<!--
  a.colheadlink:link, a.colheadlink:visited{
	font-weight: bold;
	color: #FFFFFF;
	text-decoration: none;
	}

	a.colheadlink:hover {
  	text-decoration: underline;
	}
-->
</STYLE>

<?

begin_main_frame('');
	begin_frame('Virk og dauð torrent eftir innsendingardegi');
	begin_table();
	$res = mysql_query('SELECT added,visible FROM torrents ORDER BY added DESC');
	$num_rows = mysql_num_rows($res);
	$virk = $ovirk = $samtals = $samtals2 = '0';
	echo '<tr><td>Dagur</td><td>Virk</td><td>Óvirk</td><td>Alls</td></tr>';
	while($results = mysql_fetch_array($res)) {
		$added = explode(' ',$results['added']);
		if($lastadded != $added[0] && isset($lastadded)) {
			echo '<tr><td>'.$lastadded.'</td><td>'.$virk.' ('.number_format(100*$virk/$samtals).'%)'.'</td><td>'.$ovirk.' ('.number_format(100*$ovirk/$samtals).'%)'.'</td><td>'.$samtals.' ('.number_format(100*$samtals/$num_rows).'%)'.'</td></tr>'."\n";
			$virk = $ovirk = $samtals = '0';
		}
		if($results['visible'] == 'yes')
			$virk++;
		else
			$ovirk++;
		$samtals++;
		$samtals2++;
		$lastadded = $added[0];
	}
	echo '<tr><td colspan="4" align="right">Alls '.$samtals2.' torrent</td></tr>';

	end_table();
	end_frame();
	$samtals2 = '0';
	begin_frame('Fjöldi virkra notenda, gefenda, viðvaraðra og eyddra notenda - raðað eftir stöðu');
	begin_table();
	echo '<tr>
	<td>Staða</td>
	<td>Virkir</td>
	<td>Óvirkir</td>
	<td>Gefendur</td>
	<td>Með viðvörun</td>
	<td>Eyddir</td>
	<td>Fjöldi</td>
	</tr>';

	for($i=UC_BEGINNER;$i<=UC_SYSOP;$i++) {
		$totalenabled = '0';
		$totaldisabled = '0';
		$totaldonor = '0';
		$totalwarned = '0';
		$totaldeleted = '0';
		switch($i) {
			case 0;
			$stada = get_user_class_name(UC_BEGINNER);
			break;
			case 1;
			$stada = get_user_class_name(UC_USER);
			break;
			case 2;
			$stada = get_user_class_name(UC_GOOD_USER);
			break;
			case 3;
			$stada = get_user_class_name(UC_POWER_USER);
			break;
			case 4;
			$stada = get_user_class_name(UC_MODERATOR);
			break;
			case 5;
			$stada = get_user_class_name(UC_ADMINISTRATOR);
			break;
			case 6;
			$stada = get_user_class_name(UC_SYSOP);
			break;
		}
		$res = mysql_query('SELECT enabled,donor,warned,deleted FROM users WHERE class='.$i);
		while($results = mysql_fetch_assoc($res)) {
			if($results['enabled'] == 'yes' && $results['deleted'] != '1')
				$totalenabled++;
			if($results['enabled'] == 'no' && $results['deleted'] != '1')
				$totaldisabled++;
			if($results['donor'] == 'yes')
				$totaldonor++;
			if($results['warned'] == 'yes')
				$totalwarned++;
			if($results['deleted'] == '1')
				$totaldeleted++;
		}
		echo '<tr>
		<td>'.$stada.'</td>
		<td>'.$totalenabled.'</td>
		<td>'.$totaldisabled.'</td>
		<td>'.$totaldonor.'</td>
		<td>'.$totalwarned.'</td>
		<td>'.$totaldeleted.'</td>
		<td>'.mysql_num_rows($res).'</td>
		</tr>';
		$samtals2 += mysql_num_rows($res);
	}
	echo '<tr><td colspan="7" align="right">Alls '.$samtals2.' notendur</td></tr>';
	end_table();
	end_frame();
end_main_frame();
stdfoot();
die;
?>
