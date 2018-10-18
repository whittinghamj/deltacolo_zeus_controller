#!/bin/bash

SITE_API_KEY="$1"

JSON_DATA=$(curl -sS "http://dashboard.miningcontrolpanel.com/api/?key=$SITE_API_KEY&c=site_ip_ranges")
echo $JSON_DATA

SITE_NAME=`echo "$JSON_DATA" | jq -r .site.name`

echo "Site Name:" $SITE_NAME

for row in $(echo "${JSON_DATA.ip_ranges}" | jq -r '.[] | @base64'); do
    _jq() {
     echo ${row} | base64 --decode | jq -r ${1}
    }

   echo $(_jq '.ip_range')
done