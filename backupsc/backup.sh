#!/bin/bash

BACKUP_DIR="/var/backups"
LOG_FILE="/var/log/backup.log"
DATE=$(date +"%Y-%m-%d_%H-%M-%S")
FILENAME="hasta_kayit_$DATE.sql"

mysqldump --no-tablespaces -u halil hasta_kayit > "$BACKUP_DIR/$FILENAME"

if [ $? -eq 0 ]; then
    SIZE=$(du -h "$BACKUP_DIR/$FILENAME" | cut -f1)
    echo "$DATE | $FILENAME | $SIZE | Backup başarılı" >> "$LOG_FILE"
else
    echo "$DATE | HATA: Backup alınamadı" >> "$LOG_FILE"
fi

cp "$BACKUP_DIR/$FILENAME" /var/www/html/altay/backups

sudo chown www-data:www-data /var/www/html/altay/backups/$FILENAME
sudo chmod 644 /var/www/html/altay/backups/$FILENAME