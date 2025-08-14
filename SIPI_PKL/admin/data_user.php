    <?php
    session_start();
    include '../config/database.php';

    // Pastikan user sudah login dan memiliki akses admin
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }

    // Ambil semua data user dari database
    $query_users = "SELECT * FROM users";
    $result_users = mysqli_query($conn, $query_users);

    $result_users_edit = mysqli_query($conn, "SELECT * FROM users");

    $foto = isset($_SESSION['foto']) ? $_SESSION['foto'] : 'default-profile.png';
    ?>


    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data User - SIPI PKL</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="../assets/css/style.css">        
    </head>
    <body>
        
       
<!-- Navbar -->
<?php include '../admin/navbar.php'; ?>

        <!-- Sidebar -->
        <?php include '../admin/sidebar.php'; ?>

        <!-- Konten -->
        <div class="content">
            <div class="container">
                <h2 class="title">Manajemen Data User</h2><br>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahUser">+ Tambah User</button>
                <div class="table-container">
                    <table class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = mysqli_fetch_assoc($result_users)) : ?>
                                <tr>
                                    <td><?php echo $user['id_users']; ?></td>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['role']; ?></td>
                                    <td><img src="../assets/images/upload/<?php echo $user['foto']; ?>" alt="Foto"></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditUser<?php echo $user['id_users']; ?>">Edit</button>
                                        <button class="btn btn-danger btn-sm" onclick="hapusUser(<?php echo $user['id_users']; ?>)">Hapus</button>

                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Tambah User -->
        <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahUserLabel">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="proses_tambah_user.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" name="role">
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" name="foto">
                            </div>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit User (Looping untuk setiap user) -->
        <?php while ($user_edit = mysqli_fetch_assoc($result_users_edit)) : ?>
        <div class="modal fade" id="modalEditUser<?php echo $user_edit['id_users']; ?>" tabindex="-1" aria-labelledby="modalEditUserLabel<?php echo $user_edit['id_users']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditUserLabel<?php echo $user_edit['id_users']; ?>">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="proses_edit_user.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id_users" value="<?php echo $user_edit['id_users']; ?>">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" value="<?php echo $user_edit['username']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" name="role">
                                    <option value="admin" <?php echo ($user_edit['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="user" <?php echo ($user_edit['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control" name="foto">
                                <img src="../assets/images/upload/<?php echo $user_edit['foto']; ?>" alt="Foto" width="50" class="mt-2">
                            </div>
                            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>


       

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <script>
        function hapusUser(id) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "proses_hapus_user.php?id=" + id;
                }
            });
        }
        </script>
        <?php include '../admin/footer.php'; ?>
    </body>
    </html>
