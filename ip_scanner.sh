#!/bin/bash

## set vars
ip_range=$1'0/24'

## remove files from last run
# rm -rf /mcp/online_ip_addresses.txt

## create new files for this run
# touch /mcp/online_ip_addresses.txt

## get all ip addresses for this subnet running cgminer / bmminer api
#3echo "Scanning $ip_range"
	nmap -p4028 $ip_range -oG - | grep 4028/open | awk '{ print $2 }' >> /mcp/online_ip_addresses.txt
# echo "Done."

# echo ""

active_miners=`cat /mcp/online_ip_addresses.txt | wc -l`

echo "Found $active_miners miners on subnet $ip_range"