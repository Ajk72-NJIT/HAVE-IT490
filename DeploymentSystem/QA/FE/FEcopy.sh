#!/bin/bash

FROM_IP="10.211.55.6" #Deployment system
USER="parallels"
COPYFROM="/home/parallels/HAVE-FRIDGE"
COPYTO="/home/parallels/HAVE-FRIDGE"
ID="FRE"
TEMP="/home/parallels/Temp/$(date +%Y-%m-%d)"
ZIP="HAVEFRIDGEv$1.zip"

echo "${ZIP}"

mkdir -p "$TEMP"
mkdir -p "$COPYTO"

scp "${USER}@${FROM_IP}:${COPYFROM}/${ZIP}" "$TEMP/"

unzip -o "${TEMP}/${ZIP}" -d "$TEMP"

cp -Rf "${TEMP}/${ID}/"* "$COPYTO"

echo "files copied"
