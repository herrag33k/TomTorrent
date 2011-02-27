<?
$server = "localhost";
$user = "isfrag";
$pass = "fragar";
$pre = "isfrag";
$nonews = "Engar fréttir";
mysql_pconnect($server, $user, $pass) or die(mysql_error());
mysql_select_db($pre) or die(mysql_error());
function news() {
	$query = mysql_query("SELECT * from isfrag_news") or die(mysql_error());
	if(mysql_num_rows($query) < 1) exit("Auli");
		while($a = mysql_fetch_array($query)) {
			//$var1 = array('{titile}', '{date}', '{author}', '{body}');
			//$var2 = array('api', 'köttur', 'hundur', 'bangsi');
			$var1 = title;
			$var2 = 'titill';
			if(!file_exists('news.html')) exit ('News template not found');
			$news = implode('', file('news.html'));
			echo $news;
			$news2 = str_replace($var1, $var2, $news);
			echo $news;
		}
}
?>
