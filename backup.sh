#!/bin/bash

BACKUP_DIR="/var/backups"
LOG_FILE="/var/log/backup.log"
DATE=$(date +"%Y-%m-%d_%H-%M-%S")
FILENAME="hasta_kayit_$DATE.sql"

# ❗ mysql user ve password ortam değişkeninden alınıyor
MYSQL_USER="${MYSQL_USER:-myuser}"
MYSQL_PASSWORD="${MYSQL_PASSWORD:-mypassword}"
MYSQL_HOST="db"  # docker-compose servisi
MYSQL_DATABASE="hasta_kayit"

/usr/bin/mysqldump --no-tablespaces -h $MYSQL_HOST -u $MYSQL_USER -p$MYSQL_PASSWORD $MYSQL_DATABASE > "$BACKUP_DIR/$FILENAME"

if [ $? -eq 0 ]; then
    SIZE=$(du -h "$BACKUP_DIR/$FILENAME" | cut -f1)
    echo "$DATE | $FILENAME | $SIZE | Backup başarılı" >> "$LOG_FILE"
else
    echo "$DATE | HATA: Backup alınamadı" >> "$LOG_FILE"
fi
chown www-data:www-data /var/www/html/backups/
cp "$BACKUP_DIR/$FILENAME" /var/www/html/backups/

chown www-data:www-data /var/www/html/backups/$FILENAME
chmod 644 /var/www/html/backups/$FILENAME
