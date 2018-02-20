<?php
function pingAddress($ip) {
    $pingresult = exec("/bin/ping -c 1 $ip", $outcome, $status);
    if (0 == $status) {
        $status = "alive";
    } else {
        $status = "dead";
    }
    echo "The IP address, $ip, is  ".$status;
}

pingAddress($argv[1]);

echo "\n\n";