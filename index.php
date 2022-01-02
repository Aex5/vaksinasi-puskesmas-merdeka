<!-- mengkoneksikan ke databse -->
<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "vaksinasi";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("tidak bisa terhubung ke database");
}
$nik        = "";
$nama       = "";
$gender     = "";
$vaksinasi   = "";
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id         = $_GET['id'];
    $sql1       = "delete from pendaftaran where id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "berhsil menghapus data";
    } else {
        $error = "gagal melakukan delete data";
    }
}

if ($op == 'edit') {
    $id             = $_GET['id'];
    $sql1           = "select * from pendaftaran where id = '$id'";
    $q1             = mysqli_query($koneksi, $sql1);
    $r1             = mysqli_fetch_array($q1);
    $nik            = $r1['nik'];
    $nama           = $r1['nama'];
    $gender         = $r1['gender'];
    $vaksinasi       = $r1['vaksinasi'];

    if ($nik == '') {
        $error = "data tidak ditemukan";
    }
}


if (isset($_POST['simpan'])) {  // untuk create 
    $nik    = $_POST['nik'];
    $nama   = $_POST['nama'];
    $gender = $_POST['gender'];
    $vaksinasi = $_POST['vaksinasi'];

    if ($nik && $nama && $gender && $vaksinasi) {
        if ($op == 'edit') { // untuk uodate 
            $sql1       = "update pendaftaran set nik = '$nik',nama = '$nama',gender = '$gender',vaksinasi = '$vaksinasi' where id = '$id'";
            $q1         = mysqli_query($koneksi, $sql1);

            if ($q1) {
                $sukses = "Data berhasil di update";
            } else {
                $error = "Data gagal di update";
            }
        } else { // untuk insert
            $sql1 = "insert into pendaftaran(nik,nama,gender,vaksinasi) values ('$nik','$nama','$gender','$vaksinasi')";
            $q1   =  mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses   = "Berhasil memasukan data baru";
            } else {
                $eror     = "gagal memasukan data";
            }
        }
    } else {
        $error = "silahkan masukan data";
    }
}
?>

<!-- doc html -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data pendaftaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- memeasukan data -->
        <div class="card">
            <h5 class="card-header">create / edit data</h5>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php"); // 5 = detik
                }
                ?>
                <?php

                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nik" name="nik" value="<?php echo $nik ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="gender" class="col-sm-2 col-form-label">Gender</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="gender" name="gender" value="<?php echo $gender ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="vaksinasi" class="col-sm-2 col-form-label">Vaksinasi</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="vaksinasi" id="vaksinasi">
                                <option value="">Vaksinasi Ke -</option>
                                <option value="vaksinasi-1" <?php if ($vaksinasi == "vaksinasi-1") echo "selected" ?>>vaksinasi-1</option>
                                <option value="vaksinasi-2" <?php if ($vaksinasi == "vaksinasi-2 ") echo "selected" ?>>vaksinasi-2</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkkan data -->
        <div class="card">
            <h5 class="card-header text-white bg-secondary">Data pendaftaran</h5>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIK</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Vaksinasi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    <tbody>
                        <?php
                        $sql2 = "select * from pendaftaran order by id desc";
                        $q2   = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id          = $r2['id'];
                            $nik         = $r2['nik'];
                            $nama        = $r2['nama'];
                            $gender      = $r2['gender'];
                            $vaksinasi    = $r2['vaksinasi'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $nik ?> </td>
                                <td scope="row"><?php echo $nama ?> </td>
                                <td scope="row"><?php echo $gender ?> </td>
                                <td scope="row"><?php echo $vaksinasi ?> </td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('apakah anda yakin ?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr>
                        <?php

                        }
                        ?>
                    </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>

</html>