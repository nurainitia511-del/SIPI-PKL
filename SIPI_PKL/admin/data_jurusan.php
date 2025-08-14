<?php
session_start();
include '../config/database.php';

// Pastikan user sudah login dan memiliki akses admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil semua data jurusan dari database
$query_jurusan = "SELECT * FROM jurusan ORDER BY id ASC";
$result_jurusan = mysqli_query($conn, $query_jurusan);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jurusan - SIPI PKL</title>
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
        <h2 class="title">Manajemen Data Jurusan</h2><br>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahJurusan">+ Tambah Jurusan</button>
        <div class="table-container">
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($jurusan = mysqli_fetch_assoc($result_jurusan)) : ?>
                        <tr>
                            <td><?php echo $jurusan['id']; ?></td>
                            <td><?php echo $jurusan['nama_jurusan']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditJurusan<?php echo $jurusan['id']; ?>">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="hapusJurusan(<?php echo $jurusan['id']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Jurusan -->
<div class="modal fade" id="modalTambahJurusan" tabindex="-1" aria-labelledby="modalTambahJurusanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="proses_tambah_jurusan.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" class="form-control" name="nama_jurusan" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Jurusan -->
<?php
$result_jurusan_edit = mysqli_query($conn, $query_jurusan);
while ($jurusan_edit = mysqli_fetch_assoc($result_jurusan_edit)) :
?>
<div class="modal fade" id="modalEditJurusan<?php echo $jurusan_edit['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="proses_edit_jurusan.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $jurusan_edit['id']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" class="form-control" name="nama_jurusan" value="<?php echo $jurusan_edit['nama_jurusan']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>

<script>
function hapusJurusan(id) {
    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "proses_hapus_jurusan.php?id=" + id;
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../admin/footer.php'; ?>
</body>
</html>
