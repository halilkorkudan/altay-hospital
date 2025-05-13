#!/bin/bash

# Apache'yi başlat
echo "Starting Apache..."
apache2ctl -D FOREGROUND &

# Cron servisinin loglarını takip edebilmek için (isteğe bağlı)
touch /var/log/cron.log

# Cron'u başlat
echo "Starting cron..."
cron

# Sonsuza kadar uyuyarak container'ın açık kalmasını sağla
# Alternatif olarak sadece 'cron -f' yazıp foreground çalıştırabilirsin.
tail -f /var/log/cron.log
