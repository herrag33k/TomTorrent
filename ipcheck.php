<?
function matchCIDR($addr, $cidr) {

       // $addr should be an ip address in the format '0.0.0.0'
       // $cidr should be a string in the format '100/8'
       //      or an array where each element is in the above format

        $output = false;

       if ( is_array($cidr) ) {
               foreach ( $cidr as $cidrlet ) {
                       if ( matchCIDR( $addr, $cidrlet) ) {
                               $output = true;
                       }
               }
       } else {
               list($ip, $mask) = explode('/', $cidr);
               $mask = 0xffffffff << (32 - $mask);
               $output = ((ip2long($addr) & $mask) == (ip2long($ip) & $mask));
       }
       return $output;
}

$ip = $_SERVER['REMOTE_ADDR'];

// Athuga hvort viðkomandi sé á bannlista...
$cidr = file("/www/antilink/ban-list.txt");
if(matchCIDR($ip, $cidr)) {
	header("HTTP/1.0 403 Your IP has been banned");
	die('<html><body><h1>Ip talan þín hefur verið bönnuð!</h1>Þú getur haft samband við okkur á -> <a href="mailto:torrent@torrent.is">torrent@torrent.is</a>.</body></html>');
}
$cidr = '';

$ipcheck_path = $_SERVER['REQUEST_URI'];

if(strstr($ipcheck_path, 'announce.php') !== false || strstr($ipcheck_path, 'scrape.php') !== false)
	$check = '1';

if(isset($check)) {
        $cidr = file("/www/antilink/is-net.txt");
        if(matchCIDR($ip, $cidr))
                $allow = '1';
        else
                $allow = '0';
} else
        $allow = '1';

if($allow !== '1') {
        header("HTTP/1.0 403 Computers outside Iceland not allowed");
        die('Computers outside Iceland not allowed');
}
?>
