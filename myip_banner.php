<?php

$myip = shell_exec('sh /root/myip.sh');

echo shell_exec($myip);