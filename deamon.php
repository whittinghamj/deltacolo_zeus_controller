<?php

// version 1.2

$api_url = 'http://dashboard.miningcontrolpanel.com';

// http://dashboard.miningcontrolpanel.com/api/?key=279e8017e2d2d0d7d591a25e40d6cada&c=site_miners

include('/mcp/global_vars.php');
include('/mcp/functions.php');

// console_output("Building deamon. May take up to 30 seconds.");

// sleep(30);

$runs = $argv[1];

$miners_raw 		= file_get_contents($api_url."/api/?key=".$config['api_key']."&c=site_miners");
$miners 			= json_decode($miners_raw, true);

foreach($miners['miners'] as $miner)
{
	$miner_ids[] = $miner['id'];
}

$count 				= count($miner_ids);

console_output("Polling " . $count . " miners.");

for ($i=0; $i<$runs; $i++) {
    console_output("Spawning children.");
    for ($j=0; $j<$count; $j++) {
    	echo "Checking Miner: ".$miner_ids[$j]."\n";

        $pipe[$j] = popen("php -q /mcp/deamon_update_miner_stats.php -p='".$miner_ids[$j]."'", 'w');
    }

    console_output("Killing children.");
    // wait for them to finish
    for ($j=0; $j<$count; ++$j) {
        pclose($pipe[$j]);
    }

    // console_output("Sleeping.");
    // sleep(1);
}

exit();

?>