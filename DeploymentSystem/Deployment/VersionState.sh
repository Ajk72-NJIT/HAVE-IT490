#!/bin/bash

DB_USER="root"
DB_PASSWORD="sinnlig31"
DB="deploymentdb"
DB_HOST="localhost"

echo "Updating v$1 to state $2"

mysql -u "$DB_USER" -p"$DB_PASSWORD" "$DB" <<EOF
UPDATE bundle SET state='$2' WHERE version='$1';
EOF

QSTATUS=$?

if [ $QSTATUS -eq 0 ]; then 
	echo "State updated" 
else 
	echo "Error" 
fi


