<?php
$kullanici_adi = $_POST['kullanici_adi'];
$sifre = $_POST['sifre'];


$host = 'db';
$db_user = 'myuser';
$db_pass = 'mypassword';
$db_name = 'hasta_kayit';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}


$kontrol = $conn->prepare("SELECT id FROM users WHERE username = ?");
$kontrol->bind_param("s", $kullanici_adi);
$kontrol->execute();
$kontrol->store_result();

if ($kontrol->num_rows > 0) {
    echo "<script>alert('Bu kullanıcı adı zaten alınmış. Lütfen başka bir kullanıcı adı deneyin.'); window.history.back();</script>";
    exit();
}


$hashli_sifre = password_hash($sifre, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $kullanici_adi, $hashli_sifre);

if ($stmt->execute()) {
    
    echo '
    <!DOCTYPE html>
    <html lang="tr">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <title>Kayıt Başarılı</title>
      <style>
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
          height: 100vh;
          background: linear-gradient(135deg, #007BFF, #ffffff);
          display: flex;
          justify-content: center;
          align-items: center;
        }
        .message-box {
          background-color: #ffffff;
          border-radius: 10px;
          padding: 40px 30px;
          width: 100%;
          max-width: 400px;
          box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
          text-align: center;
        }
        .message-box h2 {
          color: #007BFF;
          margin-bottom: 20px;
        }
        .message-box p {
          font-size: 16px;
          color: #333;
        }
      </style>
      <script>
        setTimeout(function() {
          window.location.href = "login.html";
        }, 3000);
      </script>
    </head>
    <body>
      <div class="message-box">
        <h2>Teşekkürler!</h2>
        <p>Kayıt işleminiz başarıyla tamamlandı.<br>Giriş sayfasına yönlendiriliyorsunuz...</p>
      </div>
    </body>
    </html>';
} else {
    echo "Hata oluştu: " . $stmt->error;
}


$stmt->close();
$kontrol->close();
$conn->close();
?>
