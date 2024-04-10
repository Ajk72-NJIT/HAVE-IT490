#!/bin/bash

VMS=("DMZ:10.211.55.5") #separate by space
USER="parallels"
COPYFROM="/home/parallels/CopyFrom"
COPYTO="/home/parallels/CopyTo"

mkdir -p "$COPYTO"
cd "$COPYTO"
#Loop through hosts to find and copy files
for HOST in "${VMS[@]}"; do

    echo "Connecting to $HOST"
    
    SUBDIR=${HOST:0:3}
    IP=${HOST#*:}
    
    mkdir -p "$COPYTO/$SUBDIR"
    
    echo "Copying files from $HOST:$COPYFROM"
    scp -r "$USER@$IP:$COPYFROM/"* "$COPYTO/$SUBDIR/"
done

VERSION=1
while [[ -f "HAVEFRIDGE v$VERSION.zip" ]]; do
    ((VERSION++))
done
ZIPNAME="HAVEFRIDGE v$VERSION.zip"

#Zip files
#cd "$COPYTO"

echo "Zipping files..."
zip -r "$ZIPNAME" . -x "*.zip"

echo "files copied"

