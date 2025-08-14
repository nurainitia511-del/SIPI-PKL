<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../config/database.php';

if (isset($_GET['id_guru'])) {
    $id_guru = $_GET['id_guru'];

    // Cek apakah ID valid (harus angka)
    if (!is_numeric($id_guru)) {
        die("ID tidak valid!");
    }

    // Ambil nama file foto sebelum menghapus data
    $query_foto = mysqli_query($conn, "SELECT foto FROM guru WHERE id_guru = $id_guru");
    $data_foto = mysqli_fetch_assoc($query_foto);
    $foto = $data_foto['foto'];

    // Hapus foto jika bukan default
    if ($foto != "default-profile.png") {
        unlink("../assets/images/upload/" . $foto);
    }

    // Jalankan query hapus
    $query = "DELETE FROM guru WHERE id_guru = $id_guru";

    if (mysqli_query($conn, $query)) {
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Guru berhasil dihapus!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'data_guru.php';
                });
            </script>
        </body>
        </html>
        ";
        exit();
    } else {
        die("Error SQL: " . mysqli_error($conn));
    }
} else {
    die("ID tidak ditemukan!");
}
?>
