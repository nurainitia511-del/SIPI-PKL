<?php
session_start();
include '../config/database.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil data jumlah siswa, guru, instansi
$query_siswa = "SELECT COUNT(*) AS total_siswa FROM siswa";
$result_siswa = mysqli_query($conn, $query_siswa);
$total_siswa = $result_siswa ? mysqli_fetch_assoc($result_siswa)['total_siswa'] : 0;

$query_guru = "SELECT COUNT(*) AS total_guru FROM guru";
$result_guru = mysqli_query($conn, $query_guru);
$total_guru = $result_guru ? mysqli_fetch_assoc($result_guru)['total_guru'] : 0;

$query_instansi = "SELECT COUNT(*) AS total_instansi FROM instansi";
$result_instansi = mysqli_query($conn, $query_instansi);
$total_instansi = $result_instansi ? mysqli_fetch_assoc($result_instansi)['total_instansi'] : 0;

// Path Foto Profil (Default jika tidak ada)
$foto_profil = isset($_SESSION['foto']) ? $_SESSION['foto'] : 'default-profile.png';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIPI PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    
</head>
<body>

    
<!-- Navbar -->
<?php include '../admin/navbar.php'; ?>

    <!-- Tombol untuk toggle sidebar -->
    <button class="toggle-btn" onclick="toggleSidebar()">☰ Menu</button>
    
    <!-- Include Sidebar -->
    <?php include '../admin/sidebar.php'; ?>

   <!-- Konten -->
<div class="content">
    <div class="container">
        <h2 class="title">Dashboard</h2>
        <p class="">Selamat datang, <strong><?php echo $_SESSION['username']; ?></strong>!</p>

        <div class="row">
            <!-- Card Total Siswa -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card bg-primary text-white h-100 shadow-lg">
                    <div class="card-body text-center p-4">
                        <h4 class="card-title"><i class="fas fa-users"></i> Total Siswa</h4>
                        <h1 class="card-text fw-bold"><?php echo $total_siswa; ?></h1>
                    </div>
                </div>
            </div>

            <!-- Card Total Guru -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card bg-success text-white h-100 shadow-lg">
                    <div class="card-body text-center p-4">
                        <h4 class="card-title"><i class="fas fa-user-tie"></i> Total Guru</h4>
                        <h1 class="card-text fw-bold"><?php echo $total_guru; ?></h1>
                    </div>
                </div>
            </div>

            <!-- Card Total Instansi -->
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card bg-warning text-white h-100 shadow-lg">
                    <div class="card-body text-center p-4">
                        <h4 class="card-title"><i class="fas fa-building"></i> Total Instansi</h4>
                        <h1 class="card-text fw-bold"><?php echo $total_instansi; ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("show");
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Anda akan keluar dari sistem!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        }
    </script>
<?php include '../admin/footer.php'; ?>

</body>
</html>
