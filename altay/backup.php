<?php
session_start();
require 'auth.php'; // Giriş kontrolü

$log_file = "/var/log/backup.log";
$backup_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['backup'])) {
        $output = null;
        $return_var = null;
        exec("sudo /usr/local/bin/backup.sh", $output, $return_var);

        if ($return_var === 0) {
            $_SESSION['backup_message'] = "<p style='color:green;'>Backup başarıyla alındı.</p>";
        } else {
            $_SESSION['backup_message'] = "<p style='color:red;'>Backup alınırken hata oluştu.</p>";
        }

        header("Location: backup.php");
        exit();
    }

}


// Sayfa yüklendiğinde session mesajını al
$backup_message = '';
if (isset($_SESSION['backup_message'])) {
    $backup_message = $_SESSION['backup_message'];
    unset($_SESSION['backup_message']);
}


// Log dosyasını oku
$log_content = "";
if (file_exists($log_file)) {
    $log_content = htmlspecialchars(file_get_contents($log_file));
} else {
    $log_content = "Log dosyası bulunamadı.";
}

// En son yedek dosyasını bul
$backup_dir = "/var/www/html/altay/backups/";
$files = glob($backup_dir . "*.sql");
$latest_file = '';

if (count($files) > 0) {
    array_multisort(array_map('filemtime', $files), SORT_DESC, $files);
    $latest_file = basename($files[0]);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Backup Yönetimi</title>
    <style>
        body {
            background: linear-gradient(135deg, #007BFF, #ffffff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            max-width: 900px;
            width: 100%;
        }
        h2 {
            color: #007BFF;
            text-align: center;
        }
        textarea {
            width: 100%;
            height: 300px;
            margin-top: 10px;
            padding: 10px;
            font-family: monospace;
        }
        form {
            text-align: center;
            margin-top: 20px;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 10px;
        }
        .download-link {
            text-align: center;
            margin-top: 20px;
        }
        .download-link a {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .download-link a:hover {
            background: #218838;
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
        .logout:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Backup Yönetimi</h2>

    <?php echo $backup_message; ?>


<form method="POST">
    <button type="submit" name="backup">Backup Al</button>
</form>


    <div class="download-link">
        <?php if ($latest_file): ?>
            <h3>En Son Yedek Dosyası</h3>
            <a href="/altay/backups/<?php echo htmlspecialchars($latest_file); ?>" download>Yedeği İndir</a>
        <?php else: ?>
            <p>Henüz bir yedek dosyası bulunamadı.</p>
        <?php endif; ?>
    </div>

    <h3>Backup Logları</h3>
    <textarea readonly><?php echo $log_content; ?></textarea>

    <a class="logout" href="dashboard.php">Geri Dön</a>
</div>
<script>
window.addEventListener("beforeunload", function () {
    navigator.sendBeacon("logout.php");
});
</script>

</body>
</html>
