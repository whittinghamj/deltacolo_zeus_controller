#!/bin/bash
script="pause_antminer.sh"
newname="pause_antminer_run.sh"

rm -iv "$newname"

ln -s "$script" "$newname"

exec "$newname" "$@"