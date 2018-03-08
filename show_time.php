<?php 

$var = $argv[2];

exec("touch ".time()."-".$var.".txt");