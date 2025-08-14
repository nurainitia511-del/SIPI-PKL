<?php
session_start();
include '../config/database.php';

// Pastikan guru sudah login
if (!isset($_SESSION['id_guru']) || $_SESSION['role'] != 'guru') {
    header("Location: ../login.php");
    exit();
}

$id_guru = $_SESSION['id_guru'];

// Ambil data pengajuan PKL dari siswa bimbingan guru ini
$query = "
    SELECT ri.*, s.nama AS nama_siswa, s.nis, s.kelas, j.nama_jurusan, i.nama
    FROM rekomendasi_instansi ri
    JOIN siswa s ON ri.id_siswa = s.id_siswa
    LEFT JOIN jurusan j ON s.jurusan_id = j.id
    LEFT JOIN instansi i ON ri.id_instansi = i.id_instansi
    WHERE s.id_guru = $id_guru
    ORDER BY ri.id_rekomendasi_instansi DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengajuan PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .table thead th {
            background-color: #4facfe;
            color: white;
        }
        .badge {
            padding: 0.5em 0.75em;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>
<button class="toggle-btn" onclick="toggleSidebar()">☰ Menu</button>
<?php include 'sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h2 class="title">Pengajuan PKL Siswa Bimbingan</h2>
        <p>Berikut adalah daftar pengajuan PKL dari siswa bimbingan Anda.</p>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Instansi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Tentukan warna badge berdasarkan status
                            $status = strtolower($row['status']);
                            if ($status === 'disetujui') {
                                $badgeClass = 'success';
                            } elseif ($status === 'ditolak') {
                                $badgeClass = 'danger';
                            } elseif ($status === 'menunggu') {
                                $badgeClass = 'secondary';
                            } else {
                                $badgeClass = 'info';
                            }

                            echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['nama_siswa']}</td>
                                <td>{$row['nis']}</td>
                                <td>{$row['kelas']}</td>
                                <td>{$row['nama_jurusan']}</td>
                                <td>{$row['nama']}</td>
                                <td><span class='badge bg-{$badgeClass}'>{$row['status']}</span></td>
                            </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>Belum ada pengajuan PKL dari siswa bimbingan.</td></tr>";
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
