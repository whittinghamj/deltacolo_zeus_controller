<?php

$api_url = 'http://dashboard.miningcontrolpanel.com';

include('/zeus/controller/global_vars.php');
include('/zeus/controller/functions.php');

$site_raw 			= file_get_contents($api_url."/api/?key=".$config['api_key']."&c=home");
$site 				= json_decode($site_raw, true);

$site_id			= $site['site']['id'];

$remote_ssh_port	= '110'.$site_id;

console_output("Connecting to SSH Hub and routing port: " . $remote_ssh_port);

exec("sudo ssh -f -N -o StrictHostKeyChecking=no -R ".$remote_ssh_port.":localhost:33077 root@64.71.184.69 -p 33077");

