<?php


for ($i=0; $i<10; $i++) {
    // open ten processes
    for ($j=0; $j<10; $j++) {
        $pipe[$j] = popen("php -q show_time.php -p='jamie'", 'r');
    }

    // wait for them to finish
    for ($j=0; $j<10; ++$j) {
        pclose($pipe[$j]);
    }
}