<?php
$title = 'Tambah Data Pelanggan';
require 'koneksi.php';

if (isset($_POST['btn-simpan'])) {
    $nama = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat_pelanggan'];
    $no_ktp = $_POST['no_ktp'];
    $telp = $_POST['telp_pelanggan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];

    // Mengecek apakah No KTP sudah ada di database
    $checkQuery = "SELECT * FROM pelanggan WHERE no_ktp = ?";
    if ($stmt = mysqli_prepare($conn, $checkQuery)) {
        mysqli_stmt_bind_param($stmt, "s", $no_ktp);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // No KTP sudah ada, tampilkan pesan peringatan
            $_SESSION['msg'] = 'Nama Pelanggan sudah terdaftar!';
            header('Location: tambah_pelanggan.php');
            exit();
        } else {
            // No KTP belum ada, lanjutkan penyimpanan
            $query = "INSERT INTO pelanggan (nama_pelanggan, alamat_pelanggan, no_ktp, telp_pelanggan, jenis_kelamin) 
                      VALUES (?, ?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $query)) {
                mysqli_stmt_bind_param($stmt, "sssss", $nama, $alamat, $no_ktp, $telp, $jenis_kelamin);
                $insert = mysqli_stmt_execute($stmt);
                if ($insert) {
                    $_SESSION['msg'] = 'Berhasil menambahkan pelanggan baru';
                    header('Location: pelanggan.php');
                } else {
                    $_SESSION['msg'] = 'Gagal menambahkan data baru!!!';
                    header('Location: tambah_pelanggan.php');
                }
            }
        }
        mysqli_stmt_close($stmt);
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
                    <a href="pelanggan.php">Pelanggan</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#"><?= $title; ?></a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><?= $title; ?></div>
                    </div>
                    <form action="" method="POST">
                        <!-- Menampilkan pesan peringatan jika ada duplikasi No KTP -->
                        <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] <> '') { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $_SESSION['msg']; ?>
                            </div>
                        <?php
                        $_SESSION['msg'] = '';
                        }
                        ?>

                        <div class="card-body">
                            <div class="form-group">
                                <label for="largeInput">No KTP Pelanggan</label>
                                <input type="text" name="no_ktp" class="form-control form-control" id="defaultInput" placeholder="No KTP..." maxlength="20">
                            </div>
                            <div class="form-group">
                                <label for="largeInput">Nama Pelanggan</label>
                                <input type="text" name="nama_pelanggan" class="form-control form-control" id="defaultInput" placeholder="Nama...">
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat Pelanggan</label>
                                <textarea class="form-control" rows="5" name="alamat_pelanggan"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="largeInput">No Telepon</label>
                                <input type="text" name="telp_pelanggan" class="form-control form-control" id="defaultInput" placeholder="No Telp...">
                            </div>
                            <div class="form-group">
                                <label for="defaultSelect">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control form-control" id="defaultSelect">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div class="card-action">
                                <button type="submit" name="btn-simpan" class="btn btn-success">Submit</button>
                                <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-danger">Batal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'footer.php'; ?>
