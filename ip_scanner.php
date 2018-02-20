<?php

// SET VARS
error_reporting(0); 
ini_set('max_execution_time', 300);
$ports = array(4028);
$subnets = array("192.168.200.");

$count = 0;
$rigs = array();

function check_sub($sub, $ports){ 
	foreach($sub as $ips) {
		foreach(range(100,254) as $ip_oct_4){
			// build full ip address
			$ip = $ips . $ip_oct_4;
			
			$rigs[$count]['ip_address'] = $ip;
			
			echo $ip . " > ";

			// clean the buffer
			flush(); ob_flush();

			// check the port number is open / online
			foreach($ports as $port){ 
				if(@fsockopen($ip,$port,$errno,$errstr,1)){
					$cgminer = "ONLINE";
				} else {
					$cgminer = "OFFLINE !!!";
				}
				echo "CGMINER = " . $cgminer . " \n";
				
				$rigs[$count]['cgminer_status']		= $cgminer;
			}
		}
		
		$count++;
	} 
	
	// clean the buffer
	flush();
	ob_flush();
	 
}

// scan ip range
check_sub($subnets, $ports);

print_r($rigs);
?>