<?php
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama_jurusan = mysqli_real_escape_string($conn, $_POST['nama_jurusan']);

    $query = "UPDATE jurusan SET nama_jurusan = '$nama_jurusan' WHERE id = $id";
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
                    text: 'Jurusan berhasil diperbarui!',
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
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal mengedit jurusan!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'data_jurusan.php';
                });
            </script>
        </body>
        </html>
        ";
        exit();
    }
}
?>
