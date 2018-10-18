#!/bin/bash

SITE_API_KEY="$1"

content=$(curl -sS "http://dashboard.miningcontrolpanel.com/api/?key=$SITE_API_KEY&c=site_ip_ranges")
echo $content