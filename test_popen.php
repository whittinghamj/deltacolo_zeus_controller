<?php


$miners = array('28283', '28284', '28285', '28286', '28287', '28288', '28328');

for ($i=0; $i<count(miners); $i++) {
    // open ten processes
    for ($j=0; $j<count(miners); $j++) {
    	echo "Checking Miner: ".$miners[$j]."\n";

        $pipe[$j] = popen("php -q get_miner_stats.php -p='".$miners[$j]."'", 'r');
    }

    // wait for them to finish
    for ($j=0; $j<count(miners); ++$j) {
        pclose($pipe[$j]);
    }
}