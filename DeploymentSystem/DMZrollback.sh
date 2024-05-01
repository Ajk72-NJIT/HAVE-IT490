#!/bin/bash

FROM_IP="10.211.55.6"
USER="parallels"
COPYFROM="/home/parallels/CopyFrom"
COPYTO="/home/parallels/CopyTo"
ID="DMZ"
TEMP="/home/parallels/Temp/Rollback/$(date +%Y-%m-%d)"

mkdir -p "$TEMP"
mkdir -p "$COPYTO"

scp "$USER@$FROM_IP:$COPYFROM/$1" "$COPYTO"

unzip -o "$COPYTO/$1" -d "$TEMP"

cp -Rf "$TEMP/$ID/"* "$COPYTO"

echo "files copied"
