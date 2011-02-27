<?
function matchCIDR2($addr, $cidr) {

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

?>
