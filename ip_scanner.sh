#!/bin/bash

## remove files from last run
rm -rf /mcp/all_ip_addresses.txt
rm -rf /mcp/online_ip_addresses.txt

## create new files for this run
touch /mcp/all_ip_addresses.txt
touch /mcp/online_ip_addresses.txt

## get all ip addresses for this subnet
echo "Building IP address range."
	nmap -n -sL 192.168.7.0/24 | grep "Nmap scan report" | awk '{print $NF}' > all_ip_addresses.txt
echo "Done."

echo ""

## check each ip address to see if its online
echo "Scanning IP addresses for active hosts"
	parallel -j 128 -a dev_ips.txt 'ping -c1 {} > /dev/null 2>&1 && echo {} >> online_ip_addresses.txt || '
echo "Done."