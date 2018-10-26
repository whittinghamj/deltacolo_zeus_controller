#!/bin/bash

## MCP install antminer s9 addons

## set miner details
IP_ADDRESS=$1
USERNAME=$2
PASSWORD=$3

## set working dir
cd /mcp_firmware/antminer/

## make sure we have the latest version
sh /mcp_firmware/antminer/update.sh

## sync local version to remote antminer
#scp -r /mcp_firmware/antminer $USERNAME@$IP_ADDRESS:/www/pages/

sshpass -p$PASSWORD scp -r /mcp_firmware/antminer $USERNAME@$IP_ADDRESS:/www/pages/