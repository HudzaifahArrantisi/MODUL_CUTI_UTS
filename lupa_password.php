<?php
include 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nik = $_POST['nik'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validasi input
    if (empty($nik) || empty($nama)) {
        $error = 'NIK dan Nama harus diisi';
    } elseif (empty($new_password) || empty($confirm_password)) {
        $error = 'Password baru dan konfirmasi harus diisi';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Konfirmasi password tidak sama';
    } elseif (strlen($new_password) < 8) {
        $error = 'Password minimal 8 karakter';
    } else {
        // Verifikasi identitas user berdasarkan NIK dan Nama
        $stmt = $pdo->prepare("SELECT id FROM users WHERE nik = ? AND nama = ?");
        $stmt->execute([$nik, $nama]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Update password TANPA HASH
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            
            if ($stmt->execute([$new_password, $user['id']])) {
                $success = "Password berhasil direset! Silakan login dengan password baru Anda.";
            } else {
                $error = "Gagal mereset password. Silakan coba lagi.";
            }
        } else {
            $error = "NIK dan Nama tidak cocok dengan data kami.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - DeepSeek</title>
    <link rel="icon" href="img/images.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --deepseek-blue: #0066cc;
            --deepseek-light: #e6f2ff;
            --deepseek-dark: #004080;
            --deepseek-accent: #00aaff;
        }
        
        body {
            background-color: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .deepseek-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.15);
            overflow: hidden;
        }
        
        .deepseek-header {
            background: linear-gradient(135deg, var(--deepseek-blue) 0%, var(--deepseek-dark) 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .deepseek-body {
            padding: 2rem;
            background: white;
        }
        
        .btn-deepseek {
            background-color: var(--deepseek-blue);
            color: white;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-deepseek:hover {
            background-color: var(--deepseek-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 102, 204, 0.2);
        }
        
        .form-control:focus {
            border-color: var(--deepseek-accent);
            box-shadow: 0 0 0 0.25rem rgba(0, 170, 255, 0.25);
        }
        
        .deepseek-icon {
            color: var(--deepseek-blue);
            font-size: 1.2rem;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="deepseek-card">
                    <div class="deepseek-header">
                        <h3><i class="bi bi-shield-lock"></i> Reset Password</h3>
                        <p class="mb-0">Verifikasi identitas Anda</p>
                    </div>
                    <div class="deepseek-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $error ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <?= $success ?>
                                <div class="text-center mt-3">
                                    <a href="login.php" class="btn btn-deepseek">
                                        <i class="bi bi-box-arrow-in-right"></i> Ke Halaman Login
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <form action="lupa_password.php" method="POST">
                                <div class="mb-4">
                                    <label for="nik" class="form-label fw-bold">
                                        <i class="bi bi-credit-card deepseek-icon"></i>NIK
                                    </label>
                                    <input type="text" class="form-control py-2" id="nik" name="nik" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="nama" class="form-label fw-bold">
                                        <i class="bi bi-person deepseek-icon"></i>Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control py-2" id="nama" name="nama" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="new_password" class="form-label fw-bold">
                                        <i class="bi bi-key deepseek-icon"></i>Password Baru
                                    </label>
                                    <input type="password" class="form-control py-2" id="new_password" name="new_password" required minlength="8">
                                    <small class="text-muted">Minimal 8 karakter</small>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label fw-bold">
                                        <i class="bi bi-key-fill deepseek-icon"></i>Konfirmasi Password
                                    </label>
                                    <input type="password" class="form-control py-2" id="confirm_password" name="confirm_password" required minlength="8">
                                </div>
                                
                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-deepseek py-2">
                                        <i class="bi bi-arrow-repeat"></i> Reset Password
                                    </button>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <a href="login.php" class="text-decoration-none text-deepseek-blue">
                                        <i class="bi bi-arrow-left"></i> Kembali ke Login
                                    </a>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animasi untuk input
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.querySelector('label').style.color = '#0066cc';
            });
            
            input.addEventListener('blur', function() {
                this.parentNode.querySelector('label').style.color = '';
            });
        });
    </script>
</body>
</html>