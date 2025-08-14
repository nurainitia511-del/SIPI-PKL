<?php
session_start();
include '../config/database.php';

// Pastikan admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_rekomendasi_instansi = $_POST['id_rekomendasi_instansi'];
    $status = $_POST['status'];  // 'disetujui' atau 'ditolak'
    $id_users = $_SESSION['id_users']; // ID pengguna (admin) yang melakukan update
    $tanggal_proses = date("Y-m-d H:i:s"); // Waktu update

    // Ambil data rekomendasi sebelum diperbarui
    $query_get_rekomendasi = "SELECT * FROM rekomendasi_instansi WHERE id_rekomendasi_instansi = $id_rekomendasi_instansi";
    $result_get = mysqli_query($conn, $query_get_rekomendasi);
    $data_rekomendasi = mysqli_fetch_assoc($result_get);

    if ($data_rekomendasi) {
        $id_siswa = $data_rekomendasi['id_siswa'];
        $id_instansi = $data_rekomendasi['id_instansi'];

        // Update status rekomendasi_instansi
        $update_query = "UPDATE rekomendasi_instansi SET status = '$status' WHERE id_rekomendasi_instansi = $id_rekomendasi_instansi";
        
        if (mysqli_query($conn, $update_query)) {
            // Simpan ke tabel riwayat_rekomendasi_instansi
            $insert_riwayat_query = "INSERT INTO riwayat_rekomendasi_instansi (id_rekomendasi_instansi, id_siswa, id_instansi, status, tanggal_proses, id_users) 
                                     VALUES ('$id_rekomendasi_instansi', '$id_siswa', '$id_instansi', '$status', '$tanggal_proses', '$id_users')";

            if (mysqli_query($conn, $insert_riwayat_query)) {
                $_SESSION['alert'] = [
                    'title' => 'Berhasil!',
                    'text' => 'Status rekomendasi instansi berhasil diperbarui dan disimpan ke riwayat.',
                    'icon' => 'success'
                ];
            } else {
                $_SESSION['alert'] = [
                    'title' => 'Gagal!',
                    'text' => 'Gagal menyimpan riwayat rekomendasi instansi.',
                    'icon' => 'error'
                ];
            }
        } else {
            $_SESSION['alert'] = [
                'title' => 'Gagal!',
                'text' => 'Gagal memperbarui status rekomendasi instansi.',
                'icon' => 'error'
            ];
        }
    }

    // Redirect ke halaman riwayat setelah update
    header("Location: riwayat_rekomendasi_instansi.php");
    exit();
}



// Ambil data rekomendasi yang masih pending untuk ditampilkan di tabel
$query_rekomendasi = "SELECT rekomendasi_instansi.*, siswa.nama AS nama_siswa, siswa.kelas AS kelas_siswa, instansi.nama AS nama_instansi
                      FROM rekomendasi_instansi
                      JOIN siswa ON rekomendasi_instansi.id_siswa = siswa.id_siswa
                      JOIN instansi ON rekomendasi_instansi.id_instansi = instansi.id_instansi
                      WHERE rekomendasi_instansi.status = 'pending'";

$result_rekomendasi = mysqli_query($conn, $query_rekomendasi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Rekomendasi Instansi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Menambahkan SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Navbar -->
<?php include '../admin/navbar.php'; ?>

<!-- Sidebar -->
<?php include '../admin/sidebar.php'; ?>

<!-- SweetAlert -->
<?php if (isset($_SESSION['alert'])): ?>
<script>
    Swal.fire({
        title: "<?php echo $_SESSION['alert']['title']; ?>",
        text: "<?php echo $_SESSION['alert']['text']; ?>",
        icon: "<?php echo $_SESSION['alert']['icon']; ?>"
    });
</script>
<?php unset($_SESSION['alert']); ?>
<?php endif; ?>

<!-- Konten -->
<div class="content">
    <div class="container">
        <h2 class="title">Konfirmasi Rekomendasi Instansi</h2><br>
        
        <table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID Rekomendasi</th> <!-- Tambahkan kolom ID -->
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Nama Instansi</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($rekomendasi = mysqli_fetch_assoc($result_rekomendasi)): ?>
            <tr>
                <td><?php echo $rekomendasi['id_rekomendasi_instansi']; ?></td> <!-- Menampilkan ID -->
                <td><?php echo $rekomendasi['nama_siswa']; ?></td>
                <td><?php echo $rekomendasi['kelas_siswa']; ?></td>
                <td><?php echo $rekomendasi['nama_instansi']; ?></td>
                <td><?php echo ucfirst($rekomendasi['status']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_rekomendasi_instansi" value="<?php echo $rekomendasi['id_rekomendasi_instansi']; ?>">
                        <button type="submit" name="status" value="disetujui" class="btn btn-success">Setujui</button>
                        <button type="submit" name="status" value="ditolak" class="btn btn-danger">Tolak</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../admin/footer.php'; ?>
</body>
</html>
