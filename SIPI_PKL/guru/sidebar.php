<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4 class="text-center py-3"><i class="fas fa-desktop"></i> SIPI PKL</h4>
    <hr>
    <a href="dashboard.php" class="menu-link"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="lihat_siswa.php" class="menu-link"><i class="fas fa-user-graduate"></i> Lihat Data Siswa</a>
    <a href="pengajuan_pkl.php" class="menu-link"><i class="fas fa-file-alt"></i>Pengajuan PKL</a>
    <a href="laporan_pkl_siswa.php" class="menu-link"><i class="fas fa-file-alt"></i> Laporan PKL Siswa</a>

    
   
</div>

<!-- Tombol Toggle Sidebar -->
<button class="toggle-btn" onclick="toggleSidebar()">☰ Menu</button>

<!-- Script untuk Sidebar & Logout -->
<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("show");
}

function confirmLogout() {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Anda akan keluar dari sistem!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Logout',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    });
}
</script>

<!-- CSS -->
<style>


/* Sidebar */
.sidebar {
    width: 260px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: linear-gradient(45deg, #4facfe,rgb(43, 149, 241));
    color: white;
    
    z-index: 1000; /* Lebih tinggi dari navbar */
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
}

/* Saat sidebar terbuka */
.sidebar.show {
    left: 0;
}

/* Title */
.sidebar h4 {
    font-size: 22px;
    font-weight: bold;
    padding: 10px;
    margin-bottom: 20px;
}



.sidebar a i {
    font-size: 22px; 
    width: 20px; /* Menyamakan ukuran lebar ikon */
    text-align: center;
}

/* Hover Effect */
.sidebar a:hover, .logout-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

/* Menyesuaikan ikon dan teks agar sejajar */
.sidebar a {
    display: flex;
    align-items: center;
    gap: 12px; /* Jarak antara ikon dan teks */
    padding: 12px 20px;
    font-size: 16px;
    width: 100%;
    text-align: left;
    font-weight: bold;
    padding: 14px 22px;
}




/* Tombol Logout */
.logout-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 15px;
    transition: all 0.3s ease-in-out;
}

.logout-btn i {
    margin-right: 8px;
}

/* Hover Logout */
.logout-btn:hover {
    background: #b71c1c;
    transform: scale(1.05);
}

/* Tombol Toggle */
.toggle-btn {
    position: fixed;
    top: 15px;
    left: 15px;
    background: #0080ff;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 18px;
    cursor: pointer;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
}

.toggle-btn:hover {
    background: #004aad;
}

/* Responsif */
@media (min-width: 769px) {
    .sidebar {
        left: 0; /* Sidebar selalu terbuka di layar besar */
    }

    .toggle-btn {
        display: none; /* Tombol menu hilang di layar besar */
    }
}
</style>
