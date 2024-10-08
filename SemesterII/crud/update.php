<!DOCTYPE html>
<html>
<head>
    <title>Form Memesan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <?php

    //Include file koneksi, untuk koneksikan ke database
    include "koneksi.php";

    //Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $nama="";
    $variant="";
    $metode_pembayaran="";
    //Cek apakah ada nilai yang dikirim menggunakan methos GET dengan nama id
    if (isset($_GET['id'])) {
        $id=input($_GET["id"]);

        $sql="select * from pesanan where id=$id";
        $hasil=mysqli_query($kon,$sql);
        $data = mysqli_fetch_assoc($hasil);


    }

    //Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $id=htmlspecialchars($_POST["id"]);
        $nama=input($_POST["nama"]);
        $harga_satuan = 0; // Nilai default atau nilai lain yang sesuai dengan kebutuhan Anda
        $sql_menu = "SELECT harga FROM menu WHERE nama = '$nama'";
        $result_menu = mysqli_query($kon, $sql_menu);
    
        if ($result_menu) {
            if(mysqli_num_rows($result_menu) > 0) {
                $row_menu = mysqli_fetch_assoc($result_menu);
                $harga_satuan = $row_menu['harga'];
            } else {
                // Handle jika tidak ada data yang ditemukan
                $error = "Menu tidak ditemukan atau harga tidak tersedia.";
            }
        } else {
            // Handle jika query gagal
            $error = "Gagal mengambil data harga dari menu.";

        }
        $variant=input($_POST["variant"]);
        $kuantitas=input($_POST["kuantitas"]);
        $metode_pembayaran=input($_POST["metode_pembayaran"]);
        $subtotal = intval($harga_satuan) * intval($kuantitas);

        //Query update data pada tabel anggota
        $sql="UPDATE pesanan SET
			nama='$nama',
            harga_satuan='$harga_satuan',
			variant='$variant',
			kuantitas='$kuantitas',
			metode_pembayaran='$metode_pembayaran',
            subtotal='$subtotal'
			where id=$id";

        //Mengeksekusi atau menjalankan query diatas
        $hasil=mysqli_query($kon,$sql);

        //Kondisi apakah berhasil atau tidak dalam mengeksekusi query diatas
        if ($hasil) {
            header("Location:bntrya.php");
        }
        else {
            echo "<div class='alert alert-danger'> Data Gagal disimpan.</div>";

        }

    }

    ?>
    <h2>Update Data</h2>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <div class="form-group">
                <label>Nama</label>
                <div class="col-sm-10">
                    <select class="form-control" name="nama" id="nama">
                        <option value="">- Menu -</option>
                        <option value="Ice Cream" <?php if ($nama == "Ice Cream")
                            echo "selected" ?>>Ice Cream</option>
                            <option value="Ice Cream Jasmine Tea" <?php if ($nama == "Ice Cream Jasmine Tea")
                            echo "selected" ?>>Ice Cream Jasmine Tea</option>
                            <option value="Ice Cream Earl Grey Tea" <?php if ($nama == "Ice Cream Earl Grey Tea")
                            echo "selected" ?>>Ice Cream Earl Grey Tea</option>
                            <option value="Boba Sundae" <?php if ($nama == "Boba Sundae")
                            echo "selected" ?>>Boba Sundae
                            </option>
                            <option value="Boba Shake" <?php if ($nama == "Boba Shake")
                            echo "selected" ?>>Boba Shake</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                <label>Variant</label>
                <div class="col-sm-10">
                    <select class="form-control" name="variant" id="variant">
                        <option value="">- variant -</option>
                        <option value="Dingin" <?php if ($variant == "Dingin")
                            echo "selected" ?>>Dingin</option>
                            <option value="Hangat" <?php if ($variant == "Hangat")
                            echo "selected" ?>>Hangat</option>
                            <option value="Panas" <?php if ($nama == "Panas")
                            echo "selected" ?>>Panas</option>
                        </select>
                    </div>
                </div>
        <div class="form-group">
            <label>Kuantitas:</label>
            <input type="text" name="kuantitas" class="form-control" placeholder="Masukan Kuantitas" required/>
        </div>
        <div class="form-group">
                <label>Metode Pembayaran:</label>
                <div class="col-sm-10">
                    <select class="form-control" name="metode_pembayaran" id="metode_pembayaran">
                        <option value="">- metode_pembayaran -</option>
                        <option value="Bayar Cash" <?php if ($metode_pembayaran == "Bayar Cash")
                            echo "selected" ?>>Bayar Cash</option>
                            <option value="Transfer via KoPay" <?php if ($metode_pembayaran == "Transfer via KoPay")
                            echo "selected" ?>>Transfer via KoPay</option>
                        </select>
                    </div>
                </div>
                </div>

        <input type="hidden" name="id" value="<?php echo $data['id']; ?>" />

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>