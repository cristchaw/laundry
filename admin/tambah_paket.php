<?php
$title = 'Tambah Data Paket';
require 'koneksi.php';

// Query untuk mendapatkan daftar outlet
$query = "SELECT * FROM outlet";
$data = mysqli_query($conn, $query);

// Cek jika tombol simpan ditekan
if (isset($_POST['btn-simpan'])) {
    // Sanitasi input user
    $nama = mysqli_real_escape_string($conn, $_POST['nama_paket']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis_paket']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $id_outlet = mysqli_real_escape_string($conn, $_POST['outlet_id']);

    // Cek apakah kombinasi nama paket, jenis paket, dan outlet sudah ada di database
    $checkQuery = "SELECT * FROM paket_cuci WHERE nama_paket = ? AND jenis_paket = ? AND outlet_id = ?";
    if ($stmt = mysqli_prepare($conn, $checkQuery)) {
        mysqli_stmt_bind_param($stmt, "ssi", $nama, $jenis, $id_outlet); // Parameter yang digunakan: nama_paket, jenis_paket, outlet_id
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            // Jika kombinasi sudah ada, tampilkan peringatan
            $_SESSION['msg'] = 'Nama paket sudah terdaftar!';
        } else {
            // Jika kombinasi belum ada, lanjutkan dengan proses insert
            $query = "INSERT INTO paket_cuci (nama_paket, jenis_paket, harga, outlet_id) 
                      VALUES (?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $query)) {
                mysqli_stmt_bind_param($stmt, "ssdi", $nama, $jenis, $harga, $id_outlet);
                $insert = mysqli_stmt_execute($stmt);
                
                if ($insert) {
                    $_SESSION['msg'] = 'Berhasil tambah paket baru';
                    header('location:paket.php');
                    exit();
                } else {
                    $_SESSION['msg'] = 'Gagal menambahkan data baru';
                    header('location: paket.php');
                    exit();
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
}

require 'header.php';
?>

<?php
// Menampilkan pesan jika ada
if (isset($_SESSION['msg'])) {
    echo '<div class="alert alert-danger">'.$_SESSION['msg'].'</div>';
    unset($_SESSION['msg']); // Menghapus pesan setelah ditampilkan
}
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
                    <a href="paket.php">Paket</a>
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
                        <div class="card-body">
                            <div class="form-group">
                                <label for="largeInput">Nama Paket</label>
                                <input type="text" name="nama_paket" class="form-control form-control" id="defaultInput" placeholder="Paket...">
                            </div>
                            <div class="form-group">
                                <label for="defaultSelect">Jenis Paket</label>
                                <select name="jenis_paket" class="form-control form-control" id="defaultSelect">
                                    <option value="kiloan">Kiloan</option>
                                    <option value="selimut">Selimut</option>
                                    <option value="bedcover">Bedcover</option>
                                    <option value="kaos">Kaos</option>
                                    <option value="lain">Lain</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Harga</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Rp</span>
                                    </div>
                                    <input type="text" class="form-control" name="harga" aria-describedby="basic-addon1">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="defaultSelect">Pilih Outlet</label>
                                <select name="outlet_id" class="form-control form-control" id="defaultSelect">
                                    <?php while ($key = mysqli_fetch_array($data)) { ?>
                                        <option value="<?= $key['id_outlet']; ?>"><?= $key['nama_outlet']; ?></option>
                                    <?php } ?>
                                </select>
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
<?php require 'footer.php'; ?>
