<?php
session_start();

require 'auth.php';

$servername = "db";
$username = "myuser";  // kendi kullanıcı adın
$password = "mypassword";  // kendi şifren
$dbname = "hasta_kayit";

// Bağlantıyı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

// Form gönderilmiş mi kontrolü
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $tc_kimlik_no = $_POST['tc_kimlik_no'];
    $telefon_no = $_POST['telefon_no'];
    $bolum = $_POST['bolum'];
    $sikayet = $_POST['sikayet'];
    $kayit_tarihi = date("Y-m-d");

    // Basit doğrulama
    if (!empty($ad) && !empty($soyad) && !empty($tc_kimlik_no) && !empty($telefon_no) && !empty($bolum) && !empty($sikayet)) {
        $stmt = $conn->prepare("INSERT INTO patients (ad, soyad, tc_kimlik_no, telefon_no, bolum, sikayet, kayit_tarihi) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $ad, $soyad, $tc_kimlik_no, $telefon_no, $bolum, $sikayet, $kayit_tarihi);

        if ($stmt->execute()) {
            $message = "Yeni hasta kaydedildi!";
        } else {
            $message = "Hata: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Lütfen tüm alanları doldurun.";
    }
}

// Hasta verilerini çek
$sql = "SELECT id, ad, soyad, tc_kimlik_no, telefon_no, bolum, sikayet, kayit_tarihi FROM patients ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            background: linear-gradient(135deg, #007BFF, #ffffff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }
        form {
            margin-bottom: 30px;
        }
        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin-top: 10px;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            color: green;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .login {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background: #dc3545;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        text-align: center;
        float: right; /* Butonu sağa hizalar */
}

        .logout:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Hasta Kayıt Formu</h2>

    <?php if (isset($message)) { echo "<div class='message'>" . htmlspecialchars($message) . "</div>"; } ?>

    <form method="POST" action="">
        <label>Ad:</label>
        <input type="text" name="ad" required>

        <label>Soyad:</label>
        <input type="text" name="soyad" required>

        <label>TC Kimlik No:</label>
        <input type="text" name="tc_kimlik_no" required>

        <label>Telefon No:</label>
        <input type="text" name="telefon_no" required>

        <label>Bölüm:</label>
        <input type="text" name="bolum" required>

        <label>Şikayet:</label>
        <input type="text" name="sikayet" required>

        <input type="submit" value="Kaydet">
    </form>

    <h2>Hasta Listesi</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Ad</th>
            <th>Soyad</th>
            <th>TC Kimlik No</th>
            <th>Telefon No</th>
            <th>Bölüm</th>
            <th>Şikayet</th>
            <th>Kayıt Tarihi</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".htmlspecialchars($row['id'])."</td>
                        <td>".htmlspecialchars($row['ad'])."</td>
                        <td>".htmlspecialchars($row['soyad'])."</td>
                        <td>".htmlspecialchars($row['tc_kimlik_no'])."</td>
                        <td>".htmlspecialchars($row['telefon_no'])."</td>
                        <td>".htmlspecialchars($row['bolum'])."</td>
                        <td>".htmlspecialchars($row['sikayet'])."</td>
                        <td>".htmlspecialchars($row['kayit_tarihi'])."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Kayıtlı hasta bulunamadı.</td></tr>";
        }
        ?>
    </table>

    <a class="logout" href="logout.php">Çıkış Yap</a>
    <a class="login" href="backup.php">Backup Sayfası</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
