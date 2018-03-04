#!/bin/bash

## ZEUS Controller - Update Script (git pull)

git --git-dir=/zeus/controller/.git pull origin master
crontab /zeus/controller/crontab.txt 