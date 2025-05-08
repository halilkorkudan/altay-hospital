<?php
session_start();
session_unset();      // Tüm $_SESSION değişkenlerini temizler
session_destroy();    // Oturumu tamamen sonlandırır

// Tarayıcıda kalan çerezleri de silmek için (gerekirse):
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Giriş sayfasına yönlendir
header("Location: login.php");
exit();
