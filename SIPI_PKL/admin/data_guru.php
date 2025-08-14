<?php
session_start();
include '../config/database.php';

// Pastikan user sudah login dan memiliki akses admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil semua data guru dari database
$query_guru = "SELECT * FROM guru ORDER BY id_guru ASC";
$result_guru = mysqli_query($conn, $query_guru);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru - SIPI PKL</title>
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
        <h2 class="title">Manajemen Data Guru</h2><br>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahGuru">+ Tambah Guru</button>
        <div class="table-container">
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($guru = mysqli_fetch_assoc($result_guru)) : ?>
                        <tr>
                            <td><?php echo $guru['id_guru']; ?></td>
                            <td><img src="../assets/images/upload/<?php echo $guru['foto']; ?>" width="50"></td>
                            <td><?php echo $guru['nama']; ?></td>
                            <td><?php echo $guru['nip']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditGuru<?php echo $guru['id_guru']; ?>">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="hapusGuru(<?php echo $guru['id_guru']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Guru -->
<div class="modal fade" id="modalTambahGuru" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="proses_tambah_guru.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" class="form-control" name="nip" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" class="form-control" name="foto">
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Guru -->
<?php
$result_guru_edit = mysqli_query($conn, $query_guru);
while ($guru_edit = mysqli_fetch_assoc($result_guru_edit)) :
?>
<div class="modal fade" id="modalEditGuru<?php echo $guru_edit['id_guru']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="proses_edit_guru.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_guru" value="<?php echo $guru_edit['id_guru']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" value="<?php echo $guru_edit['nama']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" class="form-control" name="nip" value="<?php echo $guru_edit['nip']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" class="form-control" name="foto">
                    </div>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php endwhile; ?>

<script>
function hapusGuru(id) {
    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "proses_hapus_guru.php?id_guru=" + id;
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../admin/footer.php'; ?>
</body>
</html>
