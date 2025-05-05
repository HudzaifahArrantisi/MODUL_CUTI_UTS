<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = trim($_POST['nik'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($nik) || empty($password)) {
        $error = "NIK dan Password isi!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE nik = ?");
        $stmt->execute([$nik]);
        $user = $stmt->fetch();
        
        if ($user) {
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['nik'] = $user['nik'];

                if ($user['role'] === 'admin') {
                    header("Location: dashboard_admin.php");
                } else {
                    header("Location: dashboard_user.php");
                }
                exit();
            } else {
                $error = "Password salah, Ler!";
            }
        } else {
            $error = "NIK salah, Ler!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Cuti DeepSeek</title>
    <link rel="icon" href="img/images.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --deepseek-blue: #0066cc;
            --deepseek-light: #e6f2ff;
            --deepseek-dark: #004080;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            min-height: 100vh;
            align-items: center;
            background: linear-gradient(135deg, var(--deepseek-light) 0%, #ffffff 100%);
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
            padding: 2.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 102, 204, 0.1);
            border-top: 4px solid var(--deepseek-blue);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo {
            height: 60px;
            margin-bottom: 1rem;
        }
        
        .login-title {
            color: var(--deepseek-dark);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
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
            border: none;
            transition: all 0.3s;
        }
        
        .btn-deepseek:hover {
            background-color: var(--deepseek-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 102, 204, 0.2);
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
        }
        
        .login-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
        }
        
        .login-footer a {
            color: var(--deepseek-blue);
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .alert {
            border-left: 4px solid var(--deepseek-blue);
        }
        
        @media (max-width: 576px) {
            .login-container {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <img src="img/deep.png" alt="DeepSeek Logo" class="login-logo">
                <h3 class="login-title">Sistem Cuti Karyawan</h3>
                <p class="login-subtitle">Masukkan NIK dan password untuk mengakses sistem</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nik" class="form-label">Nomor Induk Karyawan (NIK)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                        <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK Anda" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password Anda" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-deepseek btn-lg">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </button>
                </div>
                
                <div class="text-center mt-3">
    <a href="lupa_password.php" class="text-decoration-none text-deepseek-blue">
        <i class="bi bi-question-circle"></i> Lupa Password?
    </a>
</div>
            </form>

            <div class="login-footer">
                <p>Belum memiliki akun? <a href="register.php">Daftar disini</a></p>
                <a href="index.php" class="d-inline-flex align-items-center">
                    <i class="bi bi-arrow-left-short"></i> Kembali ke beranda
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
        
        // Add animation to login button on hover
        const loginBtn = document.querySelector('.btn-deepseek');
        loginBtn.addEventListener('mouseenter', () => {
            loginBtn.style.transform = 'translateY(-2px)';
        });
        loginBtn.addEventListener('mouseleave', () => {
            loginBtn.style.transform = 'translateY(0)';
        });
    </script>
</body>
</html>