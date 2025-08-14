<?php
session_start();
include '../config/database.php';

// Pastikan user sudah login dan memiliki akses admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil semua data instansi dari database
$query_instansi = "SELECT * FROM instansi ORDER BY id_instansi ASC";
$result_instansi = mysqli_query($conn, $query_instansi);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Instansi - SIPI PKL</title>
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
        <h2 class="title">Manajemen Data Instansi</h2><br>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahInstansi">+ Tambah Instansi</button>
        <div class="table-container">
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kontak</th>
                        <th>Bidang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($instansi = mysqli_fetch_assoc($result_instansi)) : ?>
                        <tr>
                            <td><?php echo $instansi['id_instansi']; ?></td>
                            <td><?php echo $instansi['nama']; ?></td>
                            <td><?php echo $instansi['alamat']; ?></td>
                            <td><?php echo $instansi['kontak']; ?></td>
                            <td><?php echo $instansi['bidang_instansi']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditInstansi<?php echo $instansi['id_instansi']; ?>">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="hapusInstansi(<?php echo $instansi['id_instansi']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Instansi -->
<div class="modal fade" id="modalTambahInstansi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Instansi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="proses_tambah_instansi.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Instansi</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak</label>
                        <input type="text" class="form-control" name="kontak" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bidang Instansi</label>
                        <input type="text" class="form-control" name="bidang_instansi" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Instansi -->
<?php
$result_instansi_edit = mysqli_query($conn, $query_instansi);
while ($instansi_edit = mysqli_fetch_assoc($result_instansi_edit)) :
?>
<div class="modal fade" id="modalEditInstansi<?php echo $instansi_edit['id_instansi']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Instansi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="proses_edit_instansi.php" method="POST">
                    <input type="hidden" name="id_instansi" value="<?php echo $instansi_edit['id_instansi']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Instansi</label>
                        <input type="text" class="form-control" name="nama" value="<?php echo $instansi_edit['nama']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" required><?php echo $instansi_edit['alamat']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kontak</label>
                        <input type="text" class="form-control" name="kontak" value="<?php echo $instansi_edit['kontak']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bidang Instansi</label>
                        <input type="text" class="form-control" name="bidang_instansi" value="<?php echo $instansi_edit['bidang_instansi']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>

<script>
function hapusInstansi(id) {
    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "proses_hapus_instansi.php?id=" + id;
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../admin/footer.php'; ?>
</body>
</html>
