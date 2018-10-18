#!/bin/bash

## get all ip addresses for this subnet
echo "Building IP address range."
	nmap -n -sL 192.168.7.0/24 | grep "Nmap scan report" | awk '{print $NF}' > dev_ips.txt
echo "Done."

echo ""

## check each ip address to see if its online
echo "Scanning IP addresses for active hosts"
	parallel -j 128 -a dev_ips.txt 'ping -c1 {} > /dev/null 2>&1 && echo {} is available || echo {} is down'
echo "Done."