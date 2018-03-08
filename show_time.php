<?php 

echo print_r($argv);

$options = getopt("p:");
$part = $options["p"];

echo print_r($options);

exec("touch ".time().".txt");