<?php

// version 0.1.2

include('global_vars.php');
include('functions.php');

$runs = $argv[1];

$miners_raw 		= file_get_contents("http://zeus.deltacolo.com/api/?key=".$config['api_key']."&c=site_miners");
$miners 			= json_decode($miners_raw, true);

foreach($miners['miners'] as $miner)
{
	$miner_ids[] = $miner['id'];
}

$count 				= count($miner_ids);

console_output("Polling " . $count . " miners.");

for ($i=0; $i<$runs; $i++) {
    // open ten processes
    for ($j=0; $j<$count; $j++) {
    	echo "Checking Miner: ".$miner_ids[$j]."\n";

        $pipe[$j] = popen("php -q get_miner_stats.php -p='".$miner_ids[$j]."'", 'w');
    }

    // wait for them to finish
    for ($j=0; $j<$count; ++$j) {
        pclose($pipe[$j]);
    }
}

?>