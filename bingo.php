<?
function bingoletter($no) {
	if($no>='61')
		return 'O';
	elseif($no>='46')
		return 'G';
	elseif($no>='31')
		return 'N';
	elseif($no>='16')
		return 'I';
	else
		return 'B';
}

if($_GET['newgame']) {
	unset($_SESSION['bingocard']);
	unset($bingono);
	unset($_SESSION['bingodrawn']);
	unset($drawnout);
}

if(!$_SESSION['bingocard'] || $_GET['choose'] === '1') {
	for($i=0;$i<5;$i++) {
		for($a=0;$a<5;$a++) {
			$num = $i*15+rand(1,15);
			if(@!in_array($num,$bingono[$i]))
				$bingono[$i][] = $num;
			else
				$a = $a-1;
		}
	}
	$_SESSION['bingocard'] = $bingono;
} else
	$bingono = $_SESSION['bingocard'];

if($_SESSION['bingodrawn'] || $_GET['draw'] === '1') {
	$drawnout = $_SESSION['bingodrawn'];
	$no = rand(1,75);
	if(count($drawnout) != '75') {
		do {
			$no = rand(1,75);
		} while(in_array($no,$drawnout));
		$drawnout[] = $no;
		$_SESSION['bingodrawn'] = $drawnout;
	} else
		$gameover = '1';
}

require_once("include/bittorrent.php");
dbconn();
stdhead("Staff");
begin_main_frame();
loggedinorreturn();
begin_frame('Bingo');
if($_SESSION['bingodrawn']) {
	if($gameover != '1')
		echo bingoletter($no).$no.'!<br />';
	echo 'Búið er að draga út:<br />';
	for($s=0;$s<count($drawnout);$s++) {
		echo bingoletter($drawnout[$s]).$drawnout[$s].', ';
	}
}
echo '<table>';
for($i=0;$i<5;$i++) {
	echo '<tr>';
	for($a=0;$a<5;$a++) {
		$num = $bingono[$a][$i];
		echo '<td style="padding:15px 15px 15px 15px';
		if(@in_array($num,$drawnout))
			echo ';color:red';
		echo '">'.bingoletter($num).$num.'</td>';
	}
	echo '</tr>';
}
echo '</table>';
end_frame();
end_main_frame();
stdfoot();
?>
