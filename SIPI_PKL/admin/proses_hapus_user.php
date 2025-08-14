<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../config/database.php';

// Pastikan user adalah admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Pastikan ID user tersedia dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: data_user.php");
    exit();
}

$id_users = $_GET['id'];

// Ambil nama file foto sebelum dihapus
$query = "SELECT foto FROM users WHERE id_users = '$id_users'";
$result = mysqli_query($conn, $query);

if (!$result) {
    header("Location: data_user.php");
    exit();
}

$data = mysqli_fetch_assoc($result);
if (!$data) {
    header("Location: data_user.php");
    exit();
}

$foto = $data['foto'] ?? '';

// **CEK APAKAH FOTO DIGUNAKAN USER LAIN**
$query_check = "SELECT COUNT(*) AS total FROM users WHERE foto = '$foto'";
$result_check = mysqli_query($conn, $query_check);
$count_data = mysqli_fetch_assoc($result_check);

// Jika hanya satu user yang menggunakan foto, baru boleh dihapus
if ($foto && $foto !== 'default-profile.png' && $count_data['total'] == 1) {
    $file_path = "../assets/images/upload/" . $foto;
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

// Hapus user dari database
$query_delete = "DELETE FROM users WHERE id_users = '$id_users'";
if (mysqli_query($conn, $query_delete)) {
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'User berhasil dihapus!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'data_user.php';
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
                title: 'Error!',
                text: 'Gagal menghapus user!',
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
?>
