#!/bin/bash

VMS=("DMZ:10.211.55.5" "DBR:10.211.55.5" "FRE:10.211.55.7") #separate by space, identify by 3 characters
USER="parallels"
COPYFROM="/home/parallels/HAVE-FRIDGE"
COPYTO="/home/parallels/HAVE-FRIDGE"

#DB 
DB_USER="root"
DB_PASSWORD="sinnlig31"
DB="deploymentdb"
DB_HOST="localhost"

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

mysql -u "$DB_USER" -p"$DB_PASSWORD" "$DB" <<EOF
INSERT INTO bundle (bundle_name, version) VALUES ('$ZIPNAME', '$VERSION');
EOF

echo "files copied and inserted into DB"

