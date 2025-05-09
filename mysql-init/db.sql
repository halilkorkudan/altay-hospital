-- Veritabanı oluştur
CREATE DATABASE IF NOT EXISTS hasta_kayit;
USE hasta_kayit;

-- Kullanıcılar tablosu
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Hastalar tablosu
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(50) NOT NULL,
    soyad VARCHAR(50) NOT NULL,
    tc_kimlik_no VARCHAR(11) NOT NULL UNIQUE,
    telefon_no VARCHAR(15),
    bolum VARCHAR(100),
    sikayet TEXT,
    kayit_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


