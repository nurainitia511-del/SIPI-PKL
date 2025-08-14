<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4 class="text-center py-3"><i class="fas fa-desktop"></i> SIPI PKL</h4>
    <hr>
    <a href="siswa_dashboard.php" class="menu-link"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="guru_pembimbing.php" class="menu-link"><i class="fas fa-chalkboard-teacher"></i> Guru Pembimbing</a>
    <a href="pilih_instansi.php" class="menu-link"><i class="fas fa-map-marked-alt"></i> Memilih Instansi</a>
    <a href="Laporan_pkl.php" class="menu-link"><i class="fas fa-file-alt"></i> Laporan PKL</a>
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
    background: linear-gradient(180deg, #4facfe,rgb(43, 149, 241)); /* Warna disesuaikan */
    color: white;
    z-index: 1000;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease-in-out;
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

/* Menyesuaikan ikon dan teks agar sejajar */
.sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 22px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    text-decoration: none;
    transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
}

/* Ikon pada sidebar */
.sidebar a i {
    font-size: 22px; 
    width: 20px; 
    text-align: center;
}

/* Efek hover */
.sidebar a:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

/* Tombol Toggle */
.toggle-btn {
    position: fixed;
    top: 15px;
    left: 15px;
    background: #00c6ff; /* Warna tombol lebih terang */
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 18px;
    cursor: pointer;
    border-radius: 5px;
    transition: all 0.3s ease-in-out;
}

.toggle-btn:hover {
    background: #4facfe; /* Warna lebih lembut */
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
