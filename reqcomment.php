<?

require_once("include/bittorrent.php");

hit_start();
$action = $_GET["action"];
dbconn(false);
hit_count();
loggedinorreturn();

if ($action == "add")
{
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
$reqid = 0 + $_POST["tid"];
if (!is_valid_id($reqid))
stderr("Error", "Wrong ID $reqid.");

$res = mysql_query("SELECT request FROM requests WHERE id = $reqid") or 
sqlerr(__FILE__,__LINE__);
$arr = mysql_fetch_array($res);
if (!$arr)
stderr("Error", "No request with ID $reqid.");

$text = trim($_POST["msg"]);
if (!$text)
stderr("Error", "Don't leave any fields blank!");

mysql_query("INSERT INTO comments (user, req, added, text, ori_text) VALUES (" .$CURUSER["id"] . ",$reqid, '" . get_date_time() . "', " . sqlesc($text) ."," . sqlesc($text) . ")");

$newid = mysql_insert_id();

mysql_query("UPDATE requests SET comments = comments + 1 WHERE id = $reqid");
header("Refresh: 0; url=reqdetails.php?id=$reqid&viewcomm=$newid#comm$newid");
hit_end();
exit();
}

$reqid = 0 + $_GET["tid"];
if (!is_valid_id($reqid))
stderr("Error", "Wrong ID $reqid.");

$res = mysql_query("SELECT request FROM requests WHERE id = $reqid") or 
sqlerr(__FILE__,__LINE__);
$arr = mysql_fetch_array($res);
if (!$arr)
stderr("Error", "Wrong ID $reqid.");

stdhead("Add comment to \"" . $arr["request"] . "\"");


?>
<script language=javascript>
function SmileIT(smile,form,text){
document.forms[form].elements[text].value = 
document.forms[form].elements[text].value+" "+smile+" ";
document.forms[form].elements[text].focus();
}
function PopMoreSmiles(form,name) {
link='moresmiles.php?form='+form+'&text='+name
newWin=window.open(link,'moresmile','height=500,width=450,resizable=no,scrollbars=yes');
if (window.focus) {newWin.focus()}
}
</script>
<?

print("<h1>Add comment to \"" . htmlspecialchars($arr["request"]) . 
"\"</h1>\n");
print("<p><form name=\"Form\" method=\"post\" 
action=\"reqcomment.php?action=add\">\n");
print("<input type=\"hidden\" name=\"tid\" value=\"$reqid\"/>\n");
print("<textarea name=msg cols=80 rows=15></textarea><br />

<a href=\"javascript: SmileIT(':smile:','Form','msg')\"><img border=0 
src=pic/smilies/smile2.gif></a>
<a href=\"javascript: SmileIT(':-D','Form','msg')\"><img border=0 
src=pic/smilies/grin.gif></a>
<a href=\"javascript: SmileIT(':-)','Form','msg')\"><img border=0 
src=pic/smilies/smile1.gif></a>
<a href=\"javascript: SmileIT(':smile:','Form','msg')\"><img border=0 
src=pic/smilies/smile2.gif></a>
<a href=\"javascript: SmileIT(':-D','Form','msg')\"><img border=0 
src=pic/smilies/grin.gif></a>
<a href=\"javascript: SmileIT(':lol:','Form','msg')\"><img border=0 
src=pic/smilies/laugh.gif></a>
<a href=\"javascript: SmileIT(':w00t:','Form','msg')\"><img border=0 
src=pic/smilies/w00t.gif></a>
<a href=\"javascript: SmileIT(':-P','Form','msg')\"><img border=0 
src=pic/smilies/tongue.gif></a>
<a href=\"javascript: SmileIT(';-)','Form','msg')\"><img border=0 
src=pic/smilies/wink.gif></a>
<a href=\"javascript: SmileIT(':-|','Form','msg')\"><img border=0 
src=pic/smilies/noexpression.gif></a>
<a href=\"javascript: SmileIT(':-/','Form','msg')\"><img border=0 
src=pic/smilies/confused.gif></a><br>
<a href=\"javascript: SmileIT(':-(','Form','msg')\"><img border=0 
src=pic/smilies/sad.gif></a>
<a href=\"javascript: SmileIT(':\'-(','Form','msg')\"><img border=0 
src=pic/smilies/cry.gif></a>
<a href=\"javascript: SmileIT(':weep:','Form','msg')\"><img border=0 
src=pic/smilies/weep.gif></a>
<a href=\"javascript: SmileIT(':-O','Form','msg')\"><img border=0 
src=pic/smilies/ohmy.gif></a>
<a href=\"javascript: SmileIT(':o)','Form','msg')\"><img border=0 
src=pic/smilies/clown.gif></a>
<a href=\"javascript: SmileIT('8-)','Form','msg')\"><img border=0 
src=pic/smilies/cool1.gif></a>
<a href=\"javascript: SmileIT('|-)','Form','msg')\"><img border=0 
src=pic/smilies/sleeping.gif></a>
<a href=\"javascript: SmileIT(':innocent:','Form','msg')\"><img border=0 
src=pic/smilies/innocent.gif></a>
<a href=\"javascript: SmileIT(':whistle:','Form','msg')\"><img border=0 
src=pic/smilies/whistle.gif></a>
<a href=\"javascript: SmileIT(':unsure:','Form','msg')\"><img border=0 
src=pic/smilies/unsure.gif></a>
<a href=\"javascript: SmileIT(':closedeyes:','Form','msg')\"><img border=0 
src=pic/smilies/closedeyes.gif></a>

<br>
<center><a href=\"javascript: 
PopMoreSmiles('Form','msg')\">MORE_SMILES</a></center>");

print("<p><input type=\"submit\" class=btn value=\"Add!\" 
/></p></form>\n");

$res = mysql_query("SELECT comments.id, text, comments.added, username, users.id as user, users.avatar FROM comments LEFT JOIN users ON comments.user = users.id WHERE req = $reqid ORDER BY comments.id DESC LIMIT 5");

$allrows = array();
while ($row = @mysql_fetch_array($res))
$allrows[] = $row;

if (count($allrows)) {
print("<h2>Last comments in reverse order.</h2>\n");
commenttable($allrows);
}

stdfoot();
hit_end();
die;
}
elseif ($action == "edit")
{
$commentid = 0 + $_GET["cid"];
if (!is_valid_id($commentid))
stderr("Error", "Wrong ID $commentid.");
$res = mysql_query("SELECT c.*, o.request FROM comments AS c JOIN requests 
AS o ON c.req = o.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);

$arr = mysql_fetch_array($res);
if (!$arr)
stderr("Error", "Wrong ID $commentid.");

if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
stderr("Error", "Access denied.");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
$text = $_POST["msg"];
$returnto = $_POST["returnto"];

if ($text == "")
stderr("Error", "Don't leave any fields blank!");

$text = sqlesc($text);

mysql_query("UPDATE comments SET text=$text WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);

if ($returnto)
header("Location: $returnto");
else
header("Location: $BASEURL/"); // change later ----------------------

hit_end();
die;
}

stdhead("Edit comment for \"" . $arr["request"] . "\"");

print("<h1>Edit comment for \"" . htmlspecialchars($arr["request"]) . 
"\"</h1><p>\n");
print("<form name=Form method=\"post\" 
action=\"reqcomment.php?action=edit&amp;cid=$commentid\">\n");
print("<input type=\"hidden\" name=\"returnto\" value=\"" . 
$_SERVER["HTTP_REFERER"] . "\" />\n");
print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
//tagbuttons();
print("<textarea name=\"msg\" rows=\"10\" cols=\"60\">" . 
htmlspecialchars($arr["text"]) . "</textarea><br>
<a href=\"javascript:Smilies(':-)')\"><img src=pic/smilies/smile1.gif 
border=0 alt=':-)'></a>
<a href=\"javascript:Smilies(';-)')\"><img src=pic/smilies/wink.gif 
border=0 alt=';-)'></a>
<a href=\"javascript:Smilies(':-P')\"><img src=pic/smilies/tongue.gif 
border=0 alt=':-P'></a>
<a href=\"javascript:Smilies(':-D')\"><img src=pic/smilies/grin.gif 
border=0 alt=':-D'></a>
<a href=\"javascript:Smilies(':-(')\"><img src=pic/smilies/sad.gif 
border=0 alt=':-('></a>
<a href=\"javascript:Smilies(':-|')\"><img 
src=pic/smilies/noexpression.gif border=0 alt=':-|'></a>
<a href=\"javascript:Smilies(':-/')\"><img src=pic/smilies/confused.gif 
border=0 alt=':-/'></a>
<br>
<a href='#' onClick=window.open('popuptags.php','_blank',& 
amp;#39width=400,height=300,screenX=100,screenY=100,resizable=yes,menubar=no,loc

ationbar=no,scrollba


rs=yes');><small>Tags</small></a> <a href='#' 
onClick=window.open('smilies.php','_blank',& 
amp;#39width=300,height=300,screenX=100,screenY=100,resizable=yes,menubar=no,loc

ationbar=no,scrollba


rs=yes');><small>Smilies...</small></a></p>\n");
print("<p><input type=\"submit\" class=btn value=\"Edit!\" 
/></p></form>\n");

stdfoot();
hit_end();
die;
}
elseif ($action == "delete")
{
if (get_user_class() < UC_MODERATOR)
stderr("Error", "Access denied.");

$commentid = 0 + $_GET["cid"];

if (!is_valid_id($commentid))
stderr("Error", "Invalid ID $commentid.");

$sure = $_GET["sure"];

if (!$sure)
{
$referer = $_SERVER["HTTP_REFERER"];
stderr("Eyða athugasemd", "Þú ert að fara að eyða þessari athugasemd. Ýttu\n" .
"<a href=?action=delete&cid=$commentid&sure=1" .
($referer ? "&returnto=" . urlencode($referer) : "") .
">hér</a>, ef þú ert viss.");
}


$res = mysql_query("SELECT req FROM comments WHERE id=$commentid") or 
sqlerr(__FILE__,__LINE__);
$arr = mysql_fetch_array($res);
if ($arr)
$offid = $arr['req'];

mysql_query("DELETE FROM comments WHERE id=$commentid") or 
sqlerr(__FILE__,__LINE__);
if ($offid && mysql_affected_rows() > 0)
mysql_query("UPDATE requests SET comments = comments - 1 WHERE id = $offid");

$returnto = $BASEURL.'/reqdetails.php?id='.$offid;
if ($returnto) {
header("Location: $returnto");
}else
header("Location: $BASEURL/"); // change later ----------------------

hit_end();
die;
}
elseif ($action == "vieworiginal")
{
if (get_user_class() < UC_MODERATOR)
stderr("Error", "Access denied.");

$commentid = 0 + $_GET["cid"];

if (!is_valid_id($commentid))
stderr("Error", "Invalid ID $commentid.");

$res = mysql_query("SELECT c.*, t.request FROM comments AS c JOIN requests 
AS t ON c.req = t.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
$arr = mysql_fetch_array($res);
if (!$arr)
stderr("Error", "Invalid ID $commentid.");

stdhead("Original");
print("<h1>Original content of comment #$commentid</h1><p>\n");
print("<table width=500 border=1 cellspacing=0 cellpadding=5>");
print("<tr><td class=comment>\n");
echo htmlspecialchars($arr["ori_text"]);
print("</td></tr></table>\n");

$returnto = $_SERVER["HTTP_REFERER"];

// $returnto = 
"details.php?id=$torrentid&amp;viewcomm=$commentid#$commentid";

if ($returnto)
print("<p><font size=small>(<a href=$returnto>Back</a>)</font></p>\n");

stdfoot();
hit_end();
die;
}
else
stderr("Error", "Unknown action $action");

die;
?>
