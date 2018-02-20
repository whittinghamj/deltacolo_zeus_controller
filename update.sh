#!/bin/bash

## ZEUS Controller - Update Script (git)

cd /zeus
rm -rf controller/
git clone ssh://git@github.com/whittinghamj/deltacolo_zeus_controller.git
mv deltacolo_zeus_controller controller
cp global_vars.php controller/
crontab controller/crontab.txt 