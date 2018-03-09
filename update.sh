#!/bin/bash

## ZEUS Controller - Update Script (git pull)

git --git-dir=/zeus/controller/.git pull origin master

rm -rf /etc/rc.local
cp /zeus/controller/rc.local /etc

crontab /zeus/controller/crontab.txt

