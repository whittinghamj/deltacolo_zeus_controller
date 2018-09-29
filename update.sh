#!/bin/bash

## MCP Controller - Update Script (git pull)

cd /mcp && git --git-dir=/mcp/.git pull origin master

crontab /mcp/crontab.txt

chmod 777 global_vars.php