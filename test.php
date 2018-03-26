<?php

$ip_address = $argv[1];

$new_password = $argv[2];

$cmd = 'ssh-keygen -f "/root/.ssh/known_hosts" -R '.$ip_address;
exec($cmd);

$password_hash = exec(`echo -n "root:antMiner Configuration:$new_password" | md5sum | cut -b -32`);

echo "Miner IP: ".$ip_address." \n";
echo "New Password: ".$new_password." \n";
echo "Password Hash: ".$password_hash." \n";

$cmd = "sshpass -padmin ssh -o StrictHostKeyChecking=no root@".$ip_address." 'rm -rf /config/update_password.sh; wget -O /config/update_password.sh http://zeus.deltacolo.com/antminer_s9/update_password.sh; sh /config/update_password.sh >/dev/null 2>&1;'";
// exec($cmd);

// echo "Resetting miner password " . $ip_address;
