<?php
session_start();
include '../config/database.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$id_siswa = $_SESSION['id_siswa'];
$username = $_SESSION['username'];

// Ambil jumlah siswa & instansi
$query_siswa = "SELECT COUNT(*) AS total_siswa FROM siswa";
$result_siswa = mysqli_query($conn, $query_siswa);
$total_siswa = $result_siswa ? mysqli_fetch_assoc($result_siswa)['total_siswa'] : 0;

$query_instansi = "SELECT COUNT(*) AS total_instansi FROM instansi";
$result_instansi = mysqli_query($conn, $query_instansi);
$total_instansi = $result_instansi ? mysqli_fetch_assoc($result_instansi)['total_instansi'] : 0;

// Ambil status rekomendasi siswa
$query_rekomendasi = "SELECT ri.status, i.nama, i.alamat, i.kontak 
                      FROM rekomendasi_instansi ri
                      JOIN instansi i ON ri.id_instansi = i.id_instansi
                      WHERE ri.id_siswa = $id_siswa LIMIT 1";
$result_rekomendasi = mysqli_query($conn, $query_rekomendasi);

$status_rekomendasi = "Belum Mengajukan";
$instansi_terpilih = null;
if ($result_rekomendasi && mysqli_num_rows($result_rekomendasi) > 0) {
    $data_rekomendasi = mysqli_fetch_assoc($result_rekomendasi);
    $status_rekomendasi = ucfirst($data_rekomendasi['status']);
    $instansi_terpilih = $data_rekomendasi;
}

// Ambil guru pembimbing
$query_guru = "SELECT siswa.nama AS nama_siswa, guru.nama AS nama_guru 
               FROM siswa 
               LEFT JOIN guru ON siswa.id_guru = guru.id_guru 
               WHERE siswa.nama = '$username'";
$result_guru = mysqli_query($conn, $query_guru);
$guru_pembimbing = mysqli_fetch_assoc($result_guru);

// Tentukan warna status
$status_colors = [
    "pending" => "bg-warning",
    "disetujui" => "bg-success",
    "ditolak" => "bg-danger"
];
$status_class = isset($status_colors[strtolower($status_rekomendasi)]) ? $status_colors[strtolower($status_rekomendasi)] : "bg-secondary";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - SIPI PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../siswa/navbar.php'; ?>
<button class="toggle-btn" onclick="toggleSidebar()">☰ Menu</button>
<?php include '../siswa/sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h2 class="title">Dashboard Siswa</h2>
        <p>Selamat datang, <strong><?php echo $_SESSION['username']; ?></strong>!</p>

        <div class="row">
            <!-- Status Rekomendasi -->
            <div class="col-12 col-md-6 mb-4">
                <div class="card text-white shadow-lg <?php echo $status_class; ?>">
                    <div class="card-body text-center p-4">
                        <h4><i class="fas fa-check-circle"></i> Status Rekomendasi</h4>
                        <h3 class="fw-bold"><?php echo $status_rekomendasi; ?></h3>
                    </div>
                </div>
            </div>

            <!-- Instansi Terpilih -->
            <div class="col-12 col-md-6 mb-4">
                <div class="card bg-info text-white shadow-lg">
                    <div class="card-body text-center p-4">
                        <h4><i class="fas fa-building"></i> Instansi Terpilih</h4>
                        <?php if ($instansi_terpilih): ?>
                            <h5><?php echo $instansi_terpilih['nama']; ?></h5>
                        <?php else: ?>
                            <p>Belum memilih instansi</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guru Pembimbing -->
        <div class="col-12 col-md-6 mb-4">
            <div class="card bg-dark text-white shadow-lg">
                <div class="card-body text-center p-4">
                    <h4><i class="fas fa-user-tie"></i> Guru Pembimbing</h4>
                    <?php if ($guru_pembimbing): ?>
                        <h5><?php echo $guru_pembimbing['nama_guru'] ? $guru_pembimbing['nama_guru'] : 'Belum ada guru pembimbing'; ?></h5>
                    <?php else: ?>
                        <p>Belum ada guru pembimbing</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("show");
    }
</script>

<?php include '../siswa/footer.php'; ?>

</body>
</html>