<?php
session_start();
session_destroy(); // Hapus semua session

// Redirect ke login.php dengan notifikasi logout berhasil
header("Location: login.php?logout=success");
exit();
?>
