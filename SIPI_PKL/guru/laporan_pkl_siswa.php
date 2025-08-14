<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['id_guru']) || $_SESSION['role'] != 'guru') {
    header("Location: ../login.php");
    exit();
}

$id_guru = $_SESSION['id_guru'];

$query = "
    SELECT laporan_pkl.*, siswa.nama 
    FROM laporan_pkl 
    JOIN siswa ON laporan_pkl.id_siswa = siswa.id_siswa 
    WHERE siswa.id_guru = $id_guru
    ORDER BY laporan_pkl.tanggal DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan PKL Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>
<button class="toggle-btn" onclick="toggleSidebar()">☰ Menu</button>
<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h2 class="title mb-4">Laporan PKL Siswa</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-success text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Kendala</th>
                        <th>Solusi</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr class='align-middle'>";
                            echo "<td class='text-center'>{$no}</td>";
                            echo "<td>{$row['nama']}</td>";
                            echo "<td>{$row['tanggal']}</td>";
                            echo "<td>{$row['kegiatan']}</td>";
                            echo "<td>{$row['kendala']}</td>";
                            echo "<td>{$row['solusi']}</td>";
                            echo "<td><img src='../assets/images/laporan_pkl/{$row['foto']}' alt='Foto Kegiatan' width='60'></td>";
                            echo "</tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center text-danger'>Belum ada laporan PKL dari siswa bimbingan.</td></tr>";
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
