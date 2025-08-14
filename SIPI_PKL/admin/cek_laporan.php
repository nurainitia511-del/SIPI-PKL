<?php
session_start();
include '../config/database.php';

// Pastikan user sudah login dan memiliki akses admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Cek jika id_siswa dikirim
if (!isset($_GET['id_siswa']) || empty($_GET['id_siswa'])) {
    die("ID siswa tidak ditemukan.");
}

$id_siswa = $_GET['id_siswa'];

// Ambil data siswa
$query_siswa = "SELECT siswa.*, jurusan.nama_jurusan, guru.nama AS nama_guru 
                FROM siswa 
                JOIN jurusan ON siswa.jurusan_id = jurusan.id 
                JOIN guru ON siswa.id_guru = guru.id_guru
                WHERE siswa.id_siswa = ?";
$stmt_siswa = mysqli_prepare($conn, $query_siswa);
mysqli_stmt_bind_param($stmt_siswa, "i", $id_siswa);
mysqli_stmt_execute($stmt_siswa);
$result_siswa = mysqli_stmt_get_result($stmt_siswa);
$siswa = mysqli_fetch_assoc($result_siswa);
mysqli_stmt_close($stmt_siswa);

// Jika siswa tidak ditemukan
if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

// Ambil laporan siswa
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        @media print {
            body {
                visibility: hidden;
            }
            .print-section {
                visibility: visible;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                text-align: center;
            }
            .btn-print {
                display: none;
            }
        }

        .kop-sekolah {
            text-align: center;
            margin-bottom: 20px;
        }

        .kop-sekolah img {
            width: 100px;
            height: auto;
            position: absolute;
        }

        .logo-left {
            left: 10px;
            top: 10px;
        }

        .logo-right {
            right: 10px;
            top: 10px;
        }

        /* Tambahan: Styling untuk foto siswa */
        .siswa-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .siswa-info img {
            width: 120px;
            height: 140px;
            object-fit: cover;
            border: 2px solid #000;
            margin-right: 20px;
        }

    </style>
</head>
<body>

<!-- Navbar -->
<?php include '../admin/navbar.php'; ?>

<!-- Sidebar -->
<?php include '../admin/sidebar.php'; ?>

<!-- Konten -->
<div class="content">
    <div class="container">
        <div class="print-section">
            <!-- KOP SEKOLAH -->
            <div class="kop-sekolah">
                <img src="../assets/images/logo.png" class="logo-left">
                <h3>SMK Negeri 2 Padang</h3>
                <p>Alamat: Jl. Baru Andalas No. 5 Padang, SUMBAR</p>
                <p>Telepon: 0751-21930 | Email: fadhildarma95@gmail.com</p>
                <hr>
            </div>

            <!-- Informasi Siswa -->
            <div class="siswa-info">
                <?php if (!empty($siswa['foto'])): ?>
                    <img src="../assets/images/upload/<?php echo $siswa['foto']; ?>" alt="Foto Siswa">
                <?php else: ?>
                    <img src="../assets/images/default_avatar.png" alt="Foto Siswa">
                <?php endif; ?>
                <div>
                    <h2 class="title">Laporan PKL - <?php echo htmlspecialchars($siswa['nama']); ?></h2>
                    <p><strong>NIS:</strong> <?php echo htmlspecialchars($siswa['nis']); ?></p>
                    <p><strong>Kelas:</strong> <?php echo htmlspecialchars($siswa['kelas']); ?></p>
                    <p><strong>Jurusan:</strong> <?php echo htmlspecialchars($siswa['nama_jurusan']); ?></p>
                    <p><strong>Guru Pembimbing:</strong> <?php echo htmlspecialchars($siswa['nama_guru']); ?></p>
                </div>
            </div>

            <!-- Tabel Laporan -->
            <div class="table-container">
                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Kendala</th>
                            <th>Solusi</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($laporan = mysqli_fetch_assoc($result_laporan)) : ?>
                            <tr>
                                <td><?php echo $laporan['tanggal']; ?></td>
                                <td><?php echo nl2br(htmlspecialchars($laporan['kegiatan'])); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($laporan['kendala'])); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($laporan['solusi'])); ?></td>
                                <td>
                                    <?php if (!empty($laporan['foto'])) : ?>
                                        <img src="../assets/images/laporan_pkl/<?php echo $laporan['foto']; ?>" width="50" style="cursor: pointer;" 
                                            data-bs-toggle="modal" data-bs-target="#previewModal" 
                                            onclick="previewImage('../assets/images/laporan_pkl/<?php echo $laporan['foto']; ?>')">
                                    <?php else : ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <!-- Tombol Cetak -->
        <button onclick="printLaporan()" class="btn btn-primary btn-print">Cetak Laporan</button>
        <a href="laporan_pkl_admin.php" class="btn btn-secondary btn-print">Kembali</a>
    </div>
</div>

<!-- Modal untuk preview gambar -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(src) {
    document.getElementById("previewImage").src = src;
}

function printLaporan() {
    window.print();
}
</script>

</body>
</html>
