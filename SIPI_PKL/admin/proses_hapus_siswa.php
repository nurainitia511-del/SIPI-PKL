<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Validasi ID
    if (!is_numeric($id)) {
        die("ID tidak valid!");
    }

    // Ganti 'id' dengan kolom yang benar, misalnya 'id_siswa'
    $query_foto = mysqli_query($conn, "SELECT foto FROM siswa WHERE id_siswa = $id");
    if (!$query_foto) {
        die("Query error: " . mysqli_error($conn));
    }

    $siswa = mysqli_fetch_assoc($query_foto);

    if ($siswa && isset($siswa['foto']) && $siswa['foto'] !== 'default-profile.png') {
        $foto_path = "../assets/images/upload/" . $siswa['foto'];
        if (file_exists($foto_path)) {
            unlink($foto_path);
        }
    }

    $query = "DELETE FROM siswa WHERE id_siswa = $id";

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
                    text: 'Siswa berhasil dihapus!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'data_siswa.php';
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
