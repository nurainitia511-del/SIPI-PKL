<?php
session_start();
include '../config/database.php';

// Pastikan user sudah login dan memiliki akses admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil semua data siswa
$query_siswa = "SELECT siswa.*, jurusan.nama_jurusan, guru.nama AS nama_guru 
                FROM siswa 
                JOIN jurusan ON siswa.jurusan_id = jurusan.id 
                JOIN guru ON siswa.id_guru = guru.id_guru
                ORDER BY siswa.id_siswa ASC";
$result_siswa = mysqli_query($conn, $query_siswa);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan PKL - SIPI PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- Navbar -->
<?php include '../admin/navbar.php'; ?>

<!-- Sidebar -->
<?php include '../admin/sidebar.php'; ?>

<!-- Konten -->
<div class="content">
    <div class="container">
        <h2 class="title">Laporan PKL Siswa</h2><br>
        <div class="table-container">
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Guru Pembimbing</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($siswa = mysqli_fetch_assoc($result_siswa)) : ?>
                        <tr>
                            <td><?php echo $siswa['id_siswa']; ?></td>
                            <td><img src="../assets/images/upload/<?php echo $siswa['foto']; ?>" width="50"></td>
                            <td><?php echo $siswa['nama']; ?></td>
                            <td><?php echo $siswa['nis']; ?></td>
                            <td><?php echo $siswa['kelas']; ?></td>
                            <td><?php echo $siswa['nama_jurusan']; ?></td>
                            <td><?php echo $siswa['nama_guru']; ?></td>
                            <td>
                                <a href="cek_laporan.php?id_siswa=<?php echo $siswa['id_siswa']; ?>" class="btn btn-primary btn-sm">Cek Laporan</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../admin/footer.php'; ?>
</body>
</html>
