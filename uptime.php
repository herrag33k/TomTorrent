<?

/*Script by CoLdFuSiOn 27/02/2004 ver. 2 heehee
You can of course use this script in any page other than a BT site
as long as it is using windows and php!
replace this routine in index.php toward the bottom
<h2>Uptime</h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td align=center>
<? print(trim(exec('uptime'))); ?>
</td></tr></table>

and replace it with

<h2>Uptime</h2>
<table width=100% border=1 cellspacing=0 cellpadding=10><tr><td align=center>
<? include ("uptime.php"); ?>
</td></tr></table>*/



    $server = "Servernum";                 //Change this to your server name or website title.
    $testtime= filemtime("/var/run/apache2.pid");   // Change this to a file that is only run once during webserver startup, I suggest the http.pid if you have apache, or if IIS you'll have to experiment with different files as I don't know much about it.
    $up = time() - $testtime;
    $days = floor($up / 86400);
    $up -= ($days * 86400);
    $hours = floor($up / 3600);
    $up -= ($hours * 3600);
    $minutes = floor($up / 60);
    $up -= ($minutes * 60);
    $seconds = $up;


    echo $server." var seinast endurr�st fyrir: ";

	if ($days==1){
	echo $days." degi, ";
}else{
		echo $days." d�gum, ";
}

    if ($hours==1){
	echo $hours." t�ma, ";
}else{
		echo $hours." t�mum, ";
}
    if ($minutes==1){
	echo $minutes." m�n�tu og ";
}else{
		echo $minutes." m�n�tum og ";
}

    if ($seconds==1){
	echo $seconds." sek�ndu";
}else{
		echo $seconds." sek�ndum";
}

?>
