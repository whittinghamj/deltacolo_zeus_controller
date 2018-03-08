<?php 

$options = getopt("p:");
$miner_id = $options["p"];

echo print_r($options);

exec("touch ".time()."-".$miner_id.".txt");