<?php 

echo print_r($argv);

$options = getopt("miner_id:");
$miner_id = $options["miner_id"];

echo print_r($options);

exec("touch ".time()."-".$miner_id.".txt");