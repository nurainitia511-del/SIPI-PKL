<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../config/database.php';

// Debugging: Tampilkan semua parameter GET yang diterima
if (empty($_GET)) {
    die("DEBUG: Tidak ada parameter GET yang diterima!");
}

// Debugging: Periksa apakah 'id' dikirim melalui GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("DEBUG: ID tidak ditemukan atau kosong! URL: " . $_SERVER['REQUEST_URI']);
}

$id_instansi = intval($_GET['id']); // Gunakan 'id' sesuai dengan URL

// Debugging: Cek apakah ID valid (harus angka lebih dari 0)
if ($id_instansi <= 0) {
    die("DEBUG: ID tidak valid! ID yang diterima: " . htmlspecialchars($_GET['id']));
}

// Debugging: Cek apakah ID ada di database
$cek_instansi = mysqli_query($conn, "SELECT * FROM instansi WHERE id_instansi = $id_instansi");
if (!$cek_instansi) {
    die("DEBUG: Error SQL saat cek ID: " . mysqli_error($conn));
}

if (mysqli_num_rows($cek_instansi) == 0) {
    die("DEBUG: Instansi dengan ID $id_instansi tidak ditemukan di database!");
}

// Jalankan query hapus
$query = "DELETE FROM instansi WHERE id_instansi = $id_instansi";
if (!mysqli_query($conn, $query)) {
    die("DEBUG: Error saat menghapus data: " . mysqli_error($conn));
}

// Jika sukses, tampilkan alert sukses
echo "
<html>
<head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Instansi berhasil dihapus!',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'data_instansi.php';
        });
    </script>
</body>
</html>
";
exit();
?>
