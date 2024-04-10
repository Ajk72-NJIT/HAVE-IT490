#!/bin/bash

VMS=("10.211.55.5") #separate by space
USER="parallels"
COPYFROM="/home/parallels/CopyFrom"
COPYTO="/home/parallels/CopyTo"

mkdir -p "$COPYTO"

#Loop through hosts to find and copy files
for HOST in "${VMS[@]}"; do
    echo "Connecting to $HOST"
    
    echo "Copying files from $HOST:$COPYFROM"
    scp -r "$USER@$HOST:\"$COPYFROM\"/*" "$COPYTO/"
done

VERSION=1
while [[ -f "$COPYTO/HAVEFRIDGE v$VERSION.zip" ]]; do
    ((VERSION++))
done
ZIPNAME="HAVEFRIDGE v$VERSION.zip"

#Zip files
cd "$COPYTO"

echo "Zipping files..."
zip -r "$ZIPNAME" .

echo "files copied"

