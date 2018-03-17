<?php

$ip_address = $argv[1];

$cmd = 'ssh-keygen -f "/root/.ssh/known_hosts" -R '.$ip_address;
exec($cmd);

$cmd = "sshpass -padmin ssh -o StrictHostKeyChecking=no root@".$ip_address." 'rm -rf /config/update_password.sh; wget -O /config/update_password.sh http://zeus.deltacolo.com/antminer_s9/update_password.sh; sh /zeus/controller/update_password.sh >/dev/null 2>&1;'";
// console_output($cmd);
echo "Resetting miner password " . $ip_address;
exec($cmd);