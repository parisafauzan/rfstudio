<?php
$koneksi = mysqli_connect("localhost","root","","RF Studio");

if (isset($_GET['nama'])) {
        $nama = $_GET['nama'];
         
        $sql_u = "SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE nama='$nama'";
         
        $res_u = mysqli_query($databaseConnection, $sql_u);
        //Check number of rows returned from database. 
        //If greater than zero means that username is already submitted/saved in mysql database.
        if (mysqli_num_rows($res_u) > 0) {
            $name_error = "This ". $username ." username is already taken";     
            echo " " . $name_error;
        }
    }
    
// if(!empty($_POST["nama"])){
//     $query = "SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE nama='".$_POST["nama"]."'";
//     $result = mysqli_query($koneksi,$query);
//     $count = mysqli_num_rows($result);
//     if($count>0){
//         echo '<span class="text-danger">Tanggal yang dipilih sudah terisi</span>';
//     }else{
//         echo '<span class="text-success">Tanggal yang dipilih tersedia</span>';
//     }
// }
?>