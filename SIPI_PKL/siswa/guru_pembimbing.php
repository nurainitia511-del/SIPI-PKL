<?php
session_start();
include '../config/database.php';

// Pastikan user sudah login sebagai siswa
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: login.php");
    exit;
}

// Ambil data siswa yang sedang login
$username = $_SESSION['username'];  // Nama siswa yang login
$query_siswa = "
    SELECT siswa.id_siswa, siswa.nama AS nama_siswa, guru.nama AS nama_guru
    FROM siswa
    LEFT JOIN guru ON siswa.id_guru = guru.id_guru
    WHERE siswa.nama = '$username'";  // Filter berdasarkan siswa yang login

$result_siswa = mysqli_query($conn, $query_siswa);

if (!$result_siswa) {
    die("Query Error: " . mysqli_error($conn));
}

// Ambil data siswa
$siswa_data = mysqli_fetch_assoc($result_siswa);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru Pembimbing - SIPI PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- Navbar -->
<?php include '../siswa/navbar.php'; ?>

<!-- Sidebar -->
<?php include '../siswa/sidebar.php'; ?>

<!-- Konten -->
<div class="content">
    <div class="container">
        <h2 class="title">Guru Pembimbing</h2><br>
        
        <p>Berikut adalah data guru pembimbing Anda:</p>

        <div class="table-container">
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Nama Guru Pembimbing</th>
                    </tr>   
                </thead>
                <tbody>
                    <?php if ($siswa_data): ?>
                        <tr>
                            <td><?php echo $siswa_data['nama_siswa']; ?></td>
                            <td><?php echo $siswa_data['nama_guru'] ? $siswa_data['nama_guru'] : 'Belum ada guru pembimbing'; ?></td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="2">Data siswa tidak ditemukan</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../siswa/footer.php'; ?>

</body>
</html>
