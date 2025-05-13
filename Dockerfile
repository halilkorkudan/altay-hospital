FROM php:8.3-apache

# PHP eklentileri
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Apache ayarları
RUN a2enmod rewrite

# mysqldump için mysql-client
RUN apt-get update && apt-get install -y default-mysql-client \
cron

# Gerekli klasörleri oluştur
RUN mkdir -p /var/backups /var/log /var/www/html/altay/backups
RUN mkdir -p /var/www/html/backups && chown www-data:www-data /var/www/html/backups
# Klasör izinlerini www-data kullanıcısına ver
RUN chown -R www-data:www-data /var/backups /var/log /var/www/html/altay/backups \
    && chmod -R 775 /var/backups /var/log /var/www/html/altay/backups

# Script dosyasını image içine kopyala
COPY backup.sh /usr/local/bin/backup.sh

# Script çalıştırılabilir yap
RUN chmod +x /usr/local/bin/backup.sh

RUN echo "0 2 * * 0 root /usr/local/bin/backup.sh" >> /etc/crontab

# Apache port
EXPOSE 80

COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]

