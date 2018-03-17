<?php

date_default_timezone_set('UTC');
ini_set('max_execution_time', 500);

include('functions.php');

exec('fping -a -q -g 192.168.200.0/24 > active_ip_addresses.txt');
$active_ip_addresses = file('active_ip_addresses.txt');

$miner = array();
$count = 0;

foreach ($active_ip_addresses as $active_ip_address) {
	
	console_output('Checking ' . $active_ip_address);

	$miner[$count]['ip_address'] 		= $active_ip_address;
	$miner[$count]['host_status']		= 'online';
	if(@fsockopen($active_ip_address,'4028',$errno,$errstr,1))
	{
		$miner[$count]['miner_status']	= 'online';
	}else{
		$miner[$count]['miner_status']	= 'offline';
	}

	$count++;
}

echo print_r($miner, true);