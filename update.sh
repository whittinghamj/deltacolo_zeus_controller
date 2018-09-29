#!/bin/bash

## MCP Controller - Update Script (git pull)

mv /mcp/global_vars.php /mcp/global_vars.tmp

cd /mcp && git --git-dir=/mcp/.git pull origin master

crontab /mcp/crontab.txt

mv /mcp/global_vars.tmp /mcp/global_vars.php

chmod 777 /mcp/global_vars.php