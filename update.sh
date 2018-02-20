#!/bin/bash

## ZEUS Controller - Update Script

cd /zeus/controller/
rm -rf run.sh
wget -q http://zeus.deltacolo.com/controller/run.sh
sh run.sh