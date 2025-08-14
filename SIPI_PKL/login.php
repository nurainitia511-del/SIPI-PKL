<?php
session_start();
include 'config/database.php';

$alert = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); 

    if (empty($username) || empty($password)) {
        $alert = "<script>
            Swal.fire({
                title: 'Login Gagal!',
                text: 'Username dan password harus diisi!',
                icon: 'warning'
            });
        </script>";
    } else {
        // Cek di tabel users (admin/guru jika digabung)
        $query_users = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result_users = mysqli_query($conn, $query_users);

        // Cek di tabel siswa
        $query_siswa = "SELECT * FROM siswa WHERE nis = '$username' AND password = '$password'";
        $result_siswa = mysqli_query($conn, $query_siswa);

        // Cek di tabel guru
        $query_guru = "SELECT * FROM guru WHERE nip = '$username' AND password = '$password'";
        $result_guru = mysqli_query($conn, $query_guru);

        if (!$result_users || !$result_siswa || !$result_guru) {
            die("Query error: " . mysqli_error($conn));
        }

        // LOGIN SEBAGAI ADMIN
        if (mysqli_num_rows($result_users) > 0) {
            $user = mysqli_fetch_assoc($result_users);

            if ($user['role'] === 'admin') {
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = 'admin';
                $_SESSION['id_users'] = $user['id_users']; 

                $alert = "<script>
                    Swal.fire({
                        title: 'Login Berhasil!',
                        text: 'Selamat datang Admin!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location = 'admin/dashboard.php'; 
                    });
                </script>";
            } 
        }
        // LOGIN SEBAGAI SISWA
        elseif (mysqli_num_rows($result_siswa) > 0) {
            $user = mysqli_fetch_assoc($result_siswa);
            $_SESSION['username'] = $user['nama'];
            $_SESSION['role'] = 'siswa';
            $_SESSION['id_siswa'] = $user['id_siswa']; 

            $alert = "<script>
                Swal.fire({
                    title: 'Login Berhasil!',
                    text: 'Selamat datang Siswa!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location = 'siswa/siswa_dashboard.php';
                });
            </script>";
        }
        // LOGIN SEBAGAI GURU
        // LOGIN SEBAGAI GURU
elseif (mysqli_num_rows($result_guru) > 0) {
    $user = mysqli_fetch_assoc($result_guru);
    $_SESSION['username'] = $user['nip'];    // tetap pakai NIP untuk keperluan query
    $_SESSION['nama'] = $user['nama'];       // simpan nama untuk ditampilkan di navbar

    $_SESSION['role'] = 'guru';
    $_SESSION['id_guru'] = $user['id_guru']; 
 

            $alert = "<script>
                Swal.fire({
                    title: 'Login Berhasil!',
                    text: 'Selamat datang Guru!',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location = 'guru/dashboard.php';
                });
            </script>";
        }
        // LOGIN GAGAL
        else {
            $alert = "<script>
                Swal.fire({
                    title: 'Login Gagal!',
                    text: 'Username atau password salah!',
                    icon: 'error'
                });
            </script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPI PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Agar card selalu di tengah */
    margin: 0; /* Menghindari padding default */
    font-family: 'Poppins', sans-serif;
    background: url('assets/images/bg.jpeg') no-repeat center center fixed;
    background-size: cover;
    position: relative;
}


    /* Overlay hitam transparan */
    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6); /* Warna hitam dengan transparansi */
        z-index: -1;
    }
        .login-card {
            width: 400px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
            animation: fadeIn 1s ease-in-out;
        }
        .login-image {
            width: 120px;
            height: 120px;
            margin: -60px auto 10px;
            border-radius: 50%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            animation: bounceIn 1s ease-in-out;
        }
        .login-image img {
            width: 90px;
            height: auto;
        }
        .login-btn {
            background: #4facfe;
            border: none;
            transition: transform 0.2s, background 0.3s;
        }
        .login-btn:hover {
            background: #00c6ff;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounceIn {
            0% { transform: scale(0.8); opacity: 0; }
            80% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>

<?php
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Logout Berhasil!',
                text: 'Anda telah keluar dari sistem.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.history.replaceState(null, '', 'login.php');
            });
        });
    </script>";
}
?>

<div class="login-card">
    <div class="login-image">
        <img src="assets/images/logo.png" alt="User Icon">
    </div>
    <h3 class="mb-4">SISTEM INFORMASI PEMILIHAN INSTANSI 
    PRAKTEK KERJA LAPANGAN (PKL)</h3>
    <h2 class="mb-4">LOGIN</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label"><b>Username / NIS</b></label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label"><b>Password</b></label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100 login-btn">Login</button>
    </form>
</div>

<?php
if (!empty($alert)) {
    echo $alert;
}
?>

</body>
</html>
