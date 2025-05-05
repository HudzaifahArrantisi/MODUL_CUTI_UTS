<?php
require_once 'config.php';
redirectIfNotAdmin();

// Get leave data
$stmt = $pdo->prepare("SELECT c.*, u.nama as nama_user FROM cuti c JOIN users u ON c.user_id = u.id ORDER BY c.tanggal_pengajuan DESC");
$stmt->execute();
$cutiList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $cutiId = $_POST['cuti_id'];
        $catatan = $_POST['catatan'] ?? '';
        
        switch ($_POST['action']) {
            case 'approve':
                $stmt = $pdo->prepare("UPDATE cuti SET status = 'approved', catatan_admin = ? WHERE id = ?");
                $stmt->execute([$catatan, $cutiId]);
                break;
            case 'reject':
                $stmt = $pdo->prepare("UPDATE cuti SET status = 'rejected', catatan_admin = ? WHERE id = ?");
                $stmt->execute([$catatan, $cutiId]);
                break;
            case 'delete':
                $stmt = $pdo->prepare("DELETE FROM cuti WHERE id = ?");
                $stmt->execute([$cutiId]);
                break;
        }
        header("Location: dashboard_admin.php");
        exit();
    }
}

// Calculate stats for dashboard
$totalRequests = count($cutiList);
$pendingRequests = count(array_filter($cutiList, fn($cuti) => $cuti['status'] === 'pending'));
$approvedRequests = count(array_filter($cutiList, fn($cuti) => $cuti['status'] === 'approved'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DeepSeek Leave Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                },
                extend: {
                    colors: {
                        deepseek: {
                            primary: {
                                50: '#f0f9ff',
                                100: '#e0f2fe',
                                200: '#bae6fd',
                                300: '#7dd3fc',
                                400: '#38bdf8',
                                500: '#0ea5e9',
                                600: '#0284c7',
                                700: '#0369a1',
                                800: '#075985',
                                900: '#0c4a6e',
                                950: '#082f49'
                            },
                            dark: {
                                50: '#f8fafc',
                                100: '#f1f5f9',
                                200: '#e2e8f0',
                                300: '#cbd5e1',
                                400: '#94a3b8',
                                500: '#64748b',
                                600: '#475569',
                                700: '#334155',
                                800: '#1e293b',
                                900: '#0f172a',
                                950: '#020617'
                            },
                            accent: {
                                light: '#a5f3fc',
                                DEFAULT: '#06b6d4',
                                dark: '#0e7490'
                            }
                        }
                    },
                    boxShadow: {
                        'deepseek': '0 4px 14px 0 rgba(6, 182, 212, 0.15)',
                        'deepseek-lg': '0 10px 25px -5px rgba(6, 182, 212, 0.1)'
                    }
                }
            }
        }
    </script>
    <style>
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .sidebar-item {
            transition: all 0.3s ease;
        }
        .sidebar-item:hover {
            transform: translateX(4px);
        }
        .status-badge {
            transition: all 0.2s ease;
        }
        .status-badge:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-deepseek-dark-100 min-h-screen font-sans">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 w-64 bg-deepseek-dark-900 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50" id="sidebar">
        <div class="flex items-center justify-between p-4 border-b border-deepseek-dark-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-deepseek-accent-DEFAULT flex items-center justify-center">
                    <i class="fas fa-robot text-xl text-white"></i>
                </div>
                <span class="text-xl font-bold">PT DeepSeek</span>
            </div>
            <button id="closeSidebar" class="md:hidden text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="p-4">
            <div class="mb-8 mt-4">
                <div class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-deepseek-dark-800">
                    <div class="w-10 h-10 rounded-full bg-deepseek-accent-DEFAULT flex items-center justify-center text-white">
                        <?= substr(htmlspecialchars($_SESSION['nama']), 0, 1) ?>
                    </div>
                    <div>
                        <p class="font-medium"><?= htmlspecialchars($_SESSION['nama']) ?></p>
                        <p class="text-xs text-deepseek-dark-400">Admin</p>
                    </div>
                </div>
            </div>
            
            <ul class="space-y-2">
                <li>
                    <a href="dashboard_admin.php" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg bg-deepseek-dark-800 text-deepseek-accent-DEFAULT">
                        <i class="fas fa-tachometer-alt w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="datauser.php" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg text-deepseek-dark-300 hover:bg-deepseek-dark-800 hover:text-white">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span>Manage Users</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-deepseek-dark-700">
            <a href="logout.php" class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg text-deepseek-dark-300 hover:bg-deepseek-dark-800 hover:text-red-400">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="md:ml-64 transition-all duration-300">
        <!-- Mobile Header -->
        <header class="md:hidden bg-deepseek-dark-900 text-white p-4 flex justify-between items-center sticky top-0 z-40">
            <button id="openSidebar" class="text-white">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="text-xl font-bold">Admin Dashboard</h1>
            <div class="w-8"></div> <!-- Spacer for balance -->
        </header>

        <!-- Desktop Header -->
        <header class="hidden md:block bg-white shadow-sm">
            <div class="px-6 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-deepseek-dark-800">Leave Request Management</h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full bg-deepseek-accent-DEFAULT flex items-center justify-center text-white text-sm font-medium">
                            <?= substr(htmlspecialchars($_SESSION['nama']), 0, 1) ?>
                        </div>
                        <span class="text-sm font-bold text-deepseek-dark-700"><?= htmlspecialchars($_SESSION['nama']) ?></span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-deepseek p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-deepseek-dark-500">Total Requests</p>
                            <h3 class="text-2xl font-bold mt-1 text-deepseek-dark-900"><?= $totalRequests ?></h3>
                        </div>
                        <div class="p-3 rounded-lg bg-deepseek-primary-100 text-deepseek-primary-600">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-2 bg-deepseek-dark-100 rounded-full overflow-hidden">
                            <div class="h-full bg-deepseek-accent-DEFAULT rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-deepseek p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-deepseek-dark-500">Pending</p>
                            <h3 class="text-2xl font-bold mt-1 text-deepseek-dark-900"><?= $pendingRequests ?></h3>
                        </div>
                        <div class="p-3 rounded-lg bg-yellow-100 text-yellow-600">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-2 bg-deepseek-dark-100 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-500 rounded-full" style="width: <?= $totalRequests ? ($pendingRequests / $totalRequests * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-deepseek p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-deepseek-dark-500">Approved</p>
                            <h3 class="text-2xl font-bold mt-1 text-deepseek-dark-900"><?= $approvedRequests ?></h3>
                        </div>
                        <div class="p-3 rounded-lg bg-green-100 text-green-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="h-2 bg-deepseek-dark-100 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full" style="width: <?= $totalRequests ? ($approvedRequests / $totalRequests * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leave Requests Table -->
            <div class="bg-white rounded-xl shadow-deepseek overflow-hidden">
                <div class="px-6 py-4 border-b border-deepseek-dark-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <h2 class="text-lg font-semibold text-deepseek-dark-800">Recent Leave Requests</h2>
                    <div class="relative w-full md:w-auto">
                        <input type="text" placeholder="Search requests..." class="w-full md:w-64 pl-10 pr-4 py-2 border border-deepseek-dark-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-deepseek-accent-DEFAULT focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-deepseek-dark-400"></i>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-deepseek-dark-100">
                        <thead class="bg-deepseek-dark-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-deepseek-dark-500 uppercase tracking-wider">Employee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-deepseek-dark-500 uppercase tracking-wider">Request Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-deepseek-dark-500 uppercase tracking-wider">Leave Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-deepseek-dark-500 uppercase tracking-wider">Period</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-deepseek-dark-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-deepseek-dark-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-deepseek-dark-100">
                            <?php foreach ($cutiList as $cuti): ?>
                            <tr class="hover:bg-deepseek-dark-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-deepseek-primary-100 flex items-center justify-center text-deepseek-primary-600 font-medium">
                                            <?= substr(htmlspecialchars($cuti['nama_user']), 0, 1) ?>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-deepseek-dark-900"><?= htmlspecialchars($cuti['nama_user']) ?></div>
                                            <div class="text-sm text-deepseek-dark-500">ID: <?= $cuti['user_id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-deepseek-dark-900"><?= date('d M Y', strtotime($cuti['tanggal_pengajuan'])) ?></div>
                                    <div class="text-xs text-deepseek-dark-500"><?= date('H:i', strtotime($cuti['tanggal_pengajuan'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-deepseek-dark-900"><?= htmlspecialchars($cuti['jenis_cuti']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-deepseek-dark-900">
                                        <?= date('d M', strtotime($cuti['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($cuti['tanggal_selesai'])) ?>
                                    </div>
                                    <div class="text-xs text-deepseek-dark-500">
                                        <?php 
                                        $start = new DateTime($cuti['tanggal_mulai']);
                                        $end = new DateTime($cuti['tanggal_selesai']);
                                        $days = $start->diff($end)->days + 1;
                                        echo $days . ' day' . ($days > 1 ? 's' : '');
                                        ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
                                        'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                                        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle']
                                    ];
                                    $status = $cuti['status'];
                                    ?>
                                    <span class="status-badge px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusConfig[$status]['bg'] ?> <?= $statusConfig[$status]['text'] ?>">
                                        <i class="fas <?= $statusConfig[$status]['icon'] ?> mr-1 mt-0.5"></i>
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button onclick="openModal('<?= $cuti['id'] ?>')" class="text-deepseek-primary-600 hover:text-deepseek-primary-800 p-2 rounded-lg hover:bg-deepseek-primary-50 transition-colors duration-200" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <?php if ($cuti['status'] === 'pending'): ?>
                                        <form action="dashboard_admin.php" method="POST" class="inline">
                                            <input type="hidden" name="cuti_id" value="<?= $cuti['id'] ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-50 transition-colors duration-200" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        
                                        <button onclick="openRejectModal('<?= $cuti['id'] ?>')" class="text-yellow-600 hover:text-yellow-800 p-2 rounded-lg hover:bg-yellow-50 transition-colors duration-200" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                        
                                        <form action="dashboard_admin.php" method="POST" class="inline">
                                            <input type="hidden" name="cuti_id" value="<?= $cuti['id'] ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200" title="Delete" onclick="return confirm('Are you sure you want to delete this request?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-deepseek-dark-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-deepseek-dark-500">
                        Showing <span class="font-medium">1</span> to <span class="font-medium"><?= count($cutiList) ?></span> of <span class="font-medium"><?= count($cutiList) ?></span> results
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border border-deepseek-dark-200 rounded-lg text-deepseek-dark-500 hover:bg-deepseek-dark-50 disabled:opacity-50" disabled>
                            Previous
                        </button>
                        <button class="px-3 py-1 border border-deepseek-dark-200 rounded-lg text-deepseek-dark-500 hover:bg-deepseek-dark-50 disabled:opacity-50" disabled>
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white z-10 flex justify-between items-center border-b px-6 py-4">
                <h3 class="text-xl font-bold text-deepseek-dark-900">Leave Request Details</h3>
                <button onclick="closeModal()" class="text-deepseek-dark-400 hover:text-deepseek-dark-600 p-1 rounded-full hover:bg-deepseek-dark-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6" id="modalContent">
                <!-- Content will be loaded via AJAX -->
                <div class="animate-pulse space-y-4">
                    <div class="h-6 bg-deepseek-dark-100 rounded w-3/4"></div>
                    <div class="h-4 bg-deepseek-dark-100 rounded w-1/2"></div>
                    <div class="space-y-2">
                        <div class="h-4 bg-deepseek-dark-100 rounded"></div>
                        <div class="h-4 bg-deepseek-dark-100 rounded w-5/6"></div>
                        <div class="h-4 bg-deepseek-dark-100 rounded w-2/3"></div>
                    </div>
                </div>
            </div>
            <div class="sticky bottom-0 bg-white border-t px-6 py-4 flex justify-end">
                <button onclick="closeModal()" class="px-4 py-2 bg-deepseek-primary-600 text-white rounded-lg hover:bg-deepseek-primary-700 transition-colors duration-200">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h3 class="text-xl font-bold text-deepseek-dark-900">Reject Leave Request</h3>
                <button onclick="closeRejectModal()" class="text-deepseek-dark-400 hover:text-deepseek-dark-600 p-1 rounded-full hover:bg-deepseek-dark-100">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form action="dashboard_admin.php" method="POST">
                <input type="hidden" name="cuti_id" id="rejectCutiId">
                <input type="hidden" name="action" value="reject">
                <div class="p-6">
                    <div class="mb-4">
                        <label for="catatan" class="block text-sm font-medium text-deepseek-dark-700 mb-2">Reason for rejection</label>
                        <textarea name="catatan" id="catatan" rows="4" class="w-full px-3 py-2 border border-deepseek-dark-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-deepseek-accent-DEFAULT focus:border-transparent" required placeholder="Please provide a reason for rejecting this request..."></textarea>
                    </div>
                </div>
                <div class="border-t px-6 py-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-deepseek-dark-200 rounded-lg text-deepseek-dark-700 hover:bg-deepseek-dark-50 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                        <i class="fas fa-times mr-2"></i> Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fixed bottom-6 right-6 z-40">
        <button class="w-14 h-14 rounded-full bg-deepseek-accent-DEFAULT text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
            <i class="fas fa-plus text-xl"></i>
        </button>
    </div>

    <script>
        // Sidebar toggle for mobile
        const sidebar = document.getElementById('sidebar');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        
        openSidebarBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            document.body.style.overflow = 'hidden';
        });
        
        closeSidebarBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            document.body.style.overflow = 'auto';
        });

        // Modal functions
        function openModal(cutiId) {
            fetch('get_cuti_detail.php?id=' + cutiId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('modalContent').innerHTML = data;
                    document.getElementById('detailModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                })
                .catch(error => {
                    document.getElementById('modalContent').innerHTML = `
                        <div class="p-6 text-center">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                            <h4 class="text-lg font-medium text-deepseek-dark-800 mb-2">Error loading details</h4>
                            <p class="text-deepseek-dark-500">Could not load the leave request details. Please try again.</p>
                        </div>
                    `;
                    document.getElementById('detailModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openRejectModal(cutiId) {
            document.getElementById('rejectCutiId').value = cutiId;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            if (event.target === document.getElementById('detailModal')) {
                closeModal();
            }
            if (event.target === document.getElementById('rejectModal')) {
                closeRejectModal();
            }
        });
    </script>
</body>
</html>