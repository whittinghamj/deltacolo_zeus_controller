<?php

$runs = $argv[1];

$miners = array('28283', '28284', '28285', '28286', '28287', '28288', '28328');

$count = count($miners);

for ($i=0; $i<$runs; $i++) {
    // open ten processes
    for ($j=0; $j<$count; $j++) {
    	echo "Checking Miner: ".$miners[$j]."\n";

        $pipe[$j] = popen("php -q get_miner_stats.php -p='".$miners[$j]."'", 'w');
    }

    // wait for them to finish
    for ($j=0; $j<$count; ++$j) {
        pclose($pipe[$j]);
    }
}

?>