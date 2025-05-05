<?php
include 'config.php';

// Proses registrasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];
    $password = $_POST['password'];

    // Insert data ke database
    $stmt = $pdo->prepare("INSERT INTO users (nama, nik, jenis_kelamin, tempat_lahir, tanggal_lahir, no_telp, alamat, password, role) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nama, $nik, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $no_telp, $alamat, $password, 'user']);

    // Redirect ke halaman login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Cuti DeepSeek</title>
    <link rel="icon" href="img/images.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --deepseek-blue: #0066cc;
            --deepseek-light: #e6f2ff;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .register-container {
            max-width: 550px;
            margin: 40px auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 102, 204, 0.1);
            border-top: 5px solid var(--deepseek-blue);
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-container {
            margin-bottom: 20px;
        }
        
        .logo-container img {
            height: 50px;
            margin-bottom: 10px;
        }
        
        .form-control:focus {
            border-color: var(--deepseek-blue);
            box-shadow: 0 0 0 0.25rem rgba(0, 102, 204, 0.25);
        }
        
        .btn-deepseek {
            background-color: var(--deepseek-blue);
            color: white;
            font-weight: 600;
            padding: 10px;
            transition: all 0.3s;
        }
        
        .btn-deepseek:hover {
            background-color: #0052a3;
            color: white;
            transform: translateY(-2px);
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        
        .link-deepseek {
            color: var(--deepseek-blue);
            text-decoration: none;
            font-weight: 500;
        }
        
        .link-deepseek:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="register-header">
                <div class="logo-container">
                    <img src="img/deep.png" alt="DeepSeek Logo">
                </div>
                <h2 class="mb-3" style="color: var(--deepseek-blue);">Daftar Akun DeepSeek</h2>
                <p class="text-muted">Silahkan lengkapi data diri Anda</p>
            </div>

            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="tel" class="form-control" id="no_telp" name="no_telp" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-deepseek w-100 mt-2 mb-3">Daftar Sekarang</button>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label" for="terms">
                        Saya menyetujui <a href="#" class="link-deepseek">Syarat & Ketentuan</a> DeepSeek
                    </label>
                </div>
            </form>

            <div class="text-center mt-4">
                <p>Sudah memiliki akun? <a href="login.php" class="link-deepseek">Masuk disini</a></p>
                <a href="index.php" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>