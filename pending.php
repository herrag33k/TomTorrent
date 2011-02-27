<?php
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
if (get_user_class() > UC_ADMINISTRATOR) {
stdhead("Unconfirmed Users");
begin_main_frame();
// ===================================
$unco = number_format(get_row_count("users", "WHERE status='pending'"));
begin_frame("Unconfirmed Users ($unco)", true);
begin_table();
?>

<?php
if (!isset($_POST['submit'])) {

$query = "SELECT id,username,status,email,added FROM users WHERE status='pending' ORDER BY id ASC";
$result = mysql_query($query) or die(mysql_error());
?>
<form name="usercheck" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <tr>
     <td class="colhead">id</td>
     <td class="colhead">username</td>
     <td class="colhead">email</td>
     <td class="colhead">added</td>
     <td class="colhead">accept</td>
     <td class="colhead">reject</td>
   </tr>
 <?php
  while ($data = mysql_fetch_array($result)) {
 ?>
   <tr>
     <td style="text-align:center;"><?php echo $data['id']; ?></td>
     <td><input name="username[<?php echo $data['id']; ?>]" type="hidden" value="<?php echo $data['username']; ?>"><?php echo $data['username']; ?></td>
     <td><a href=<?php echo $data['email']; ?>><?php echo $data['email']; ?></a></td>
     <td><?php echo $data['added']; ?></td>
     <td style="text-align:center;"><input name="action[<?php echo $data['id']; ?>]" type="radio" value="accept"></td>
     <td style="text-align:center;"><input name="action[<?php echo $data['id']; ?>]" type="radio" value="delete" checked></td>
   </tr>
 <?php
 }
 ?>
 <br/>
<tr><td colspan="6" align="right"><input name="submit" type="submit" value="Just do it!"></td></tr>
</form>
<?php
}


elseif (isset($_POST['submit'])) {
if (is_array($_POST['action'])) {
 foreach ($_POST['action'] as $key => $todo) {
  if ($todo == "accept") {
   $accept .= "id='" . $key . "' OR ";
  }
  elseif ($todo == "delete") {
   $delete .= "id='" . $key . "' OR ";
  }
 }
 if (strlen($accept) > 0) {
  $accept = substr($accept,0,-3);
  $query = "SELECT id,username,email,added FROM users WHERE " . $accept;
  $accepted = mysql_query($query) or die(mysql_error());
  $query1 = "UPDATE users SET status='confirmed', editsecret=''  WHERE status='pending' AND " . $accept;
  mysql_query($query1) or die(mysql_error());
 }
 if (strlen($delete) > 0) {
  $delete = substr($delete,0,-3);
  $query = "SELECT id,username,email,added FROM users WHERE " . $delete;
  $refused = mysql_query($query) or die(mysql_error());
  $query2 = "DELETE FROM users WHERE status='pending' AND " . $delete;
  mysql_query($query2) or die(mysql_error());
 }
}
if (strlen($accept) > 0 && mysql_num_rows($accepted) > 0) {
echo "<tr>";
echo "<td class=\"none\" colspan=\"4\"><b>the following join requests have been accept:</b></td>";
echo "</tr>";
echo "<tr>";
echo "<td class=\"colhead\">id</td>";
echo "<td class=\"colhead\">username</td>";
echo "<td class=\"colhead\">email</td>";
echo "<td class=\"colhead\">join request date</td>";
echo "</tr>";
while ($data = mysql_fetch_array($accepted)) {
 echo "<tr>";
 echo "<td style=\"text-align:center;\">" . $data['id'] . "</td>";
 echo "<td>" . $data['username'] . "</td>";
 echo "<td>" . $data['email'] . "</td>";
 echo "<td>" . $data['added'] . "</td>";
 echo "</tr>";
$email = $data['email'];
$body_accept =  "You have requested a new user account on {$SITENAME} and you have
specified this address ({$data['email']}) as user contact.

Your request has been checked by an admin and has been accepted.
To login to your account, you can use the following link:

{$DEFAULTBASEURL}/login.php

We urge you to read the RULES and FAQ before you start using {$SITENAME}.

Regards
{$SITENAME} staff ";
 mail($email, "{$SITENAME} - Pending user registration", $body_accept, "From: {$SITEEMAIL}\r\nReply-To:{$SITEEMAIL}");
}
}

if (strlen($delete) > 0 && mysql_num_rows($refused) > 0) {
echo "<tr>";
echo "<td class=\"none\" colspan=\"4\"><b>the following join requests have been rejected:</b></td>";
echo "</tr>";
echo "<tr>";
echo "<td class=\"colhead\">id</td>";
echo "<td class=\"colhead\">username</td>";
echo "<td class=\"colhead\">email</td>";
echo "<td class=\"colhead\">join request date</td>";
echo "</tr>";
while ($data = mysql_fetch_array($refused)) {
 echo "<tr>";
 echo "<td style=\"text-align:center;\">" . $data['id'] . "</td>";
 echo "<td>" . $data['username'] . "</td>";
 echo "<td>" . $data['email'] . "</td>";
 echo "<td>" . $data['added'] . "</td>";
 echo "</tr>";
$user = $data['username'];
$email = $data['email'];
$body_reject =  "You have requested a new user account on {$SITENAME} and you have
specified this address ({$data['email']}) as user contact.

Your request has been checked by an admin and has been rejected.

Regards
{$SITENAME} staff ";
 mail($email, "{$SITENAME} - Pending user registration", $body_reject, "From: {$SITEEMAIL}\r\nReply-To:{$SITEEMAIL}");
}
}

echo "<h3>Commands performed:</h3>";
if (!empty($query1)) {
echo $query1 . "<br/><br/>";
}
if (!empty($query2)) {
echo $query2 . "<br/><br/>";
}


}
?>

<?
// ------------------
  end_table();
  end_frame();
// ===================================
end_main_frame();
stdfoot();
}
else {
stderr("Sorry", "Access denied!");
}
?>
