#!/bin/bash

# Define remote hosts and user
VMS=("10.211.55.5") #separate by space
USER="parallels"
COPYFROM="/home/parallels/CopyFrom"
COPYTO="/home/parallels/CopyTo"
#FILENAME="collectedFiles.zip"

mkdir -p "$COPYTO"

#Loop through hosts to find and copy files
for HOST in "${VMS[@]}"; do
    echo "Connecting to $HOST"
    
    echo "Copying files from $HOST:$dir"
    scp -r "$USER@$HOST:\"$COPYFROM\"/*" "$COPYTO/"
done

#Zip files
cd "$COPYTO"

echo "Zipping files..."
zip -r "copiedFiles.zip" .

echo "files copied"

