<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('error_reporting', E_ALL); 
	
include('global_vars.php');
include('functions.php');

function post_to_api($data)
{
	global $config;
	
	$data_string = json_encode($data);

	$ch = curl_init("http://zeus.deltacolo.com/api/?key=".$config['api_key']."&c=miner_update");                                                                      
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json',                                                                                
		'Content-Length: ' . strlen($data_string))                                                                       
	);                                                                                                                   

	$result = curl_exec($ch);
}

console_output("ZEUS Controller - v 1.1 alpha");

// process counter
$i = 1;

console_output("Getting existing site miners");

$miners_raw = file_get_contents("http://zeus.deltacolo.com/api/?key=".$config['api_key']."&c=site_miners");
$miners = json_decode($miners_raw, true);

console_output("Processing miners for '".$miners['site']['name']."'");

if(is_array($miners['miners']))
{
	
	foreach($miners['miners'] as $miner)
	{
		//Fork a process
		$pid = pcntl_fork();

		// parent process, waiting until children have finished
		if(!$pid)
		{
			// echo 'Starting Multi Threaded Child ', $i, PHP_EOL;
			
			// ping the machine to see if its online
			$miner['ping_status'] = ping($miner['ip_address']);
			
			// miner is offline, post that to zeus
			if($miner['ping_status'] == 'dead')
			{
				console_output("Miner ID: " . $miner['ip_address'] . " - Miner Status: offline");
				
				$miner['update']['status']				=	"offline";
				post_to_api($miner);
				die();
			}else{
				$miner['update']['status']				=	"online";
			}
			
			// get mac address
			$miner['update']['mac_address'] = exec("nmap -sP ".$miner['ip_address']." | grep MAC");
			
			// run special script for ebit miner
			if($miner['hardware'] == 'ebite9' || $miner['hardware'] == 'ebite9plus' || $miner['hardware'] == 'ebite10')
			{
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

				if( $stats['feedback']['poolAlarm'] == 0 ) {
					$mining = 'mining';
				}else{
					$mining = 'not_mining';
				}

				$miner['update']['hardware']				= 'ebite9plus';
				$miner['update']['frequency']				= $stats['feedback']['pllValue'];
				$miner['update']['hashrate_1']				= $stats['feedback']['calValue'];
				$miner['update']['pcb_temp_1']				= $stats['feedback']['tmpValue'];
				$miner['update']['pcb_temp_2']				= $stats['feedback']['tmpValue'];
				$miner['update']['pcb_temp_3']				= $stats['feedback']['tmpValue'];
				$miner['update']['status']					= $mining;
			}
			else
			{
				$miner_summary 	= request($miner['ip_address'], 'summary');

				if(is_array($miner_summary))
				{
					$miner_stats 	= request($miner['ip_address'], 'stats');
					$miner_pools 	= request($miner['ip_address'], 'pools');
					$miner_lcd 		= request($miner['ip_address'], 'lcd');

					if($miner_stats['STATUS']['Msg'] == 'CGMiner stats')
					{
						$miner['update']['hardware']				= $miner_stats['CGMiner']['Type'];
						if(isset($miner_stats['STATS1'])){$miner['update']['hardware'] = 'spondoolies';}

						// $miner['update']['hashrate']				= $miner_summary['SUMMARY']['GHS 5s'];
						$miner['update']['hardware_errors']			= $miner_summary['SUMMARY']['Hardware Errors'];
						$miner['update']['discarded']				= $miner_summary['SUMMARY']['Discarded'];
						$miner['update']['accepted']				= $miner_summary['SUMMARY']['Accepted'];
						$miner['update']['rejected']				= $miner_summary['SUMMARY']['Rejected'];

						$miner['update']['software_version']		= $miner_stats['STATUS']['Description'];
						if(isset($miner_stats['STATS0']['frequency']))
						{
							$miner['update']['frequency']			= $miner_stats['STATS0']['frequency'];
						}elseif(isset($miner_stats['STATS0']['frequency1'])){
							$miner['update']['frequency']			= $miner_stats['STATS0']['frequency1'];
						}else{
							$miner['update']['frequency']			= '0';
						}

						$miner['update']['pcb_temp_1']				= $miner_stats['STATS0']['temp1'];
						$miner['update']['pcb_temp_2']				= $miner_stats['STATS0']['temp2'];
						$miner['update']['pcb_temp_3']				= $miner_stats['STATS0']['temp3'];
						$miner['update']['pcb_temp_4']				= $miner_stats['STATS0']['temp4'];

						$miner['update']['chip_temp_1']				= $miner_stats['STATS0']['temp2_1'];
						$miner['update']['chip_temp_2']				= $miner_stats['STATS0']['temp2_2'];
						$miner['update']['chip_temp_3']				= $miner_stats['STATS0']['temp2_3'];
						$miner['update']['chip_temp_4']				= $miner_stats['STATS0']['temp2_4'];

						$miner['update']['asics_1']					= $miner_stats['STATS0']['chain_acn1'];
						$miner['update']['asics_2']					= $miner_stats['STATS0']['chain_acn2'];
						$miner['update']['asics_3']					= $miner_stats['STATS0']['chain_acn3'];
						$miner['update']['asics_4']					= $miner_stats['STATS0']['chain_acn4'];

						$miner['update']['chain_asic_1']			= $miner_stats['STATS0']['chain_acs1'];
						$miner['update']['chain_asic_2']			= $miner_stats['STATS0']['chain_acs2'];
						$miner['update']['chain_asic_3']			= $miner_stats['STATS0']['chain_acs3'];
						$miner['update']['chain_asic_4']			= $miner_stats['STATS0']['chain_acs4'];

						$miner['update']['hashrate_1']				= $miner_stats['STATS0']['chain_rate1'];
						$miner['update']['hashrate_2']				= $miner_stats['STATS0']['chain_rate2'];
						$miner['update']['hashrate_3']				= $miner_stats['STATS0']['chain_rate3'];
						$miner['update']['hashrate_4']				= $miner_stats['STATS0']['chain_rate4'];
						if($miner['update']['hardware'] == 'spondoolies'){
							$miner['update']['hashrate_1']			= $miner_stats['STATS0']['ASICs total rate'];
							$miner['update']['pcb_temp_1']			= $miner_stats['STATS0']['Temperature front'];
							$miner['update']['pcb_temp_2']			= $miner_stats['STATS0']['Temperature rear top'];
							$miner['update']['pcb_temp_3']			= $miner_stats['STATS0']['Temperature rear bot'];
						}
						if($miner['update']['hardware'] == 'Antminer S4'){
							echo print_r($miner_lcd, true);
							$miner['update']['hashrate_1']			= $miner_lcd['LCD0']['GHS5s'];
							$miner['update']['pcb_temp_1']			= $miner_lcd['LCD0']['temp'];
							$miner['update']['pcb_temp_2']			= $miner_lcd['LCD0']['temp'];
							$miner['update']['pcb_temp_3']			= $miner_lcd['LCD0']['temp'];
						}

						$miner['update']['pools'][0]['user']		= $miner_pools['POOL0']['User'];
						$miner['update']['pools'][0]['url']			= str_replace("stratum+tcp://", "", $miner_pools['POOL0']['URL']);
					}
					elseif($miner_stats['STATUS']['Msg'] == 'BMMiner stats')
					{
						$miner['update']['hardware']				= $miner_stats['BMMiner']['Type'];
						$miner['update']['software_version']		= 'BMMiner' . $miner_stats['BMMiner']['BMMiner'];				

						$miner['update']['hardware_errors']			= $miner_summary['SUMMARY']['Hardware Errors'];
						$miner['update']['discarded']				= $miner_summary['SUMMARY']['Discarded'];
						$miner['update']['accepted']				= $miner_summary['SUMMARY']['Accepted'];
						$miner['update']['rejected']				= $miner_summary['SUMMARY']['Rejected'];

						$miner['update']['frequency']				= $miner_stats['STATS0']['frequency'];
						$miner['update']['pcb_temp_1']				= $miner_stats['STATS0']['temp6'];
						$miner['update']['pcb_temp_2']				= $miner_stats['STATS0']['temp7'];
						$miner['update']['pcb_temp_3']				= $miner_stats['STATS0']['temp8'];
						$miner['update']['pcb_temp_4']				= '0';

						$miner['update']['chip_temp_1']				= $miner_stats['STATS0']['temp2_6'];
						$miner['update']['chip_temp_2']				= $miner_stats['STATS0']['temp2_7'];
						$miner['update']['chip_temp_3']				= $miner_stats['STATS0']['temp2_8'];
						$miner['update']['chip_temp_4']				= '0';

						$miner['update']['asics_1']					= $miner_stats['STATS0']['chain_acn6'];
						$miner['update']['asics_2']					= $miner_stats['STATS0']['chain_acn7'];
						$miner['update']['asics_3']					= $miner_stats['STATS0']['chain_acn8'];
						$miner['update']['asics_4']					= '0';

						$miner['update']['chain_asic_1']			= $miner_stats['STATS0']['chain_acs6'];
						$miner['update']['chain_asic_2']			= $miner_stats['STATS0']['chain_acs7'];
						$miner['update']['chain_asic_3']			= $miner_stats['STATS0']['chain_acs8'];
						$miner['update']['chain_asic_4']			= '';

						$miner['update']['hashrate_1']				= $miner_stats['STATS0']['chain_rate6'];
						$miner['update']['hashrate_2']				= $miner_stats['STATS0']['chain_rate7'];
						$miner['update']['hashrate_3']				= $miner_stats['STATS0']['chain_rate8'];
						$miner['update']['hashrate_4']				= $miner_stats['STATS0']['chain_rate4'];


						$miner['update']['pools'][0]['user']		= $miner_pools['POOL0']['User'];
						$miner['update']['pools'][0]['url']			= str_replace("stratum+tcp://", "", $miner_pools['POOL0']['URL']);
					}
					$miner['update']['status']				=	"mining";

				}else{
					$miner['update']['status']				=	"offline";
				}
			}
			
			console_output("Miner ID: " . $miner['ip_address'] . " - Miner Status: " . $miner['update']['status']);
			
			// post data to zeus
			post_to_api($miner);

			//Die otherwise the process will continue to loop and each process will create all the thumbnails
			exit();
		}
		
		$i++;
	}
}else{
	console_output("No miners for this site.");
}

//Wait for all the subprocesses to complete to avoid zombie processes
foreach($miners as $miner)
{
    pcntl_wait($status);
} 

console_output('Process Complete.');

echo "\n\n";
?>