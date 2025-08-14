<!-- Navbar -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config/database.php';

// Ambil data siswa berdasarkan username yang login
$username = $_SESSION['username'];
$query = "SELECT foto FROM siswa WHERE nama = '$username'";
$result = mysqli_query($conn, $query);
$siswa = mysqli_fetch_assoc($result);

$foto_profil = $siswa['foto'] ?? 'default.png'; // Jika foto kosong, gunakan gambar default
?>

<div class="navbar">
        <h4></h4>
        <div class="user-info">
        <img src="../assets/images/upload/<?php echo $foto_profil; ?>" alt="Foto Profil">
            <span>Halo, <strong><?php echo $_SESSION['username']; ?></strong></span>
           
            <a href="../logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>