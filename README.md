# Altay Hospital - Hasta Kayıt Sistemi

Bu proje, Docker Compose kullanılarak geliştirilmiş PHP ve MySQL tabanlı bir **hasta kayıt sistemi** uygulamasıdır.  
Web arayüzünden giriş yapabilir, hasta kayıtlarını görüntüleyebilir ve **veritabanının yedeğini alabilirsiniz.**

## Özellikler

- PHP 8.3 ve Apache
- MySQL 8.0 veritabanı
- Docker ve Docker Compose ile taşınabilir altyapı
- Web arayüzünden tek tıkla mysqldump ile yedek alma
- Yedek dosyaları `/var/www/html/backups/` dizininde saklanır.

## Kurulum ve Çalıştırma

### Gereksinimler

- Docker
- Docker Compose

### Adımlar

1. Bu projeyi klonlayın:
   ```bash
   git clone https://github.com/halilkorkudan/altay-hospital.git
   cd altay-hospital
2. Docker containerlarını başlatın:
   ```bash
   docker-compose up --build
3. Tarayıcıdan uygulamaya erişin:
   http://localhost:8080
4. Docker containerlarını durdurmak için:
   ```bash
   docker-compose down
