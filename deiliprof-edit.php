<?
ob_start();
require_once("include/bittorrent.php");
dbconn();
stdhead("Deilipr�f");
begin_main_frame();
loggedinorreturn();
if($CURUSER['class'] < UC_MODERATOR)
	die();
begin_frame('Deilipr�fi� hi� fr�ga');
echo '<a href="/deiliprof-edit.php?action=addq">B�ta vi� spurningu</a> | ';
echo '<a href="/deiliprof-edit.php?action=view">Sko�a spurningar</a><br /><br />';
if($_GET['action'] === 'addq') {
	$answ = $_POST['answ'];
	$answ = @array_filter($answ);
	if(!empty($_POST['question']) && !empty($_POST['ansr']) && count($answ) >= '4') {
		$sql = 'INSERT INTO uptestq (question) VALUES (\''.$_POST['question'].'\')';
		mysql_query($sql);
		$id = mysql_insert_id();
		$sql = 'INSERT INTO uptesta (qid,answer,correct) VALUES (\''.$id.'\',\''.$_POST['ansr'].'\',\'y\')';
		mysql_query($sql);
		for($i=0;$i<count($answ);$i++) {
			$sql = 'INSERT INTO uptesta (qid,answer,correct) VALUES (\''.$id.'\',\''.$answ[$i].'\',\'n\')';
			mysql_query($sql);
		}
		$header = 'Refresh: 0; url='.$BASEURL.'/deiliprof-edit.php';
		header($header);
	}
} elseif($_GET['action'] === 'view') {
	$sql = 'SELECT * FROM uptestq ORDER BY id DESC';
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res)) {
		echo $row['id'].' - <a href="/deiliprof-edit.php?action=viewq&amp;id='.$row['id'].'">'.$row['question'].'</a><br />';
	}
} elseif($_GET['action'] === 'viewq') {
	echo '<a href="/deiliprof-edit.php?action=editq&amp;id='.$_GET['id'].'">Breyta spurningu</a><br /><br />';
	$sql = 'SELECT * FROM uptestq WHERE id='.$_GET['id'];
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	echo 'Spurning: '.$row['question'].'<br />';
	$sql = 'SELECT * FROM uptesta WHERE qid='.$_GET['id'].' ORDER BY correct ASC';
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res)) {
		echo $row['answer'].' - R�tt? '.$row['correct'].'<br />';
	}
} elseif($_GET['action'] === 'editq') {
	if($_POST['submit'] === '1') {
		$sql = 'UPDATE uptestq SET question = \''.mysql_real_escape_string($_POST['question']).'\' WHERE id='.$_GET['id'];
		mysql_query($sql);
		$ansno = $_POST['ansno'];
		for($i=0;$i<count($ansno);$i++) {
			$no = $ansno[$i];
			$answer = $_POST['ans'.$no];
			$sql = 'UPDATE uptesta SET answer = \''.mysql_real_escape_string($answer).'\' WHERE id='.$no;
			mysql_query($sql);
		}
		$answ = $_POST['addans'];
		$answ = @array_filter($answ);
		if(count($answ)>'0') {
			for($i=0;$i<count($answ);$i++) {
				$sql = 'INSERT INTO uptesta (qid,answer,correct) VALUES (\''.$_GET['id'].'\',\''.$answ[$i].'\',\'n\')';
				mysql_query($sql);
			}
		}
		$header = 'Refresh: 0; url='.$BASEURL.'/deiliprof-edit.php?action=editq&id='.$_GET['id'];
		header($header);
	}
	$sql = 'SELECT * FROM uptestq WHERE id='.$_GET['id'];
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	echo 'Svarm�guleika � ekki a� fjarl�gja me� �v� a� t�ma reitinn!';
	echo '<form action="deiliprof-edit.php?action=editq&amp;id='.$_GET['id'].'" method="post">';
	echo '<input type="hidden" name="submit" value="1" />';
	echo 'Spurning: <input type="text" name="question" value="'.$row['question'].'" /><br />';
	$sql = 'SELECT * FROM uptesta WHERE qid='.$_GET['id'].' ORDER BY correct ASC';
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res)) {
		echo '<input type="hidden" name="ansno[]" value="'.$row['id'].'" />';
		echo '<input type="text" name="ans'.$row['id'].'" value="'.$row['answer'].'" /> - R�tt? '.$row['correct'].'<br />';
	}
	for($i=mysql_num_rows($res);$i<10;$i++) {
		echo '<input type="text" name="addans[]" /> - B�ta vi� svari<br />';
	}
	echo '<input type="submit" value="Breyta" /><br />';
	echo '</form>';
} else {
	echo 'Mundu: Skrifa ver�ur spurningu, r�tt svar og a� minnsta kosti 4 ranga m�guleika.<br /><br />';
	echo '<form action="deiliprof-edit.php?action=addq" method="post">';
	echo 'Spurning: <input type="text" name="question" size="80" maxlength="200" value="'.$_POST['question'].'"><br />';
	echo 'R�tt: <input type="text" name="ansr" maxlength="20" value="'.$_POST['ansr'].'"><br />';
	for($i=0;$i<10;$i++) {
		echo 'Rangt: <input type="text" name="answ[]" maxlength="20" value="'.$_POST['answ'][$i].'" /><br />';
	}
	echo '<input type="submit" value="B�ta vi�" />';
	echo '</form>';
}
end_frame();
end_main_frame();
stdfoot();
ob_flush();
?>
