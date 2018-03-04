<?php

include('global_vars.php');
include('functions.php');
include('php_colors.php');

$colors = new Colors();

$data['site']									= file_get_contents('http://zeus.deltacolo.com/api/?key='.$config['api_key'].'&c=site_info');
$data['site']									= json_decode($data['site'], true);

echo ".:[ SITE }:. \n";
echo "NAME ......................... " . $data['site']['name' ]. " \n";
echo "REVENUE / PROFIT ............. " . "$" . $data['site']['monthly_revenue'] . " / " . "$" . $data['site']['monthly_profit'] . " \n";
echo "AVERAGE TEMP ................. " . $data['site']['average_temps']['average_pcb'] . "°C / " . c_to_f($data['site']['average_temps']['average_pcb']) . "°F \n";
echo "POWER: ....................... " . number_format($data['site']['power']['kilowatts'], 2) . " kW / " . number_format($data['site']['power']['amps'], 2) . " AMPs \n";

?>