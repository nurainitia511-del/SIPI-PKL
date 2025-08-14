<!-- Navbar -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config/database.php';

// Ambil data siswa berdasarkan username yang login
$username = $_SESSION['username'];
$query = "SELECT foto FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$users = mysqli_fetch_assoc($result);

$foto_profil = $users['foto'] ?? 'default.png'; // Jika foto kosong, gunakan gambar default
?>
<div class="navbar">
        <h4></h4>
        <div class="user-info">
            <img src="../assets/images/upload/<?php echo $foto_profil; ?>" alt="Foto Profil">
            <span>Halo, <strong><?php echo $_SESSION['username']; ?></strong></span>
           
            <a href="../logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>