<?php
require_once 'config.php';
if (!isset($_GET['id'])) {
    echo "ID cuti tidak diberikan.";
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT c.*, u.nama FROM cuti c JOIN users u ON c.user_id = u.id WHERE c.id = ?");
$stmt->execute([$id]);
$cuti = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cuti) {
    echo "Data cuti tidak ditemukan.";
    exit;
}
?>

<div>
    <p><strong>Nama:</strong> <?= htmlspecialchars($cuti['nama']) ?></p>
    <p><strong>Jenis Cuti:</strong> <?= htmlspecialchars($cuti['jenis_cuti']) ?></p>
    <p><strong>Tanggal Pengajuan:</strong> <?= date('d M Y', strtotime($cuti['tanggal_pengajuan'])) ?></p>
    <p><strong>Periode:</strong> <?= date('d M Y', strtotime($cuti['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($cuti['tanggal_selesai'])) ?></p>
    <p><strong>Alasan:</strong> <?= nl2br(htmlspecialchars($cuti['alasan'])) ?></p>
    <p><strong>Status:</strong> <?= ucfirst($cuti['status']) ?></p>
    <?php if (!empty($cuti['catatan_admin'])): ?>
        <p><strong>Catatan Admin:</strong> <?= nl2br(htmlspecialchars($cuti['catatan_admin'])) ?></p>
    <?php endif; ?>
</div>
