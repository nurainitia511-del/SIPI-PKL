<?php
session_start();
include '../config/database.php';

// AKTIFKAN ERROR REPORTING
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CEK LOGIN
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    die("Akses ditolak. Silakan login sebagai siswa.");
}

if (!isset($_SESSION['id_siswa'])) {
    die("ID siswa tidak ditemukan. Silakan login ulang.");
}

$id_siswa = $_SESSION['id_siswa'];

// CEK SUDAH PILIH INSTANSI BELUM
$query_check = "SELECT id_instansi FROM rekomendasi_instansi WHERE id_siswa = ?";
$stmt_check = mysqli_prepare($conn, $query_check);
mysqli_stmt_bind_param($stmt_check, "i", $id_siswa);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);
$sudah_memilih = mysqli_num_rows($result_check) > 0;
mysqli_stmt_close($stmt_check);

// AMBIL DATA INSTANSI DAN CEK JUMLAH SISWA YANG SUDAH MEMILIH
$query_instansi = "SELECT i.id_instansi, i.nama, i.bidang_instansi, 
                          (SELECT COUNT(*) FROM rekomendasi_instansi ri WHERE ri.id_instansi = i.id_instansi) AS total_siswa 
                   FROM instansi i";
$result_instansi = mysqli_query($conn, $query_instansi);
if (!$result_instansi) {
    die("Gagal mengambil data instansi: " . mysqli_error($conn));
}

// AMBIL STATUS REKOMENDASI
$query_status = "SELECT ri.id_instansi, ri.status, i.nama, i.alamat, i.kontak, i.bidang_instansi 
                 FROM rekomendasi_instansi ri
                 JOIN instansi i ON ri.id_instansi = i.id_instansi
                 WHERE ri.id_siswa = ? LIMIT 1";
$stmt_status = mysqli_prepare($conn, $query_status);
mysqli_stmt_bind_param($stmt_status, "i", $id_siswa);
mysqli_stmt_execute($stmt_status);
$result_status = mysqli_stmt_get_result($stmt_status);
$status_rekomendasi = '';
$instansi_info = [];

if ($result_status && mysqli_num_rows($result_status) > 0) {
    $status_data = mysqli_fetch_assoc($result_status);
    if ($status_data) {
        $status_rekomendasi = $status_data['status'];
        $instansi_info = [
            'nama' => $status_data['nama'] ?? '-',
            'alamat' => $status_data['alamat'] ?? '-',
            'kontak' => $status_data['kontak'] ?? '-',
            'bidang' => $status_data['bidang_instansi'] ?? '-'
        ];
    }
}
mysqli_stmt_close($stmt_status);

// PROSES SUBMIT FORM
$alert = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($sudah_memilih) {
        $alert = "Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Anda hanya bisa memilih satu instansi!',
                    confirmButtonColor: '#3085d6'
                });";
    } else {
        $id_instansi = $_POST['id_instansi'] ?? '';

        // Cek apakah instansi sudah mencapai batas maksimal 4 siswa
        $query_count = "SELECT COUNT(*) AS total FROM rekomendasi_instansi WHERE id_instansi = ?";
        $stmt_count = mysqli_prepare($conn, $query_count);
        mysqli_stmt_bind_param($stmt_count, "i", $id_instansi);
        mysqli_stmt_execute($stmt_count);
        $result_count = mysqli_stmt_get_result($stmt_count);
        $row_count = mysqli_fetch_assoc($result_count);
        $jumlah_siswa_di_instansi = $row_count['total'];
        mysqli_stmt_close($stmt_count);

        if ($jumlah_siswa_di_instansi >= 4) {
            $alert = "Swal.fire({
                        icon: 'error',
                        title: 'Instansi Penuh!',
                        text: 'Instansi yang dipilih sudah mencapai batas maksimal 4 siswa.',
                        confirmButtonColor: '#d33'
                    });";
        } elseif (empty($id_instansi)) {
            $alert = "Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Anda harus memilih instansi.',
                        confirmButtonColor: '#d33'
                    });";
        } else {
            $insert_query = "INSERT INTO rekomendasi_instansi (id_siswa, id_instansi) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt_insert, "ii", $id_siswa, $id_instansi);

            if (mysqli_stmt_execute($stmt_insert)) {
                $alert = "Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Instansi berhasil dipilih. Tunggu konfirmasi admin.',
                            confirmButtonColor: '#3085d6'
                        }).then(() => {
                            window.location.replace('pilih_instansi.php');
                        });";
            } else {
                $alert = "Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal menyimpan pilihan.',
                            confirmButtonColor: '#d33'
                        });";
            }
            mysqli_stmt_close($stmt_insert);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Instansi untuk Rekomendasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../siswa/navbar.php'; ?>
<?php include '../siswa/sidebar.php'; ?>

<div class="content">
    <div class="container">
        <h2 class="title">Pilih Instansi untuk Rekomendasi</h2><br>

        <form action="pilih_instansi.php" method="POST">
            <div class="form-group">
                <label for="instansi">Pilih Instansi</label>
                <select name="id_instansi" id="instansi" class="form-control" required>
                    <option value="">-- Pilih Instansi --</option>
                    <?php while ($instansi = mysqli_fetch_assoc($result_instansi)): ?>
                        <option value="<?php echo $instansi['id_instansi']; ?>" 
                                <?php echo ($instansi['total_siswa'] >= 4) ? 'disabled' : ''; ?>>
                            <?php echo $instansi['nama']; ?> - <?php echo $instansi['bidang_instansi']; ?>
                            (<?php echo $instansi['total_siswa']; ?>/4)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Ajukan Rekomendasi</button>
        </form>

        <h3>Status Rekomendasi Instansi Anda</h3>
        <?php if ($status_rekomendasi): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Instansi yang Dipilih</th>
                        <th>Bidang Instansi</th>
                        <th>Alamat</th>
                        <th>Kontak</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $instansi_info['nama']; ?></td>
                        <td><?php echo $instansi_info['bidang']; ?></td>
                        <td><?php echo $instansi_info['alamat']; ?></td>
                        <td><?php echo $instansi_info['kontak']; ?></td>
                        <td><span class="badge bg-<?php echo strtolower($status_rekomendasi) === 'disetujui' ? 'success' : (strtolower($status_rekomendasi) === 'pending' ? 'warning text-dark' : 'danger'); ?>">
                            <?php echo ucfirst($status_rekomendasi); ?>
                        </span></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Anda belum memilih instansi atau status masih dalam proses.</div>
        <?php endif; ?>
    </div>
</div>

<script><?php echo $alert; ?></script>

<?php include '../siswa/footer.php'; ?>

</body>
</html>
