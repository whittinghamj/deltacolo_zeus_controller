<?php

include('/zeus/controller/global_vars.php');
include('/zeus/controller/functions.php');

$runs = $argv[1];

$site_raw 			= file_get_contents("http://zeus.deltacolo.com/api/?key=".$config['api_key']."&c=home");
$site 				= json_decode($site, true);

$site_id			= $site['site']['id'];

$remote_ssh_port	= '110'.$site_id;

exec("sudo ssh -f -N -o StrictHostKeyChecking=no -R ".$remote_ssh_port.":localhost:33077 root@64.71.184.69 -p 33077");

