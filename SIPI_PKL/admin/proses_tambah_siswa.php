<?php
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $password = md5($_POST['password']); // Admin menentukan password
    $jurusan_id = $_POST['jurusan_id'];
    $id_guru = $_POST['id_guru']; // Ambil id_guru dari form

    // Cek apakah ada file foto yang diupload
    $foto = 'default-profile.png'; // Default foto
    if (!empty($_FILES['foto']['name'])) {
        $foto = time() . "_" . basename($_FILES["foto"]["name"]);
        $target_dir = "../assets/images/upload/";
        $target_file = $target_dir . $foto;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    }

    // Query untuk menambahkan data siswa ke dalam tabel
    $query = "INSERT INTO siswa (nama, nis, kelas, password, foto, jurusan_id, id_guru) 
              VALUES ('$nama', '$nis', '$kelas', '$password', '$foto', '$jurusan_id', '$id_guru')";

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
                    text: 'Siswa berhasil ditambahkan!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'data_siswa.php';
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
                    text: 'Gagal menambahkan siswa!',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'data_siswa.php';
                });
            </script>
        </body>
        </html>
        ";
        exit();
    }
}
?>
