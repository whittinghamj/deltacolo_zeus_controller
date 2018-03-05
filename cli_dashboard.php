<?php

echo print_r($argv);

if(isset($argv[1])){
	$config['api_key'] = $argv[1];
}else{
	include('global_vars.php');
}

include('functions.php');
include('php_colors.php');

$colors = new Colors();

$data['site']									= file_get_contents('http://zeus.deltacolo.com/api/?key='.$config['api_key'].'&c=site_info');
$data['site']									= json_decode($data['site'], true);

echo "NAME ......................... " . $data['site']['name' ]. " \n";
echo "REVENUE / PROFIT ............. " . "$" . $data['site']['monthly_revenue'] . " / " . "$" . $data['site']['monthly_profit'] . " \n";
echo "AVERAGE TEMP ................. " . $data['site']['average_temps']['average_pcb'] . "°C / " . c_to_f($data['site']['average_temps']['average_pcb']) . "°F \n";
echo "POWER: ....................... " . number_format($data['site']['power']['kilowatts'], 2) . " kW / " . number_format($data['site']['power']['amps'], 2) . " AMPs \n";

echo "\n";

require_once 'Console/Table.php';

$tbl = new Console_Table();

$tbl->setHeaders(array('ID', 'Name', 'IP', 'Hashrate', 'PCB Temp', 'Status'));

foreach($data['site']['miners'] as $miner)
{
	$tbl->addRow(array($miner['id'], $miner['name'], $miner['ip_address'], $miner['hashrate'], $miner['pcb_temp'], $miner['status']));
}

echo $tbl->getTable();

?>