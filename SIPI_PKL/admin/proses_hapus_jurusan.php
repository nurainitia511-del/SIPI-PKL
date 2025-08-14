<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Cek apakah ID valid (harus angka)
    if (!is_numeric($id)) {
        die("ID tidak valid!");
    }

    // Jalankan query hapus
    $query = "DELETE FROM jurusan WHERE id = $id";

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
                    text: 'Jurusan berhasil dihapus!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'data_jurusan.php';
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
