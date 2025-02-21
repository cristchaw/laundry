<?php
$title = 'Tambah Data Outlet';
require 'koneksi.php';

if (isset($_POST['btn-simpan'])) {
    $nama = $_POST['nama_outlet'];
    $alamat = $_POST['alamat_outlet'];
    $telp = $_POST['telp_outlet'];

    // Cek apakah nama outlet sudah ada di database
    $query_check = "SELECT * FROM outlet WHERE nama_outlet = '$nama'";
    $result_check = mysqli_query($conn, $query_check);
    
    // Jika outlet dengan nama yang sama sudah ada
    if (mysqli_num_rows($result_check) > 0) {
        $_SESSION['msg'] = 'Nama outlet sudah terdaftar!';
        header('location: tambah_outlet.php'); // Pastikan ini menuju halaman yang sesuai untuk menampilkan pesan
        exit;
    }

    // Jika nama outlet belum ada, lakukan proses insert
    $query = "INSERT INTO outlet (nama_outlet, alamat_outlet, telp_outlet) values ('$nama', '$alamat', '$telp')";
    $insert = mysqli_query($conn, $query);

    if ($insert) {
        $_SESSION['msg'] = 'Berhasil Menyimpan Data';
        header('location: outlet.php');
    } else {
        $_SESSION['msg'] = 'Gagal menambahkan data baru!!!';
        header('location: outlet.php');
    }
}

require 'header.php';
?>

<div class="content">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Forms</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="index.php">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="outlet.php">Outlet</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Tambah Outlet</a>
                </li>
            </ul>
        </div>
        
        <!-- Menampilkan pesan jika ada -->
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-warning">
                <?= $_SESSION['msg']; ?>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><?= $title; ?></div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="largeInput">Nama Outlet</label>
                                <input type="text" name="nama_outlet" class="form-control form-control" id="defaultInput" placeholder="Outlet...">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat Outlet</label>
                                <textarea class="form-control" rows="5" name="alamat_outlet"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="largeInput">No Telepon</label>
                                <input type="text" name="telp_outlet" class="form-control form-control" id="defaultInput" placeholder="No Telp..." maxlength="15">
                            </div>
                            <div class="card-action">
                                <button type="submit" name="btn-simpan" class="btn btn-success">Submit</button>
                                <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-danger">Batal</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
