#!/bin/bash

## ZEUS Controller - Update Script (git pull)

git --git-dir=/zeus/controller/.git pull origin master
crontab /zeus/controller/crontab.txt

rm -rf /etc/rc.local
mv /zeus/controller/rc.local /etc
