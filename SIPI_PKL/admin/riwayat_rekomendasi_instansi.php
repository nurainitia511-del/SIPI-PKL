<?php
session_start();
include '../config/database.php';

// Pastikan admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil data riwayat rekomendasi yang sudah diproses
$query_riwayat = "SELECT ri.id_riwayat, ri.status, ri.tanggal_proses, s.nama AS nama_siswa, i.nama AS nama_instansi, u.username AS username
                  FROM riwayat_rekomendasi_instansi ri
                  JOIN siswa s ON ri.id_siswa = s.id_siswa
                  JOIN instansi i ON ri.id_instansi = i.id_instansi
                  JOIN users u ON ri.id_users = u.id_users
                  ORDER BY ri.tanggal_proses DESC, ri.id_riwayat DESC"; // Urutkan berdasarkan tanggal proses terbaru
$result_riwayat = mysqli_query($conn, $query_riwayat);

if (!$result_riwayat) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Rekomendasi Instansi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Navbar -->
<?php include '../admin/navbar.php'; ?>

<!-- Sidebar -->
<?php include '../admin/sidebar.php'; ?>

<!-- SweetAlert untuk notifikasi -->
<?php if (isset($_SESSION['alert'])): ?>
<script>
    Swal.fire({
        title: "<?php echo $_SESSION['alert']['title']; ?>",
        text: "<?php echo $_SESSION['alert']['text']; ?>",
        icon: "<?php echo $_SESSION['alert']['icon']; ?>"
    });
</script>
<?php unset($_SESSION['alert']); ?>
<?php endif; ?>

<!-- Konten -->
<div class="content">
    <div class="container">
        <h2 class="title">Riwayat Pengajuan Rekomendasi Instansi</h2><br>

        <!-- Tabel Riwayat -->
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Nama Instansi</th>
                    <th>Status</th>
                    <th>Tanggal Proses</th>
                    <th>Diproses Oleh</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_riwayat) > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($riwayat = mysqli_fetch_assoc($result_riwayat)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($riwayat['nama_siswa']); ?></td>
                            <td><?php echo htmlspecialchars($riwayat['nama_instansi']); ?></td>
                            <td>
                                <?php 
                                    if ($riwayat['status'] == 'disetujui') {
                                        echo '<span class="badge bg-success">Disetujui</span>';
                                    } else {
                                        echo '<span class="badge bg-danger">Ditolak</span>';
                                    }
                                ?>
                            </td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($riwayat['tanggal_proses'])); ?></td>
                            <td><?php echo htmlspecialchars($riwayat['username']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada riwayat pengajuan rekomendasi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../admin/footer.php'; ?>

</body>
</html>
