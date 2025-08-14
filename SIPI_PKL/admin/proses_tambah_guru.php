<?php
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $password = md5($_POST['password']); // Enkripsi password dengan MD5

    // Cek jika tidak ada foto yang diupload
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        $target_dir = "../assets/images/upload/";
        $target_file = $target_dir . basename($foto);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    } else {
        $foto = "default-profile.png"; // Gunakan foto default jika tidak diupload
    }

    // Insert data ke database, termasuk password
    $query = "INSERT INTO guru (nama, nip, foto, password) VALUES ('$nama', '$nip', '$foto', '$password')";
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
                    text: 'Guru berhasil ditambahkan!',
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
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal menambahkan guru!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back();
                });
            </script>
        </body>
        </html>
        ";
        exit();
    }
}
?>
