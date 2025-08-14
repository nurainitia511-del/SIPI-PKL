<?php
session_start();
include '../config/database.php';

// CEK LOGIN SISWA
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    die("Akses ditolak. Silakan login sebagai siswa.");
}

$id_siswa = $_SESSION['id_siswa'];

// PROSES SIMPAN LAPORAN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_laporan'])) {
    $tanggal = $_POST['tanggal'];
    $kegiatan = $_POST['kegiatan'];
    $kendala = $_POST['kendala'];
    $solusi = $_POST['solusi'];
    $foto = '';

    // UPLOAD FOTO
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "../assets/images/laporan_pkl/";
        $foto = basename($_FILES['foto']['name']);
        $target_file = $target_dir . $foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
    }

    // INSERT KE DATABASE
    $query = "INSERT INTO laporan_pkl (id_siswa, tanggal, kegiatan, kendala, solusi, foto) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isssss", $id_siswa, $tanggal, $kegiatan, $kendala, $solusi, $foto);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Laporan berhasil disimpan!'); window.location.href='laporan_pkl.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan laporan.');</script>";
    }
    mysqli_stmt_close($stmt);
}

// PROSES EDIT LAPORAN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_laporan'])) {
    $id_laporan = $_POST['id_laporan'];
    $tanggal = $_POST['tanggal'];
    $kegiatan = $_POST['kegiatan'];
    $kendala = $_POST['kendala'];
    $solusi = $_POST['solusi'];
    
    // CEK JIKA ADA UPLOAD FOTO BARU
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "../assets/images/laporan_pkl/";
        $foto = basename($_FILES['foto']['name']);
        $target_file = $target_dir . $foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);

        // UPDATE DENGAN FOTO BARU
        $query = "UPDATE laporan_pkl SET tanggal = ?, kegiatan = ?, kendala = ?, solusi = ?, foto = ? WHERE id = ? AND id_siswa = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssssi", $tanggal, $kegiatan, $kendala, $solusi, $foto, $id_laporan, $id_siswa);
    } else {
        // UPDATE TANPA MENGGANTI FOTO
        $query = "UPDATE laporan_pkl SET tanggal = ?, kegiatan = ?, kendala = ?, solusi = ? WHERE id = ? AND id_siswa = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssii", $tanggal, $kegiatan, $kendala, $solusi, $id_laporan, $id_siswa);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Laporan berhasil diperbarui!'); window.location.href='laporan_pkl.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate laporan.');</script>";
    }
    mysqli_stmt_close($stmt);
}

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
    <title>Laporan PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
  <style>
body {
    padding-top: 10px;
    background-color: #f8f9fa;
}

.container-fluid {
    padding-left: 0;
    padding-right: 0;
}

.col-md-10 {
    padding-top: 70px;
    background-color: #fff;
    min-height: 100vh;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

form {
    margin-bottom: 50px;
}
</style>


</style>
</head>
<body>
<?php include '../siswa/navbar.php'; ?>


<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 bg-light">
        <?php include '../siswa/sidebar.php'; ?>
    </div>


    <!-- Main content -->
    <div class="col-md-10" >
        <div class="mt-4">
            <h2>Laporan PKL</h2>
            <form action="laporan_pkl.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="submit_laporan" value="1">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="kegiatan" class="form-label">Kegiatan yang Dilakukan</label>
                    <textarea name="kegiatan" id="kegiatan" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="kendala" class="form-label">Kendala / Tantangan</label>
                    <textarea name="kendala" id="kendala" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="solusi" class="form-label">Solusi yang Dilakukan</label>
                    <textarea name="solusi" id="solusi" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Upload Foto (Opsional)</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Simpan Laporan</button>
            </form>

            <h3 class="mt-5">Laporan PKL Anda</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Kendala</th>
                        <th>Solusi</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($laporan = mysqli_fetch_assoc($result_laporan)): ?>
                        <tr>
                            <td><?php echo $laporan['tanggal']; ?></td>
                            <td><?php echo $laporan['kegiatan']; ?></td>
                            <td><?php echo $laporan['kendala']; ?></td>
                            <td><?php echo $laporan['solusi']; ?></td>
                            <td>
                                <?php if (!empty($laporan['foto'])): ?>
                                    <img src="../assets/images/laporan_pkl/<?php echo $laporan['foto']; ?>" width="100">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_laporan.php?id=<?php echo $laporan['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="cetak_laporan.php" class="btn btn-success mb-3" target="_blank">Cetak Laporan</a>
        </div>
    </div>
  </div>
</div>
<?php include '../siswa/footer.php'; ?>
</body>
</html>
