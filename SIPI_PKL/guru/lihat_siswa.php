<?php
session_start();
include '../config/database.php';

// Pastikan guru sudah login
if (!isset($_SESSION['id_guru']) || $_SESSION['role'] != 'guru') {
    header("Location: ../login.php");
    exit();
}

$id_guru = $_SESSION['id_guru'];
$username = $_SESSION['username'];

// Ambil data siswa bimbingan beserta jurusan
$query = "
    SELECT siswa.*, jurusan.nama_jurusan 
    FROM siswa 
    LEFT JOIN jurusan ON siswa.jurusan_id = jurusan.id 
    WHERE siswa.id_guru = $id_guru
";



$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa Bimbingan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>
<button class="toggle-btn" onclick="toggleSidebar()">☰ Menu</button>
<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h2 class="title">Data Siswa Bimbingan</h2>
        <p class=""><span>Halo, <strong>
            <?php 
                echo ($_SESSION['role'] === 'guru') ? $_SESSION['nama'] : $_SESSION['username'];
            ?>
        </strong></span>, berikut adalah siswa bimbingan Anda:</p>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover shadow-sm text-center align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $no = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$no}</td>
                            <td>{$row['nama']}</td>
                            <td>{$row['nis']}</td>
                            <td>{$row['kelas']}</td>
                            <td>{$row['nama_jurusan']}</td>
                            <td><img src='../assets/images/upload/{$row['foto']}' alt='Foto' width='60'></td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='6'>Belum ada siswa bimbingan.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
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







