#!/bin/bash

## ZEUS Controller - Install Script

## set base folder
cd /root

## update apt-get repos
apt-get update > /dev/null

## upgrade all packages
apt-get -y upgrade -qq > /dev/null

## install some packages
apt-get install -y htop nload nmap sudo zlib1g-dev gcc make git autoconf autogen automake pkg-config locate curl php php-dev php-curl dnsutils sshpass -qq > /dev/null

## install netdata
## bash <(curl -Ss https://my-netdata.io/kickstart.sh) all --dont-wait

## download speedtest script
wget -q http://deltacolo.com/scripts/speedtest.sh

## download modded .bashrc file
rm -rf /root/.bashrc
wget -q http://deltacolo.com/scripts/.bashrc

## downlod myip script
wget -q http://deltacolo.com/scripts/myip.sh

## add whittinghamj to sudo group
usermod -aG sudo whittinghamj

## use modded .bashrc file for whittinghamj and future users
rm -rf /home/whittinghamj/.bashrc
cp /root/.bashrc /home/whittinghamj/
rm -rf /etc/skel/.bashrc
cp /root/.bashrc /etc/skel

## change SSH port to 33077
sed -i -e 's/22/33077/g' /etc/ssh/sshd_config

## restart SSH server to accept new port number
/etc/init.d/ssh restart

## set controller hostname
echo 'zeus-controller' > /etc/hostname

## make zeus folders
mkdir /zeus
mkdir /zeus/controller

## get the zeus files
cd /zeus/controller
wget -q http://zeus.deltacolo.com/controller/update.sh
sh update.sh

## lets wrap up and load the modded .bashrc file
bash
