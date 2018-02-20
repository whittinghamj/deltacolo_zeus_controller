#!/bin/bash

## ZEUS Controller - Run Script

## set base folder
cd /zeus/controller/

## console.php
rm -rf console.php
wget -q http://zeus.deltacolo.com/controller/console.txt
mv console.txt console.php

## functions.php
rm -rf functions.php
wget -q http://zeus.deltacolo.com/controller/functions.txt
mv functions.txt functions.php

## crontab
rm -rf crontab.txt
wget -q http://zeus.deltacolo.com/controller/crontab.txt
crontab crontab.txt

