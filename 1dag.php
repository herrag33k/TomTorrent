<?
require("include/bittorrent.php");
dbconn();
	//delete inactive user accounts
	$secs = 84*86400; // 12 vikur
	$date = date('Ymd');
	$dt = sqlesc(get_date_time(gmtime() - $secs));
	$maxclass = UC_POWER_USER;
//	mysql_query("DELETE FROM users WHERE status='confirmed' AND ip > 0 AND class <= $maxclass AND last_access < $dt");
	mysql_query("UPDATE users SET deleted=1 WHERE status='confirmed' AND donor='no' AND ip > 0 AND class <= $maxclass AND last_access < $dt AND vikufr < $date");

	// lock topics where last post was made more than x days ago
// Deactivated by choice - SvavarL
//	$secs = 7*86400;
//	$res = mysql_query("SELECT topics.id FROM topics LEFT JOIN posts ON topics.lastpost = posts.id AND topics.sticky = 'no' WHERE " . gmtime() . " - UNIX_TIMESTAMP(posts.added) > $secs") or sqlerr(__FILE__, __LINE__);
//	while ($arr = mysql_fetch_assoc($res))
//		mysql_query("UPDATE topics SET locked='yes' WHERE id=$arr[id]") or sqlerr(__FILE__, __LINE__);
//
  //remove expired warnings
  $res = mysql_query("SELECT id FROM users WHERE warned='yes' AND warneduntil < NOW() AND warneduntil <> '0000-00-00 00:00:00'") or sqlerr(__FILE__, __LINE__);
  if (mysql_num_rows($res) > 0)
  {
    $dt = sqlesc(get_date_time());
    $msg = sqlesc("Vi�v�runin ��n hefur veri� fjarl�g�. Haga�u ��r n� betur h��an � fr�.\n");
    while ($arr = mysql_fetch_assoc($res))
    {
      mysql_query("UPDATE users SET warned = 'no', warneduntil = '0000-00-00 00:00:00' WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
      mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
    }
  }

	// H�kka notendur � "Mj�g virkur notandi" sem uppfylla skilyr�in en hafa ekki st��una.
	$limit = 100*1024*1024*1024;
	$minratio = 4.00;
	$class = UC_POWER_USER;
	$res = mysql_query("SELECT id FROM users FORCE INDEX (class) WHERE class < $class AND ((uploaded/1024/1024/1024-downloaded/1024/1024/1024 >=200) OR (uploaded >= $limit AND uploaded / downloaded >=$minratio))") or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) > 0) {
		$dt = sqlesc(get_date_time());
		$msg = sqlesc("Til hamingju, �� ert or�in(n) [b]Mj�g virkur notandi[/b].");
		while ($arr = mysql_fetch_assoc($res)) {
			mysql_query("UPDATE users SET class = $class WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
			mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
		}
	}

	// H�kka notendur � "Virkur notandi" sem uppfylla skilyr�in en hafa ekki st��una.
	$limit = 25*1024*1024*1024;
	$minratio = 1.05;
	$maxdt = sqlesc(get_date_time(gmtime() - 86400*14));
	$class = UC_GOOD_USER;
	$res = mysql_query("SELECT id FROM users FORCE INDEX (class) WHERE class < $class AND uploaded >= $limit AND uploaded / downloaded >= $minratio AND added <= $maxdt") or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) > 0) {
		$dt = sqlesc(get_date_time());
		$msg = sqlesc("Til hamingju, �� ert or�in(n) [b]Virkur notandi[/b].");
		while ($arr = mysql_fetch_assoc($res)) {
			mysql_query("UPDATE users SET class = $class WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
			mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
		}
	}

	// H�kka notendur � "Notandi" sem uppfylla skilyr�in en hafa ekki st��una.
	$limit = 10*1024*1024*1024;
	$maxdt = sqlesc(get_date_time(gmtime() - 86400*14));
	$class = UC_USER;
	$res = mysql_query("SELECT id FROM users FORCE INDEX (class) WHERE class < $class AND uploaded >= $limit") or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) > 0) {
		$dt = sqlesc(get_date_time());
		$msg = sqlesc("Til hamingju, �� ert or�in(n) [b]Notandi[/b].");
		while ($arr = mysql_fetch_assoc($res)) {
			mysql_query("UPDATE users SET class = $class WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
			mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
		}
	}

	// L�kka � Notandi �� sem uppfylla ekki skilyr�in fyrir "Virkur notandi"
	$minratio = 0.95;
	$class = UC_GOOD_USER;
	$res = mysql_query("SELECT id FROM users FORCE INDEX (class) WHERE class = $class AND uploaded / downloaded < $minratio") or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) > 0)
	{
		$dt = sqlesc(get_date_time());
		$msg = sqlesc("�� ert n� [b]Notandi[/b] �v� �� hefur fari� ni�ur fyrir 0.95 � hlutf�ll");
		while ($arr = mysql_fetch_assoc($res)) {
			mysql_query("UPDATE users SET class = 1 WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
			mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0,$arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
		}
	}

	// L�kka � "Virkur notandi" �� sem uppfylla ekki skilyr�in fyrir "Mj�g virkur notandi"
	$minratio = 3.90;
	$class = UC_POWER_USER;
	$res = mysql_query("SELECT id FROM users FORCE INDEX (class) WHERE class = $class AND uploaded/downloaded<$minratio AND uploaded/1024/1024/1024-downloaded/1024/1024/1024<190") or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) > 0)
	{
		$dt = sqlesc(get_date_time());
		$msg = sqlesc("�� ert n� [b]Virkur notandi[/b] �v� �� hefur fari� ni�ur fyrir 3.90 � hlutf�ll");
		while ($arr = mysql_fetch_assoc($res)) {
			mysql_query("UPDATE users SET class = 2 WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
			mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0,$arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
		}
	}
?>
