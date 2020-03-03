#!/bin/bash

mkdir -p backups

TIME=`date '+%Y-%m-%d_%H-%M-%S'`

echo "Making Database backup..."
docker exec -it get_db mysqldump --max_allowed_packet=1G -u"admin" -p"admin" data | gzip -c -9 > "backups/${TIME}_DATABASE.sql.gz"

# TO RESTORE DB
# cat backup.sql | docker exec -i $CONTAINER sh -c 'mysql -u"admin" -p"admin"'
