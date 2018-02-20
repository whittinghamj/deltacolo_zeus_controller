<?php

include('global_vars.php');
include('php_colors.php');

$colors = new Colors();

$data['controller']['ip_address']['lan'] 		= exec("ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1'");
$data['controller']['ip_address']['wan'] 		= json_decode(file_get_contents('https://api.ipify.org?format=json'), true);
$data['controller']['ip_address']['wan'] 		= $data['controller']['ip_address']['wan']['ip'];

$data['site']									= file_get_contents('http://zeus.deltacolo.com/api/?key='.$config['api_key'].'&c=site_info');
$data['site']									= json_decode($data['site'], true);

// echo print_r($data);

// ascii text demo
// shell_exec('/usr/bin/figlet -c -f big TEXT GOES HERE');

echo shell_exec('/usr/bin/figlet -c -f banner ZEUS');                                            
                                                                                 
// echo "ZZZZZZZZZZZZZZZZZZZEEEEEEEEEEEEEEEEEEEEEEUUUUUUUU     UUUUUUUU   SSSSSSSSSSSSSSS  \n";
// echo "Z:::::::::::::::::ZE::::::::::::::::::::EU::::::U     U::::::U SS:::::::::::::::S \n";
// echo "Z:::::::::::::::::ZE::::::::::::::::::::EU::::::U     U::::::US:::::SSSSSS::::::S \n";
// echo "Z:::ZZZZZZZZ:::::Z EE::::::EEEEEEEEE::::EUU:::::U     U:::::UUS:::::S     SSSSSSS \n";
// echo "ZZZZZ     Z:::::Z    E:::::E       EEEEEE U:::::U     U:::::U S:::::S             \n";
// echo "        Z:::::Z      E:::::E              U:::::D     D:::::U S:::::S             \n";
// echo "       Z:::::Z       E::::::EEEEEEEEEE    U:::::D     D:::::U  S::::SSSS          \n";
// echo "      Z:::::Z        E:::::::::::::::E    U:::::D     D:::::U   SS::::::SSSSS     \n";
// echo "     Z:::::Z         E:::::::::::::::E    U:::::D     D:::::U     SSS::::::::SS   \n";
// echo "    Z:::::Z          E::::::EEEEEEEEEE    U:::::D     D:::::U        SSSSSS::::S  \n";
// echo "   Z:::::Z           E:::::E              U:::::D     D:::::U             S:::::S \n";
// echo "ZZZ:::::Z     ZZZZZ  E:::::E       EEEEEE U::::::U   U::::::U             S:::::S \n";
// echo "Z::::::ZZZZZZZZ:::ZEE::::::EEEEEEEE:::::E U:::::::UUU:::::::U SSSSSSS     S:::::S \n";
// echo "Z:::::::::::::::::ZE::::::::::::::::::::E  UU:::::::::::::UU  S::::::SSSSSS:::::S \n";
// echo "Z:::::::::::::::::ZE::::::::::::::::::::E    UU:::::::::UU    S:::::::::::::::SS  \n";
// echo "ZZZZZZZZZZZZZZZZZZZEEEEEEEEEEEEEEEEEEEEEE      UUUUUUUUU       SSSSSSSSSSSSSSS    \n";

// echo " .----------------.  .----------------.  .----------------.  .----------------.  \n";
// echo "| .--------------. || .--------------. || .--------------. || .--------------. | \n";
// echo "| |   ________   | || |  _________   | || | _____  _____ | || |    _______   | | \n";
// echo "| |  |  __   _|  | || | |_   ___  |  | || ||_   _||_   _|| || |   /  ___  |  | | \n";
// echo "| |  |_/  / /    | || |   | |_  \_|  | || |  | |    | |  | || |  |  (__ \_|  | | \n";
// echo "| |     .'.' _   | || |   |  _|  _   | || |  | '    ' |  | || |   '.___`-.   | | \n";
// echo "| |   _/ /__/ |  | || |  _| |___/ |  | || |   \ `--' /   | || |  |`\____) |  | | \n";
// echo "| |  |________|  | || | |_________|  | || |    `.__.'    | || |  |_______.'  | | \n";
// echo "| |              | || |              | || |              | || |              | | \n";
// echo "| '--------------' || '--------------' || '--------------' || '--------------' | \n";
// echo " '----------------'  '----------------'  '----------------'  '----------------'  \n";

// echo "\n";
echo "\n";
// echo shell_exec("/usr/bin/figlet -c -f big '$" . number_format($data['site']['monthly_revenue'], 0)." / $" . number_format($data['site']['monthly_profit'], 0)."' ");
// echo "\n";
// echo "\n";

echo ".:[ CONTROLLER }:. \n";
echo "DATE ......................... " . date("M dS Y - H:i:s", time()) . " \n";
echo "HOSTNAME ..................... " . gethostname() . " \n";
echo "LAN IP ....................... " . $data['controller']['ip_address']['lan'] . " \n";
echo "WAN IP ....................... " . $data['controller']['ip_address']['wan'] . " \n";
echo "\n";
echo ".:[ SITE }:. \n";
echo "NAME ......................... " . $data['site']['name' ]. " \n";
echo "REVENUE / PROFIT ............. " . "$" . $data['site']['monthly_revenue'] . " / " . "$" . $data['site']['monthly_profit'] . " \n";
echo "MINERS ....................... " . "Total: " . $data['site']['total_miners'] . " / " . $colors->getColoredString("Online: ", "green", "black") . $data['site']['total_online_miners'] . " / " . $colors->getColoredString("Offline: ", "red", "black") . $data['site']['total_offline_miners'] . " \n";
echo "AVERAGE TEMP ................. " . $data['site']['average_temps']['average_pcb'] . "°C" . " \n";



