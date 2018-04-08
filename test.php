<?php

$miner['ip_address'] 	= '192.168.200.185';
$miner['username']		= 'root';
$miner['password']		= 'admin1372';

$url = "http://".$miner['ip_address']."/cgi-bin/upgrade.cgi";

if (function_exists('curl_file_create')) { // php 5.5+
  $cFile = curl_file_create('/zeus/controller/firmware/antminer-s9/Antminer-S9-all-201705031838-650M-user-Update2UBI-NF.tar.gz');
} else { // 
  $cFile = '@' . realpath('/zeus/controller/firmware/antminer-s9/Antminer-S9-all-201705031838-650M-user-Update2UBI-NF.tar.gz');
}

$post = array('datafile'=> $cFile);

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERPWD, $miner['username'].":".$miner['password']);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$result = curl_close($ch);

echo print_r($result);

echo "\n\nDone\n\n";