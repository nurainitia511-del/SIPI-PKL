<?php
session_start();
include '../config/database.php';

// Pastikan user adalah admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Periksa apakah form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_users = $_POST['id_users'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Cek apakah ada file foto yang diupload
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        $target_dir = "../assets/images/upload/";
        $target_file = $target_dir . basename($foto);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi file foto
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'data_user.php';
                    });
                </script>
            </body>
            </html>
            ";
            exit();
        }

        // Pindahkan file yang diupload
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            // Update database dengan foto baru
            $query = "UPDATE users SET username='$username', role='$role', foto='$foto' WHERE id_users='$id_users'";
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
                        text: 'Gagal mengupload foto!',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'data_user.php';
                    });
                </script>
            </body>
            </html>
            ";
            exit();
        }
    } else {
        // Update database tanpa mengganti foto
        $query = "UPDATE users SET username='$username', role='$role' WHERE id_users='$id_users'";
    }

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
                    text: 'Data user berhasil diperbarui!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'data_user.php';
                });
            </script>
        </body>
        </html>
        ";
    } else {
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan, coba lagi!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'data_user.php';
                });
            </script>
        </body>
        </html>
        ";
    }
}
?>
