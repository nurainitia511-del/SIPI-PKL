<?php
session_start();
include '../config/database.php';

// Cek jika belum login
if (!isset($_SESSION['id_guru']) || $_SESSION['role'] != 'guru') {
    header("Location: ../login.php");
    exit();
}

$id_guru = $_SESSION['id_guru'];

// Hitung total siswa yang dibimbing oleh guru ini
$query_siswa = "SELECT COUNT(*) AS total_siswa FROM siswa WHERE id_guru = $id_guru";
$result_siswa = mysqli_query($conn, $query_siswa);
$total_siswa = $result_siswa ? mysqli_fetch_assoc($result_siswa)['total_siswa'] : 0;

// Hitung total pengajuan PKL siswa bimbingan
$query_pengajuan = "
    SELECT COUNT(*) AS total_pengajuan 
    FROM rekomendasi_instansi 
    WHERE id_siswa IN (SELECT id_siswa FROM siswa WHERE id_guru = $id_guru)";
$result_pengajuan = mysqli_query($conn, $query_pengajuan);
$total_pengajuan = $result_pengajuan ? mysqli_fetch_assoc($result_pengajuan)['total_pengajuan'] : 0;

// Hitung total laporan PKL siswa bimbingan
$query_laporan = "
    SELECT COUNT(*) AS total_laporan 
    FROM laporan_pkl 
    WHERE id_siswa IN (SELECT id_siswa FROM siswa WHERE id_guru = $id_guru)";
$result_laporan = mysqli_query($conn, $query_laporan);
$total_laporan = $result_laporan ? mysqli_fetch_assoc($result_laporan)['total_laporan'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - SIPI PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>
<button class="toggle-btn" onclick="toggleSidebar()">☰ Menu</button>
<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h2 class="title">Dashboard Guru</h2>
        <p class="">Selamat datang, <span><strong>
            <?php 
                echo ($_SESSION['role'] === 'guru') ? $_SESSION['nama'] : $_SESSION['username'];
            ?>
        </strong></span>

        <div class="row">
            <!-- Card Total Siswa -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card bg-primary text-white h-100 shadow-lg">
                    <div class="card-body text-center p-4">
                        <h4 class="card-title"><i class="fas fa-users"></i> Siswa Bimbingan</h4>
                        <h1 class="card-text fw-bold"><?php echo $total_siswa; ?></h1>
                    </div>
                </div>
            </div>

            <!-- Card Total Pengajuan -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card bg-info text-white h-100 shadow-lg">
                    <div class="card-body text-center p-4">
                        <h4 class="card-title"><i class="fas fa-file-signature"></i> Pengajuan PKL</h4>
                        <h1 class="card-text fw-bold"><?php echo $total_pengajuan; ?></h1>
                    </div>
                </div>
            </div>

            <!-- Card Total Laporan -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card bg-success text-white h-100 shadow-lg">
                    <div class="card-body text-center p-4">
                        <h4 class="card-title"><i class="fas fa-book-open"></i> Laporan PKL</h4>
                        <h1 class="card-text fw-bold"><?php echo $total_laporan; ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigasi Tambahan -->
        <!-- <div class="row mt-4">
            <div class="col-md-6 mb-2">
                <a href="lihat_siswa.php" class="btn btn-outline-primary w-100">Lihat Data Siswa</a>
            </div>
            <div class="col-md-6 mb-2">
                <a href="laporan_pkl_siswa.php" class="btn btn-outline-success w-100">Lihat Laporan PKL</a>
            </div>
        </div> -->
    </div>
</div>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("show");
    }
</script>

<?php include '../admin/footer.php'; ?>
</body>
</html>
