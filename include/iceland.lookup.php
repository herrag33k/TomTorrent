<?php
session_start();
    function check_icelandic($ip) {
        $rip = explode(".", $ip);
        $rix = gethostbyname( 
"{$rip[3]}.{$rip[2]}.{$rip[1]}.{$rip[0]}.iceland.rix.is" );
        if($rix == "127.1.0.1") {
            return true;
        }
        return false;
    }
    $ip = isset($_SERVER["HTTP_X_FORWARDED"]) ? 
$_SERVER["HTTP_X_FORWARDED"] : $_SERVER["REMOTE_ADDR"];
    if(!check_icelandic($ip)) {
        die("Þú ert ekki íslendingur! I pity the fool");
    }
?>
