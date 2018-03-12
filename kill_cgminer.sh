while /bin/true; do
    killall -9 SCREEN
    /etc/init.d/cgminer stop
    /etc/init.d/bmminer stop
done &
