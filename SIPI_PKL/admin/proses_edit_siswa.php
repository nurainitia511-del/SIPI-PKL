<?php
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_siswa = $_POST['id_siswa'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
    $jurusan_id = $_POST['jurusan_id'];
    $id_guru = $_POST['id_guru']; // Ambil id guru pembimbing dari form

    // Ambil data siswa lama
    $query_old = mysqli_query($conn, "SELECT foto, password FROM siswa WHERE id_siswa = $id_siswa");
    $siswa_old = mysqli_fetch_assoc($query_old);
    $foto = $siswa_old['foto']; // Gunakan foto lama jika tidak ada yang baru
    $password = $siswa_old['password']; // Gunakan password lama jika tidak diubah

    // Jika admin memasukkan password baru, hash dengan MD5
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
    }

    // Cek apakah ada file foto yang diupload
    if (!empty($_FILES['foto']['name'])) {
        $foto = time() . "_" . basename($_FILES["foto"]["name"]);
        $target_dir = "../assets/images/upload/";
        $target_file = $target_dir . $foto;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    }

    // Query untuk update data siswa dengan tambahan kolom id_guru
    $query = "UPDATE siswa SET 
              nama='$nama', nis='$nis', kelas='$kelas', password='$password', foto='$foto', jurusan_id='$jurusan_id', id_guru='$id_guru' 
              WHERE id_siswa='$id_siswa'";

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
                    text: 'Data siswa berhasil diperbarui!',
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
                    text: 'Gagal mengedit siswa!',
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
