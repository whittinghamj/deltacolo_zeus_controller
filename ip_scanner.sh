#!/bin/bash

## remove files from last run
rm -rf /mcp/online_ip_addresses.txt

## create new files for this run
touch /mcp/online_ip_addresses.txt

## get all ip addresses for this subnet running cgminer / bmminer api
echo "Scanning IP range."
	nmap -p4028 192.168.7.0/24 -oG - | grep 4028/open | awk '{ print $2 }' >> /mcp/online_ip_addresses.txt
echo "Done."

echo ""

active_miners=`cat /mcp/online_ip_addresses.txt | wc -l`

echo "Found $active_miners miners"