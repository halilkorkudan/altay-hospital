<?php
// Oturumu başlat
session_start();

// Kullanıcı giriş yapmış mı kontrol et
if (!isset($_SESSION['username'])) {
    // Eğer giriş yapılmamışsa login sayfasına yönlendir
    header("Location: login.php");
    exit();
}
?>
