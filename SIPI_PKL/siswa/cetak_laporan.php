<?php
include '../config/database.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    die("Akses ditolak. Silakan login sebagai siswa.");
}

$id_siswa = $_SESSION['id_siswa'];

// AMBIL DATA SISWA
$query_siswa = "SELECT nama, nis, kelas, foto FROM siswa WHERE id_siswa = ?";
$stmt_siswa = mysqli_prepare($conn, $query_siswa);
mysqli_stmt_bind_param($stmt_siswa, "i", $id_siswa);
mysqli_stmt_execute($stmt_siswa);
$result_siswa = mysqli_stmt_get_result($stmt_siswa);
$siswa = mysqli_fetch_assoc($result_siswa);
mysqli_stmt_close($stmt_siswa);

// AMBIL LAPORAN SISWA
$query_laporan = "SELECT * FROM laporan_pkl WHERE id_siswa = ? ORDER BY tanggal DESC";
$stmt_laporan = mysqli_prepare($conn, $query_laporan);
mysqli_stmt_bind_param($stmt_laporan, "i", $id_siswa);
mysqli_stmt_execute($stmt_laporan);
$result_laporan = mysqli_stmt_get_result($stmt_laporan);
mysqli_stmt_close($stmt_laporan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid black;
            padding-bottom: 15px;
            margin-bottom: 20px;
            position: relative;
        }
        .kop-surat img {
            position: absolute;
            left: 30px;
            top: 0;
            width: 100px;
        }
        .kop-surat h1 {
            font-size: 22px;
            margin: 0;
        }
        .kop-surat h3 {
            font-size: 16px;
            margin: 5px 0;
        }
        .siswa-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .siswa-info img {
            width: 100px;
            height: 120px;
            object-fit: cover;
            border: 2px solid #000;
            margin-right: 15px;
        }
        .laporan-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .laporan-table th, .laporan-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }
        .laporan-table th {
            background-color: #f2f2f2;
            text-align: center;
        }
        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
<br>
<div class="kop-surat">
    <img src="../assets/images/logo.png" alt="Logo Sekolah"><br>
    <h1>SMK Negeri 2 Padang</h1>
    <h3>Jl. Baru Andalas No. 5 Padang, SUMBAR</h3>
    <h3>Telepon: 0751-21930 | Email: fadhildarma95@gmail.com</h3>
</div>

<!-- Informasi Siswa -->
<div class="siswa-info">
    <?php if (!empty($siswa['foto'])): ?>
        <img src="../assets/images/upload/<?php echo $siswa['foto']; ?>" alt="Foto Siswa">
    <?php else: ?>
        <img src="../assets/images/default_avatar.png" alt="Foto Siswa">
    <?php endif; ?>
    <div>
        <h4>Nama: <?php echo htmlspecialchars($siswa['nama']); ?></h4>
        <h4>NIS: <?php echo htmlspecialchars($siswa['nis']); ?></h4>
        <h4>Kelas: <?php echo htmlspecialchars($siswa['kelas']); ?></h4>
    </div>
</div>

<!-- Tabel Laporan -->
<table class="laporan-table">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kegiatan</th>
            <th>Kendala</th>
            <th>Solusi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($laporan = mysqli_fetch_assoc($result_laporan)): ?>
            <tr>
                <td><?php echo date("d-m-Y", strtotime($laporan['tanggal'])); ?></td>
                <td><?php echo nl2br(htmlspecialchars($laporan['kegiatan'])); ?></td>
                <td><?php echo nl2br(htmlspecialchars($laporan['kendala'])); ?></td>
                <td><?php echo nl2br(htmlspecialchars($laporan['solusi'])); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
    window.onload = function() {
        window.print();
    };
</script>

</body>
</html>
