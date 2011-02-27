<?
if ((get_user_class() >= UC_MODERATOR || $user["id"] == $CURUSER["id"]) && $user['invitari'] != 'Tómt') {
	$invitari = $user['invitari'];
	$res = mysql_query("SELECT id,username,uploaded,downloaded,enabled FROM users WHERE id = '$invitari'") or sqlerr();
	if(mysql_num_rows($res) < 1) {
		echo '<tr valign="top"><td class="rowhead">Bjóðandi:</td><td>Enginn notandi bauð honum/henni eða bjóðandi er ekki lengur í gagnagrunninum.</td>';
	} else {
		while ($a = mysql_fetch_assoc($res))
		{
			//if($a['enabled'] == 'yes') {
			//	$i = get_user_class_name($a["class"]);
			//} else {
			//	$i = "<font color=red>Disabled</font>";
			//}
			if($a['uploaded'] > '0')
				$ratio = number_format($a['uploaded']/$a['downloaded'],2);
			else
				$ratio = 'Inf.';
			if($ratio >= '0.75' && $ratio != 'Inf.')
				$h = '<font color="green">'.$ratio.'</font>';
			elseif($ratio < '0.75' && $ratio > '0.5' && $ratio != 'Inf.')
				$h = '<font color="orange">'.$ratio.'</font>';
			elseif($ratio <= '0.5' && $ratio != 'Inf.')
				$h = '<font color="red">'.$ratio.'</font>';
			else
				$h = $ratio;
	
			echo '<tr valign="top"><td class="rowhead">Bjóðandi:</td><td><a href=userdetails.php?id=' . $a["id"] . '><b>' . $a["username"] .'</b></a> - ' . $ratio .'</td>';
		}
	}
}

if (get_user_class() >= UC_MODERATOR || $user["id"] == $CURUSER["id"]) {
	$userid = $user['id'];
	echo '<tr valign=top><td class="rowhead">Hefur boðið:</td><td>';
	$res = mysql_query("SELECT id,username,class,uploaded,downloaded,enabled,deleted FROM users WHERE invitari = '$userid' ORDER BY uploaded/downloaded DESC") or sqlerr();
	if(mysql_num_rows($res) < 1) {
		echo "Engum.";
	} else {
		$ratio_up = '0';
		$ratio_dn = '0';
		$ratio_nr = '';
		$nr = '0';
		$fjoldi_boda = '0';
		while ($a = mysql_fetch_assoc($res))
		{
			$nr++;
			if($a['enabled'] == 'yes') {
				$i = get_user_class_name($a['class']);
			} else {
				$i = "<font color=red>Disabled</font>";
			}
			if($a['uploaded'] > '0' && $a['downloaded'] > 0) {
				$ratio = number_format($a['uploaded']/$a['downloaded'],2);
				if($a['deleted'] !== '1')
					$ratio_nr += $a['uploaded']/$a['downloaded'];
			} else
				$ratio = 'Inf.';
	
			if($ratio >= '0.75' && $ratio != 'Inf.')
				$h = '<font color="green">'.$ratio.'</font>';
			elseif($ratio < '0.75' && $ratio > '0.5' && $ratio != 'Inf.')
				$h = '<font color="orange">'.$ratio.'</font>';
			elseif($ratio <= '0.5' && $ratio != 'Inf.')
				$h = '<font color="red">'.$ratio.'</font>';
			else
				$h = $ratio;
	
			echo '<a href=userdetails.php?id=' . $a["id"] . '><b>' . $a["username"] .'</b></a> - ' . $h;
			if($a['deleted'] === '1')
				echo ' - Eyddur';
			elseif($a['enabled'] === 'no')
				echo ' - Óvirkur';
			if($a['deleted'] === '0')
				$fjoldi_boda++;
			echo '<br>';
		}
		if($fjoldi_boda > '0') {
				$k = $ratio_nr/$fjoldi_boda;
				$ratio_ave = number_format($ratio_nr/$fjoldi_boda,2);
				if($ratio_ave >= '0.75' && $ratio_nr != 'Inf.')
					$j = '<font color="green">'.$ratio_ave.'</font>';
				elseif($ratio_ave < '0.75' && $ratio_nr > '0.5' && $ratio_nr != 'Inf.')
					$j = '<font color="orange">'.$ratio_ave.'</font>';
				elseif($ratio_ave <= '0.5' && $ratio_nr != 'Inf.')
					$j = '<font color="red">'.$ratio_ave.'</font>';
				else
					$j = 'Inf.';
		echo '<b>Meðalhlutfall:</b> '.$j.' - Eyddir notendur ekki taldir inn í</td>';
		}
	}	
}
?>
