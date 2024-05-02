#!/bin/bash

FROM_IP="10.211.55.6" #Deployment system
USER="parallels"
COPYFROM="/home/parallels/CopyFrom"
COPYTO="/home/parallels/CopyTo"
ID="DMZ"
TEMP="/home/parallels/Temp/$(date +%Y-%m-%d)"
ZIP="/HAVEFRIDGE*.zip"

mkdir -p "$TEMP"
mkdir -p "$COPYTO"

scp "$USER@$FROM_IP:$COPYFROM/$ZIP" "$COPYTO"

unzip -o "$COPYTO/$ZIP" -d "$TEMP"

cp -Rf "$TEMP/$ID/"* "$COPYTO"

echo "files copied"
