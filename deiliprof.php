<?
require_once("include/bittorrent.php");
dbconn();
stdhead("Deilipróf");
begin_main_frame();
loggedinorreturn();
begin_frame('Deiliprófið hið fræga');
	if($CURUSER['class'] >= UC_MODERATOR)
		echo '<a href="/deiliprof-edit.php">Umsjón með deiliprófinu</a><br /><br />';
	$sql = 'SELECT * FROM uptestq ORDER BY RAND() LIMIT 20';
	$res = mysql_query($sql);
	while($data = mysql_fetch_array($res)) {
		echo '<fieldset>';
		echo '<legend>'.$data['question'].'</legend>';
		$sql = 'SELECT * FROM uptesta WHERE qid='.$data['id'].' AND correct=\'y\'';
		$row = mysql_fetch_array(mysql_query($sql));
		$keys = $values = '';
		$keys[] = $row['id'];
		$values[$row['id']] = $row['answer'];
		$sql = 'SELECT * FROM uptesta WHERE qid='.$data['id'].' AND correct=\'n\' ORDER BY RAND() LIMIT 5';
		$res2 = mysql_query($sql);
		while($row = mysql_fetch_array($res2)) {
			$keys[] = $row['id'];
			$values[$row['id']] = $row['answer'];
		}
		shuffle($keys);
		for($i=0;$i<count($keys);$i++) {
			$id = $keys[$i];
			$answer = $values[$id];
			echo '<input type="radio" id="'.$id.'" />'.$answer.'<br />'."\n";
		}
	echo '</fieldset><br /><br />';
	}
end_frame();
end_main_frame();
stdfoot();
?>
