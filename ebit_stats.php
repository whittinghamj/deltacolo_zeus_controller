<?php

$miner 		= $argv[1];

if ( empty ( $argv[1] ) ) {
	die( "Usage: script.php IP_ADDRESS " );
}

$username 	= 'admin';
$password 	= 'admin';
$loginUrl 	= 'http://'.$miner.'/user/login/';

// echo "EBit Miner Stats - v1 by Jamie Whittingham \n";
// echo "Getting stats for '".$miner."' \n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $loginUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'username='.$username.'&word='.$password);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$store = curl_exec($ch);
curl_setopt($ch, CURLOPT_URL, 'http://'.$miner.'/alarm/GetAlarmLoop');

$content = curl_exec($ch);

$stats = json_decode($content, TRUE);

if( $stats['feedback']['poolAlarm'] == 0 ) {
	$mining = 'mining';
}else{
	$mining = 'offline';
}

$hashrate = str_split ($stats['feedback']['calValue'] );

// echo "Mining Status: " . $mining . " \n";
// echo "PLL: " . $stats['feedback']['pllValue'] . " \n";
// echo "Hash Rate Raw: " . $stats['feedback']['calValue'] ." \n";
// echo "Hash Rate: " . $hashrate[0] . ".". $hashrate[1] . " TH/s \n";
// echo "Temp: " . $stats['feedback']['tmpValue'] . " ºC \n";

$results['status']		= $mining;
$results['frequency'] 	= $stats['feedback']['pllValue'];
$results['hashrate'] 	= $stats['feedback']['calValue'];
$results['temperature']	= $stats['feedback']['tmpValue'];

echo json_encode($results);