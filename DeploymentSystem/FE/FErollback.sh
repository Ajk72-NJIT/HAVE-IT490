#!/bin/bash

FROM_IP="10.211.55.6" #Deployment system
USER="parallels"
COPYFROM="/home/parallels/HAVE-FRIDGE"
COPYTO="/home/parallels/HAVE-FRIDGE"
ID="FRE"
TEMP="/home/parallels/Temp/Rollback/$(date +%Y-%m-%d)"

mkdir -p "$TEMP"
mkdir -p "$COPYTO"

scp "$USER@$FROM_IP:$COPYFROM/$1" "$COPYTO"

unzip -o "$COPYTO/$1" -d "$TEMP"

cp -Rf "$TEMP/$ID/"* "$COPYTO"

echo "files copied"
