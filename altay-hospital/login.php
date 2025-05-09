<?php
session_start(); 

$kullanici_adi = $_POST['kullanici_adi'] ?? '';
$sifre = $_POST['sifre'] ?? '';

$host = 'db';
$db_user = 'myuser';
$db_pass = 'mypassword';
$db_name = 'hasta_kayit';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kullanici_adi);
$stmt->execute();
$result = $stmt->get_result();

function mesajGoster($baslik, $mesaj, $renk, $yol) {
    echo "
    <html>
    <head>
    <meta charset='UTF-8'>
    <title>$baslik</title>
    <style>
        body {
            background: linear-gradient(135deg, #007BFF, #ffffff);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .message-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .message-box h2 {
            color: $renk;
            margin-bottom: 15px;
        }
    </style>
    <script>
        setTimeout(function(){
            window.location.href = '$yol';
        }, 3000);
    </script>
    </head>
    <body>
        <div class='message-box'>
            <h2>$baslik</h2>
            <p>$mesaj</p>
            <p>3 saniye içinde yönlendiriliyorsunuz...</p>
        </div>
    </body>
    </html>
    ";
}

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($sifre, $user['password'])) {
        $_SESSION['username'] = $kullanici_adi;

        mesajGoster("Giriş Başarılı", "Hoş geldiniz, $kullanici_adi!", "#28a745", "dashboard.php");
    } else {
        mesajGoster("Giriş Başarısız", "Lütfen tekrar deneyin.", "#dc3545", "login.html");
    }
} else {
    mesajGoster("Giriş Başarısız", "Lütfen tekrar deneyin.", "#dc3545", "login.html");
}

$stmt->close();
$conn->close();
?>
