<?php
require_once 'config.php';
redirectIfNotUser();

// Get user leave data with status counts
$stmt = $pdo->prepare("SELECT * FROM cuti WHERE user_id = ? ORDER BY tanggal_pengajuan DESC");
$stmt->execute([$_SESSION['user_id']]);
$cutiList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate leave statistics
$totalCuti = count($cutiList);
$approvedCuti = count(array_filter($cutiList, fn($cuti) => $cuti['status'] === 'approved'));
$pendingCuti = count(array_filter($cutiList, fn($cuti) => $cuti['status'] === 'pending'));

// Process leave submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_cuti'])) {
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $jenis_cuti = $_POST['jenis_cuti'];
    $alasan = $_POST['alasan'];
    
    // Validate dates
    if (strtotime($tanggal_selesai) < strtotime($tanggal_mulai)) {
        $_SESSION['error'] = "Tanggal selesai tidak boleh sebelum tanggal mulai";
    } else {
        $stmt = $pdo->prepare("INSERT INTO cuti (user_id, tanggal_mulai, tanggal_selesai, jenis_cuti, alasan) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$_SESSION['user_id'], $tanggal_mulai, $tanggal_selesai, $jenis_cuti, $alasan])) {
            $_SESSION['success'] = "Pengajuan cuti berhasil dikirim";
            header("Location: dashboard_user.php");
            exit();
        } else {
            $_SESSION['error'] = "Gagal mengajukan cuti";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan - DeepSeek Cuti</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,line-clamp"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        deepseek: {
                            primary: '#0d1b2a',
                            secondary: '#1b263b',
                            accent: '#3a86ff',
                            dark: '#0a1128',
                            light: '#e2e8f0',
                            card: '#1f2937',
                            sidebar: '#1B2B4A'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .sidebar-collapsed {
            width: 5rem;
        }
        .sidebar-collapsed .sidebar-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        .floating-input:focus ~ label,
        .floating-input:not(:placeholder-shown) ~ label {
            @apply transform scale-75 -translate-y-4 text-deepseek-accent;
        }
        .status-badge {
            @apply px-3 py-1 rounded-full text-xs font-semibold;
        }
        .status-badge.pending {
            @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200;
        }
        .status-badge.approved {
            @apply bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200;
        }
        .status-badge.rejected {
            @apply bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200;
        }
        
        /* Perbaikan untuk sidebar mobile */
        #sidebar {
            transition: transform 0.3s ease-in-out;
            z-index: 40;
        }
        
        #sidebar.hidden {
            transform: translateX(-100%);
        }
        
        @media (min-width: 768px) {
            #sidebar {
                transform: none !important;
            }
            #sidebar.hidden {
                transform: none !important;
                display: block !important;
            }
        }
        
        #mobileMenuBtn {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 1000;
            background: #3a86ff;
            color: white;
            padding: 1rem;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
        }
    </style>
</head>
<body class="bg-deepseek-light dark:bg-deepseek-dark text-gray-800 dark:text-gray-200 transition-colors duration-200 flex h-screen overflow-hidden">
    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn" class="md:hidden fixed bottom-6 right-6 z-50 bg-deepseek-accent text-white p-3 rounded-full shadow-lg hover:scale-110 transition-transform">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <!-- Sidebar -->
    <aside id="sidebar" class="bg-deepseek-sidebar text-white h-screen fixed md:relative z-40 transition-all duration-300 ease-in-out w-64 md:translate-x-0">
        <!-- Sidebar Header -->
        <div class="p-4 border-b border-deepseek-secondary flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-deepseek-accent p-2 rounded-lg">
                    <i class="fas fa-robot text-xl"></i>
                </div>
                <span class="text-xl font-bold">DeepSeek Cuti</span>
            </div>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-b border-deepseek-secondary flex items-center space-x-3">
            <div class="bg-deepseek-accent rounded-full w-10 h-10 flex items-center justify-center text-white font-medium">
                <?= strtoupper(substr(htmlspecialchars($_SESSION['nama']), 0, 1)) ?>
            </div>
            <div>
                <p class="font-medium"><?= htmlspecialchars($_SESSION['nama']) ?></p>
                <p class="text-xs text-gray-300">Karyawan</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1 px-2">
                <li>
                    <a href="#" class="flex items-center space-x-3 p-3 rounded-lg bg-deepseek-secondary text-white">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-deepseek-secondary">
            <a href="logout.php" class="flex items-center space-x-3 p-3 rounded-lg text-gray-300 hover:bg-red-600 hover:text-white transition">
                <i class="fas fa-sign-out-alt"></i>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Header -->
        <header class="bg-white dark:bg-deepseek-card shadow-sm">
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-4">
                    <button id="mobileToggleSidebar" class="text-deepseek-dark dark:text-white md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-bold">Dashboard Karyawan</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="darkModeToggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-deepseek-secondary transition">
                        <i class="fas fa-moon dark:hidden"></i>
                        <i class="fas fa-sun hidden dark:block"></i>
                    </button>
                    <div class="hidden md:flex items-center space-x-2">
                        <div class="bg-deepseek-accent rounded-full w-8 h-8 flex items-center justify-center text-white">
                            <?= strtoupper(substr(htmlspecialchars($_SESSION['nama']), 0, 1)) ?>
                        </div>
                        <span class="font-medium"><?= htmlspecialchars($_SESSION['nama']) ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-4 bg-gray-50 dark:bg-deepseek-dark">
            <!-- Notification Alerts -->
            <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 animate-slide-up" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <p><?= $_SESSION['error'] ?></p>
                </div>
                <?php unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 animate-slide-up" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <p><?= $_SESSION['success'] ?></p>
                </div>
                <?php unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-deepseek-card rounded-xl shadow p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Total Cuti</p>
                            <h3 class="text-2xl font-bold"><?= $totalCuti ?></h3>
                        </div>
                        <div class="bg-deepseek-accent bg-opacity-10 p-3 rounded-full">
                            <i class="fas fa-calendar text-deepseek-accent text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-deepseek-card rounded-xl shadow p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Disetujui</p>
                            <h3 class="text-2xl font-bold"><?= $approvedCuti ?></h3>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-deepseek-card rounded-xl shadow p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">Menunggu</p>
                            <h3 class="text-2xl font-bold"><?= $pendingCuti ?></h3>
                        </div>
                        <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                            <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengajuan Cuti Form -->
            <section id="ajukan-cuti" class="bg-white dark:bg-deepseek-card rounded-xl shadow-sm p-4 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Ajukan Cuti Baru</h2>
                    <div class="bg-deepseek-accent/90 text-white px-2.5 py-1 rounded-md text-xs flex items-center">
                    </div>
                </div>
                <form action="dashboard_user.php" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Jenis Cuti -->
                        <div class="relative z-10">
                            <select name="jenis_cuti" id="jenis_cuti" class="form-select w-full px-3 py-2 text-sm border border-gray-300/80 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-deepseek-accent bg-white dark:bg-deepseek-card appearance-none" required>
                                <option value="" selected disabled>Pilih Jenis Cuti</option>
                                <?php foreach (getJenisCutiOptions() as $jenis): ?>
                                    <option value="<?= $jenis ?>"><?= $jenis ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-3 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                        
                        <!-- Tanggal Mulai -->
                        <div class="relative">
                            <label for="tanggal_mulai" class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full px-3 py-2 text-sm border border-gray-300/80 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-deepseek-accent bg-white dark:bg-deepseek-card" required>
                        </div>
                        
                        <!-- Tanggal Selesai -->
                        <div class="relative">
                            <label for="tanggal_selesai" class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="w-full px-3 py-2 text-sm border border-gray-300/80 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-deepseek-accent bg-white dark:bg-deepseek-card" required>
                        </div>
                        
                        <!-- Alasan -->
                        <div class="relative md:col-span-2">
                            <label for="alasan" class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Alasan Cuti</label>
                            <textarea name="alasan" id="alasan" rows="2" class="w-full px-3 py-2 text-sm border border-gray-300/80 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-1 focus:ring-deepseek-accent bg-white dark:bg-deepseek-card" required></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-2">
                        <button type="submit" name="submit_cuti" class="bg-deepseek-accent hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm flex items-center space-x-2 transition-colors duration-150">
                            <i class="fas fa-paper-plane text-xs"></i>
                            <span>Ajukan Cuti</span>
                        </button>
                    </div>
                </form>
            </section>

            <!-- Riwayat Cuti -->
            <section id="riwayat-cuti" class="bg-white dark:bg-deepseek-card rounded-xl shadow-md overflow-hidden animate-fade-in">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-xl font-semibold">Riwayat Pengajuan Cuti</h2>
                    <div class="relative w-64">
                        <input type="text" placeholder="Cari riwayat..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-deepseek-accent bg-white dark:bg-deepseek-secondary dark:border-gray-600">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-deepseek-primary text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tanggal Pengajuan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Jenis Cuti</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-deepseek-card divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($cutiList as $cuti): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-deepseek-secondary transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap"><?= date('d M Y', strtotime($cuti['tanggal_pengajuan'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($cuti['jenis_cuti']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?= date('d M Y', strtotime($cuti['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($cuti['tanggal_selesai'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge <?= $cuti['status'] ?>">
                                        <?= ucfirst($cuti['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openModal('<?= $cuti['id'] ?>')" class="text-deepseek-accent hover:text-blue-600 mr-3">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </button>
                                    <?php if ($cuti['status'] === 'pending'): ?>
                                    <button class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times mr-1"></i> Batalkan
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($cutiList)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-calendar-times text-5xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                        <p class="text-lg">Belum ada pengajuan cuti</p>
                                        <p class="text-sm mt-1">Ajukan cuti pertama Anda di atas</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (!empty($cutiList)): ?>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium"><?= count($cutiList) ?></span> dari <span class="font-medium"><?= count($cutiList) ?></span> hasil
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 border rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-deepseek-secondary hover:bg-gray-50 dark:hover:bg-deepseek-primary transition">
                            Sebelumnya
                        </button>
                        <button class="px-4 py-2 border rounded-md text-sm font-medium text-white bg-deepseek-accent hover:bg-blue-600 transition">
                            Selanjutnya
                        </button>
                    </div>
                </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-200">
        <div class="bg-white dark:bg-deepseek-card rounded-xl shadow-xl w-full max-w-2xl transform transition-all duration-200 scale-95 opacity-0" id="modalDialog">
            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <h3 class="text-xl font-bold">Detail Pengajuan Cuti</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6" id="modalContent">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end">
                <button onclick="closeModal()" class="bg-deepseek-accent hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        // Toggle mobile sidebar - Versi yang sudah diperbaiki
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggleSidebar = document.getElementById('mobileToggleSidebar');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            
            function toggleSidebar() {
                sidebar.classList.toggle('hidden');
                console.log('Sidebar toggled');
            }
            
            // Event listeners untuk kedua tombol
            if (mobileToggleSidebar) {
                mobileToggleSidebar.addEventListener('click', toggleSidebar);
            }
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', toggleSidebar);
            }
            
            // Close ketika klik di luar sidebar
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 768 && 
                    !sidebar.contains(e.target) && 
                    e.target !== mobileToggleSidebar && 
                    e.target !== mobileMenuBtn) {
                    sidebar.classList.add('hidden');
                }
            });
            
            // Dark mode toggle
            const darkModeToggle = document.getElementById('darkModeToggle');
            const html = document.documentElement;
            
            darkModeToggle.addEventListener('click', () => {
                html.classList.toggle('dark');
                localStorage.setItem('darkMode', html.classList.contains('dark'));
            });

            // Check for saved preference or system preference
            if (localStorage.getItem('darkMode') === 'true' || 
                (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                html.classList.add('dark');
            }

            // Date validation
            document.getElementById('tanggal_mulai').addEventListener('change', function() {
                const endDate = document.getElementById('tanggal_selesai');
                if (this.value) {
                    endDate.min = this.value;
                    if (endDate.value && endDate.value < this.value) {
                        endDate.value = this.value;
                    }
                }
            });
        });

        // Modal functions with animations
        function openModal(cutiId) {
            fetch('get_cuti_detail.php?id=' + cutiId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modalContent').innerHTML = data;
                    const modal = document.getElementById('detailModal');
                    const dialog = document.getElementById('modalDialog');
                    
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        dialog.classList.remove('scale-95', 'opacity-0');
                        dialog.classList.add('scale-100', 'opacity-100');
                    }, 10);
                });
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            const dialog = document.getElementById('modalDialog');
            
            dialog.classList.remove('scale-100', 'opacity-100');
            dialog.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
    </script>
</body>
</html>