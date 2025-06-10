<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_pinjam_barang";

include '../config.php';

if (isset($_POST['edit-barang'])) {
    $nama_barang = $_POST['nama_barang'];
    $stok_barang = $_POST['stok_barang'];
    $id = $_POST['id'];
    $file_name = str_replace(" ", "_", $_FILES['gambar_barang']['name']);
    $file_size = $_FILES['gambar_barang']['size'];
    $file_type = $_FILES['gambar_barang']['type'];
    $tmp_name = $_FILES['gambar_barang']['tmp_name'];
    $max_size = 2000000;
    $extension = substr($file_name, strpos($file_name, '.') + 1);

    // Cek apakah gambar baru diunggah
    if (isset($file_name) && !empty($file_name)) {
        if (
            ($extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $extension == "png") &&
            ($file_type == "image/jpeg" || $file_type == "image/png" || $file_type == "image/gif") &&
            $file_size <= $max_size
        ) {

            $location = "../assets/img/uploads/";

            // Pindahkan file gambar ke direktori
            if (move_uploaded_file($tmp_name, $location . $file_name)) {
                $gambar_barang = $file_name;
            } else {
                echo "<script>alert('Gagal Upload ke direktori');</script>";
                exit; // Keluar dari script jika gagal mengunggah
            }
        } else {
            echo "<script>alert('Bukan file gambar atau melebihi batas ukuran');</script>";
            exit; // Keluar dari script jika file tidak valid
        }
    } else {
        // Jika gambar tidak diunggah, gunakan gambar lama
        $gambar_barang = $_POST['gambar_barang'];
    }

    // Perbarui data barang di database
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "UPDATE tbl_barang SET nama_barang='$nama_barang', gambar_barang='$gambar_barang', stok_barang='$stok_barang' WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Berhasil Disimpan');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Gagal Edit ke Database');</script>";
    }
    mysqli_close($conn);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM tbl_barang WHERE id='$id'";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);

    $nama_barang = $data['nama_barang'];
    $gambar_barang = $data['gambar_barang'];
    $stok_barang = $data['stok_barang'];

    mysqli_close($conn);
}
?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin - Edit Barang</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/scss/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

</head>
<body>
    <?php
        include 'sidebar.php';
    ?>
    <div id="right-panel" class="right-panel">
        <?php
            include 'header.php'; 
        ?>
        <div class="breadcrumbs">
            <div class="col-sm-6">
                <div class="page-header float-left">
                    <div class="page-title" style="padding: 20px 0;">
                        <h1 style="display: unset;">Edit Barang</h1>
                        <a href="data-barang.php" class="btn btn-info btn-sm" style="margin-left: 20px;">
                            <i class="fa fa-search"></i>
                            Lihat Data Barang
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Barang</a></li>
                            <li class="active">Edit Barang</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <strong>Edit Data Barang</strong>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="nama_barang">Nama Barang</label>
                                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?php echo $nama_barang; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="gambar_barang">Gambar Barang</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="gambar_barang" name="gambar_barang">
                                            <label class="custom-file-label" for="gambar_barang">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="stok_barang">Stok Barang</label>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                        <input type="number" class="form-control" id="stok_barang" name="stok_barang" value="<?php echo $stok_barang; ?>">
                                    </div>
                                    <button type="submit" class="btn btn-success" name="edit-barang">
                                        <i class="fa fa-check"></i> Simpan
                                    </button>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    
    <script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
