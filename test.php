<?php

$ip_address = $argv[1];

$new_password = 'zeus_admin';

$cmd = 'ssh-keygen -f "/root/.ssh/known_hosts" -R '.$ip_address;
exec($cmd);

$password_hash = exec('echo -n "root:antMiner Configuration:'.$new_password.'" | md5sum | cut -b -32');

echo "Miner IP: ".$ip_address." \n";
echo "New Password: ".$new_password." \n";
echo "Password Hash: ".$password_hash." \n";

$cmd = "sshpass -padmin ssh -o StrictHostKeyChecking=no root@".$ip_address." 'echo -e \"admin1372\nadmin1372\" | passwd root > /dev/nul; rm -f /config/shadow; mv /etc/shadow /config/shadow; ln -s /config/shadow /etc/shadow'";
exec($cmd);

echo "Setting password for root@" . $ip_address . " to " . $new_password. " \n";
