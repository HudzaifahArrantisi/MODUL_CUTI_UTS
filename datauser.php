<?php
include 'config.php';

// Check admin session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Get logged in admin data
$userId = $_SESSION['user_id'];
$stmtUser = $pdo->prepare("SELECT nama FROM users WHERE id = ?");
$stmtUser->execute([$userId]);
$user = $stmtUser->fetch();
$namaAdmin = $user['nama'] ?? 'Admin';

// Get all users data
$stmt = $pdo->query("SELECT id, nama, nik, role, password FROM users");
$userList = $stmt->fetchAll();

// Get total user count
$stmtCount = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$totalUsers = $stmtCount->fetch()['total_users'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User - Admin DeepSeek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #000;
            font-family: 'Courier New', monospace;
            color: #00ff00;
        }

        .hacker-header {
            color: #00ff00;
            text-shadow: 0 0 10px #00ff00;
        }

        .table-hacker th, .table-hacker td {
            color: #00ff00;
            padding: 8px;
        }

        .table-hacker {
            border: 1px solid #00ff00;
            width: 100%;
            border-collapse: collapse;
            background-color: #111;
        }

        .table-hacker th {
            background-color: #222;
        }

        .table-hacker td {
            background-color: #000;
        }

        .button-hacker {
            background-color: #111;
            color: #00ff00;
            padding: 10px 20px;
            border: 2px solid #00ff00;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .button-hacker:hover {
            background-color: #222;
        }

        .button-hacker:focus {
            outline: none;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo img {
            height: 50px;
            margin-right: 15px;
        }

        .blur-password {
            filter: blur(4px);
        }

        .show-password {
            color: #00ff00;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <div class="logo">
            <img src="img/deep.png" alt="DeepSeek Logo">
            <h1 class="text-2xl hacker-header">Admin Panel - DeepSeek</h1>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-bold hacker-header">Data User</h2>
        </div>

        <div class="bg-black p-4 rounded-lg">
            <div class="mb-4">
                <h3 class="hacker-header text-lg">Total Pengguna: <span class="font-bold"> <?= $totalUsers ?> </span></h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table-hacker">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userList as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['nik'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['nama']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($user['role'])) ?></td>
                            <td class="relative">
                                <span class="blur-password" id="password-<?= $user['id'] ?>">
                                    <?= htmlspecialchars($user['password']) ?>
                                </span>
                                <button class="show-password" onclick="togglePassword(<?= $user['id'] ?>)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <a href="dashboard_admin.php">
                <button class="button-hacker">Kembali ke Dashboard</button>
            </a>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const passwordSpan = document.getElementById(`password-${id}`);
            const isBlurred = passwordSpan.classList.contains('blur-password');
            const icon = passwordSpan.nextElementSibling.querySelector('i');

            if (isBlurred) {
                passwordSpan.classList.remove('blur-password');
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordSpan.classList.add('blur-password');
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
