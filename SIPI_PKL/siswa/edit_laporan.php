<?php
session_start();
include '../config/database.php';

// CEK LOGIN SISWA
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    die("Akses ditolak. Silakan login sebagai siswa.");
}

$id_siswa = $_SESSION['id_siswa'];

// CEK ID LAPORAN
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID laporan tidak ditemukan.");
}

$id_laporan = $_GET['id'];

// AMBIL DATA LAPORAN
$query = "SELECT * FROM laporan_pkl WHERE id = ? AND id_siswa = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $id_laporan, $id_siswa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$laporan = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$laporan) {
    die("Laporan tidak ditemukan atau Anda tidak memiliki akses.");
}

// PROSES UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = $_POST['tanggal'];
    $kegiatan = $_POST['kegiatan'];
    $kendala = $_POST['kendala'];
    $solusi = $_POST['solusi'];
    $foto = $laporan['foto'];

    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "../assets/images/laporan_pkl/";
        $foto = basename($_FILES['foto']['name']);
        $target_file = $target_dir . $foto;
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
    }

    $query_update = "UPDATE laporan_pkl SET tanggal = ?, kegiatan = ?, kendala = ?, solusi = ?, foto = ? WHERE id = ? AND id_siswa = ?";
    $stmt_update = mysqli_prepare($conn, $query_update);
    mysqli_stmt_bind_param($stmt_update, "ssssssi", $tanggal, $kegiatan, $kendala, $solusi, $foto, $id_laporan, $id_siswa);

    if (mysqli_stmt_execute($stmt_update)) {
        echo "<script>alert('Laporan berhasil diperbarui!'); window.location.href='laporan_pkl.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui laporan.');</script>";
    }
    mysqli_stmt_close($stmt_update);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Laporan PKL</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      padding-top: 80px;
      background-color: #f8f9fa;
    }
    .col-md-10 {
      padding: 30px;
      background-color: #fff;
      min-height: 100vh;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
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

      <!-- Main Content -->
      <div class="col-md-10">
        <h2>Edit Laporan PKL</h2>

        <div class="card mt-4">
          <div class="card-body">
            <form action="edit_laporan.php?id=<?php echo $id_laporan; ?>" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo $laporan['tanggal']; ?>" required>
              </div>
              <div class="mb-3">
                <label for="kegiatan" class="form-label">Kegiatan yang Dilakukan</label>
                <textarea name="kegiatan" id="kegiatan" class="form-control" required><?php echo $laporan['kegiatan']; ?></textarea>
              </div>
              <div class="mb-3">
                <label for="kendala" class="form-label">Kendala / Tantangan</label>
                <textarea name="kendala" id="kendala" class="form-control"><?php echo $laporan['kendala']; ?></textarea>
              </div>
              <div class="mb-3">
                <label for="solusi" class="form-label">Solusi yang Dilakukan</label>
                <textarea name="solusi" id="solusi" class="form-control"><?php echo $laporan['solusi']; ?></textarea>
              </div>
              <div class="mb-3">
                <label for="foto" class="form-label">Upload Foto Baru (Opsional)</label>
                <input type="file" name="foto" id="foto" class="form-control">
                <?php if (!empty($laporan['foto'])): ?>
                  <p class="mt-2">Foto Lama:</p>
                  <img src="../assets/images/laporan_pkl/<?php echo $laporan['foto']; ?>" width="120" alt="Foto Lama">
                <?php endif; ?>
              </div>
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
              <a href="laporan_pkl.php" class="btn btn-secondary">Batal</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include '../siswa/footer.php'; ?>
</body>
</html>
