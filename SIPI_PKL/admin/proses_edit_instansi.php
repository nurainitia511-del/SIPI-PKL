<?php
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_instansi = $_POST['id_instansi'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak']);
    $bidang_instansi = mysqli_real_escape_string($conn, $_POST['bidang_instansi']);

    $query = "UPDATE instansi 
              SET nama = '$nama', alamat = '$alamat', kontak = '$kontak', bidang_instansi = '$bidang_instansi' 
              WHERE id_instansi = $id_instansi";

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
                    text: 'Instansi berhasil diperbarui!',
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
    } else {
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal mengedit instansi!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'data_instansi.php';
                });
            </script>
        </body>
        </html>
        ";
        exit();
    }
}
?>
