<?
#======================================
#User agent ban by beeman
#======================================

require "include/bittorrent.php";
dbconn();
loggedinorreturn();

if (get_user_class() < UC_ADMINISTRATOR)
        stderr("Error", "Permission denied.");
stdhead("User Agent Ban");
begin_main_frame();
                /*------------------
                |Submits for user agent ban hack
                -------------------*/
                if ($_POST['submit'] == 'Add Ban'){
                $query = "INSERT INTO banned_agent (agent) VALUES ('" . $_POST['ban'] . "');";
               mysql_query($query);
               }
                if ($_POST['action'] == 'Delete Ban'){
                                $aquery = "DELETE FROM banned_agent WHERE agent = '" . $_POST['dban'] . "' LIMIT 1";
                                mysql_query($aquery);
                                }

begin_frame("Ban User Agent");
/*------------------
|HTML form for user agent ban hack
------------------*/
?>
                <div align="center">
                <table width='100%' cellspacing='3' cellpadding='3'>
                <tr>
                <td bgcolor='#eeeeee' colspan="2"><b><font face="Verdana" size="1">
                Client Agent Ban Settings<br /></font><font size="1" face="Times New Roman">&#9492;
                </font></b><font size="1" face="Verdana">These settings control the user agent ban hack (coded by beeman).<br><B>Note To add a client to the list you will need to use its <U>USER AGENT</U> NOT just its name</b><font></td>
                </tr>
                <form id="add ban" name="add ban" method="POST" action="./agentban.php">
                <tr>
                <td bgcolor='#eeeeee'><font face="Verdana" size="1">Add Client Ban:  <input type="text" name="ban" id="banned" size="50" maxlength="255" value=""></font></td>
                <td bgcolor='#eeeeee' align='left'>
                <font size="1" face="Verdana"><input type="submit" name="submit" value="Add Ban"></font></td>
                </tr>
                </form>

                <form id="Add known Ban" name="Add known" method="POST" action="./agentban.php">
                <tr>
                <td bgcolor='#eeeeee'><font face="Verdana" size="1">Add Client Ban For Known Agent: <select name="ban">
                <?
                /*-------------
                |Get the known agents to ban
                -------------*/
                $se = "SELECT client, agentString FROM clients ORDER BY client";
                $resa = mysql_query($se);
                while ($asrow = mysql_fetch_array($resa))
                {
                        echo"<option value=" . $asrow['agentString'] . ">" . $asrow['client'] . "</option>\n";
                        }
                echo'</select></font></td><td bgcolor="#eeeeee" align="left">
                <font size="1" face="Verdana"><input type="submit" name="submit" value="Add Ban"></font></td>
                </tr></form><br>';
                ?>

                <form id="Deleate Ban" name="Deleate Ban" method="POST" action="./agentban.php">
                <tr>
                <td bgcolor='#eeeeee'><font face="Verdana" size="1">Remove Client Ban For: <select name="dban">
                <?
                /*-------------
                |Get the agents currently banned
                -------------*/
                $select = "SELECT id, agent FROM banned_agent ORDER BY agent";
                $sres = mysql_query($select);
                while ($srow = mysql_fetch_array($sres))
                {
                        echo"<option>" . $srow['agent'] . "</option>\n";
                        }
                echo'</select></font></td><td bgcolor="#eeeeee" align="left">
                <font size="1" face="Verdana"><input type="submit" name="action" value="Delete Ban"></font></td>
                </tr></form><br>';
                #=======================================
                #End user agent ban hack
                #=======================================
end_frame();

end_main_frame();
stdfoot();
?>
