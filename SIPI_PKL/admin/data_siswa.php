<?php
session_start();
include '../config/database.php';

// Pastikan user sudah login dan memiliki akses admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil semua data siswa dari database
$query_siswa = "SELECT siswa.*, jurusan.nama_jurusan FROM siswa 
                JOIN jurusan ON siswa.jurusan_id = jurusan.id 
                ORDER BY siswa.id_siswa ASC";
$result_siswa = mysqli_query($conn, $query_siswa);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - SIPI PKL</title>
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
        <h2 class="title">Manajemen Data Siswa</h2><br>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">+ Tambah Siswa</button>
        <div class="table-container">
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Guru Pembimbing</th> <!-- Menambahkan kolom Guru Pembimbing -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($siswa = mysqli_fetch_assoc($result_siswa)) : ?>
                        <tr>
                            <td><?php echo $siswa['id_siswa']; ?></td>
                            <td><img src="../assets/images/upload/<?php echo $siswa['foto']; ?>" width="50"></td>
                            <td><?php echo $siswa['nama']; ?></td>
                            <td><?php echo $siswa['nis']; ?></td>
                            <td><?php echo $siswa['kelas']; ?></td>
                            <td><?php echo $siswa['nama_jurusan']; ?></td>
                            <td>
                                <?php
                                // Ambil nama guru pembimbing berdasarkan id_guru
                                $id_guru = $siswa['id_guru'];
                                $query_guru = mysqli_query($conn, "SELECT nama FROM guru WHERE id_guru = '$id_guru'");
                                $guru = mysqli_fetch_assoc($query_guru);
                                echo $guru['nama']; // Menampilkan nama guru pembimbing
                                ?>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditSiswa<?php echo $siswa['id_siswa']; ?>">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="hapusSiswa(<?php echo $siswa['id_siswa']; ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div class="modal fade" id="modalTambahSiswa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="proses_tambah_siswa.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" class="form-control" name="nis" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control" name="kelas" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" class="form-control" name="foto">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <select class="form-control" name="jurusan_id">
                            <?php
                            $result_jurusan = mysqli_query($conn, "SELECT * FROM jurusan");
                            while ($jurusan = mysqli_fetch_assoc($result_jurusan)) {
                                echo "<option value='{$jurusan['id']}'>{$jurusan['nama_jurusan']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Guru Pembimbing</label>
                        <select class="form-control" name="id_guru" required>
                            <?php
                            // Ambil data guru dari tabel guru
                            $result_guru = mysqli_query($conn, "SELECT * FROM guru");
                            while ($guru = mysqli_fetch_assoc($result_guru)) {
                                echo "<option value='{$guru['id_guru']}'>{$guru['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Siswa -->
<?php
$result_siswa_edit = mysqli_query($conn, $query_siswa);
while ($siswa_edit = mysqli_fetch_assoc($result_siswa_edit)) :
?>
<div class="modal fade" id="modalEditSiswa<?php echo $siswa_edit['id_siswa']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="proses_edit_siswa.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_siswa" value="<?php echo $siswa_edit['id_siswa']; ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama" value="<?php echo $siswa_edit['nama']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" class="form-control" name="nis" value="<?php echo $siswa_edit['nis']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input type="text" class="form-control" name="kelas" value="<?php echo $siswa_edit['kelas']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" class="form-control" name="foto">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <select class="form-control" name="jurusan_id">
                            <?php
                            $result_jurusan_edit = mysqli_query($conn, "SELECT * FROM jurusan");
                            while ($jurusan_edit = mysqli_fetch_assoc($result_jurusan_edit)) {
                                $selected = ($jurusan_edit['id'] == $siswa_edit['jurusan_id']) ? 'selected' : '';
                                echo "<option value='{$jurusan_edit['id']}' $selected>{$jurusan_edit['nama_jurusan']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Guru Pembimbing</label>
                        <select class="form-control" name="id_guru">
                            <?php
                            $result_guru_edit = mysqli_query($conn, "SELECT * FROM guru");
                            while ($guru_edit = mysqli_fetch_assoc($result_guru_edit)) {
                                $selected = ($guru_edit['id_guru'] == $siswa_edit['id_guru']) ? 'selected' : '';
                                echo "<option value='{$guru_edit['id_guru']}' $selected>{$guru_edit['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>

<script>
function hapusSiswa(id) {
    Swal.fire({
        title: "Yakin ingin menghapus?",
        text: "Data tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "proses_hapus_siswa.php?id=" + id;
        }
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../admin/footer.php'; ?>
</body>
</html>
