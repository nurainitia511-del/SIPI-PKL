<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/database.php';

$foto_profil = 'default.png'; // default fallback
$nama_pengguna = $_SESSION['username']; // default

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'guru') {
        $nip = $_SESSION['username'];
        $query_nav = "SELECT nama, foto FROM guru WHERE nip = '$nip'";
        $result_nav = mysqli_query($conn, $query_nav);
        if ($result_nav && mysqli_num_rows($result_nav) > 0) {
            $guru = mysqli_fetch_assoc($result_nav);
            $foto_profil = !empty($guru['foto']) ? $guru['foto'] : 'default.png';

            // Simpan nama ke session agar bisa dipakai di mana saja
            $_SESSION['nama'] = $guru['nama'];
            $nama_pengguna = $guru['nama'];
        }
    } else {
        $username = $_SESSION['username'];
        $query_nav = "SELECT foto FROM users WHERE username = '$username'";
        $result_nav = mysqli_query($conn, $query_nav);
        if ($result_nav && mysqli_num_rows($result_nav) > 0) {
            $user = mysqli_fetch_assoc($result_nav);
            $foto_profil = !empty($user['foto']) ? $user['foto'] : 'default.png';
        }

        // Admin/siswa tidak punya nama di database (pakai username saja)
        $nama_pengguna = $username;
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>

<!-- HTML Navbar -->
<div class="navbar">
    <h4></h4>
    <div class="user-info">
        <img src="../assets/images/upload/<?php echo htmlspecialchars($foto_profil); ?>" alt="Foto Profil">
        <span>Halo, <strong><?php echo htmlspecialchars($nama_pengguna); ?></strong></span>
        <a href="../logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
