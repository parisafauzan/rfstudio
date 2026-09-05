<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();
// Cek apakah user sudah login
if (!isset($_SESSION['email']) || !isset($_SESSION['uniqid_client'])) {
  header("Location: login-client.php");
  exit();
}
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// Menghubungkan ke database MySQL
$servername = "localhost"; // Ganti dengan nama server Anda
$username = "root";        // Ganti dengan username MySQL Anda
$password = "";            // Ganti dengan password MySQL Anda
$dbname = "rfstudio";      // Nama database

// Buat koneksi
$koneksi = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

$uniqid_client = $_SESSION['uniqid_client'];
// Query untuk mengambil data gambar
$result = mysqli_query($koneksi,"SELECT * FROM data_upload_foto_studio_admin WHERE uniqid_client = '$uniqid_client'");
$display1 = mysqli_fetch_array($result);

$ambildata = mysqli_query($koneksi,"SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE uniqid ='$uniqid_client' UNION ALL SELECT * FROM data_booking_cobahampirfinishjuga WHERE uniqid ='$uniqid_client'");
$hasilarray1 = mysqli_fetch_array($ambildata);

// var_dump($uniqid_client);

// Fitur download ZIP
    if (isset($_POST['downloadZip'])) {
        // Buat file ZIP
        $zip = new ZipArchive();
        $zipFilename = 'images.zip';

        // Cek jika file ZIP dapat dibuat
        if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            die("Tidak bisa membuat file ZIP.");
        }

        // Ambil data gambar dari database
        $ambilfotocetakdariadmin = mysqli_query($koneksi,"SELECT * FROM data_upload_foto_studio_admin WHERE uniqid_client = '$uniqid_client'");
        while ($cetak = mysqli_fetch_assoc($ambilfotocetakdariadmin)) {
            $imagePath = '../admin-upload/' . $cetak["nama_file"];
            if (file_exists($imagePath)) {
                $zip->addFile($imagePath, $cetak["nama_file"]);
            }
        }

        // Menutup file ZIP
        $zip->close();

        // Mengirim file ZIP untuk diunduh
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
        header('Content-Length: ' . filesize($zipFilename));

        // Membaca file ZIP dan mengirimkan ke browser
        readfile($zipFilename);

        // Menghapus file ZIP setelah dikirim
        unlink($zipFilename);

        // Menghentikan eksekusi lebih lanjut
        exit;
    }

    //1. Submit Bestdeal
      if(isset($_POST["sub-bestdeal"])){
        //var_dump($_POST);
        // $uniqid_client = $_SESSION['uniqid_client']; // Ambil uniqid dari sesi pengguna
        $nama = $_POST['nama']; // Ambil nama pengguna dari sesi
        $selectedPhotos = $_POST['selected_photos'] ?? []; // Ambil foto yang dipilih

        // Pastikan selected_photos ada dan bukan string kosong
        $selectedPhotos = isset($_POST['selected_photos']) && !empty($_POST['selected_photos']) ? json_decode($_POST['selected_photos'], true) : [];
        
        // Pastikan hasil json_decode adalah array
        if (!is_array($selectedPhotos)) {
            $selectedPhotos = [];
        }
        
        if (count($selectedPhotos) > 15) {
            echo "<script>alert('Maksimal 15 foto dapat dipilih!'); window.history.back();</script>";
            exit;
        }

        $koneksi = new mysqli($servername, $username, $password, $dbname);
        if ($koneksi->connect_error) {
            die("Koneksi gagal: " . $koneksi->connect_error);
        }

        $stmt = $koneksi->prepare("INSERT INTO data_kode_foto_dari_client (uniqid_client, nama, nama_file, waktu_input) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $uniqid_client, $nama, $nama_file);

        foreach ($selectedPhotos as $file) {
            $nama_file = basename($file);
            $stmt->execute();
        }
        
        $stmt->close();
        $koneksi->close();
        
        echo "<script>alert('Foto berhasil dikirim!'); window.location.href='data-client.php?uniqid=".$uniqid_client."';</script>";
      }
    //2. Submit Special-diamond package
      if(isset($_POST["sub-special"])){}
    //3. Submit Prawedding
      if(isset($_POST["sub-prawedd"])){}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Website Pilih Foto untuk Cetak dan Edit</title>
  
  <!-- Menambahkan link ke CSS Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .image-container {
      border:groove;
      border-radius: 10px;
      position: relative;
      display: inline-block;
      width: 100%;
      max-width: 150px;
      max-height: fit-content;
      margin: 5px;
      padding: 5px;

    }

    .form-check-input {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 1;
      border-color: #636363;
    }

    .image-container img {
      /* width: 100%;
      height: auto; */
      border-radius: 8px;
      width: 100%;
      height: 100%;
      max-width: 140px;
      max-height: 140px;
    }

    .preview {
      display: flex;
      gap: 10px;
      margin-top: 10px;
      width: 100%;
      border-radius: 20px;
      border: 3px dashed #d3d3d3;
    }

    .preview img {
      width: 100px;
      height: 100px;
      object-fit: cover;
    }
    .modal-body{
      overflow-y: scroll;
      width: 100%;
      height: 100%;
      max-height: 600px;
    }
    .main-container{
      width: 100%;
      max-width: 800px;
      padding: 20px;
      border-radius: 20px;
    }
    .teks-flex{
      width: 100%;
      text-align: center;
      background-color:rgb(255, 255, 255);
      color: white;
      border: none;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .teks-flex:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      background-color:rgb(23, 23, 23);
    }
    .teks-flex1.active{
      transform: scale(1.02);
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      background-color:rgb(23, 23, 23);
      color: #fff; 
    }
    .teks-flex2.active{
      transform: scale(1.02);
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      background-color:rgb(23, 23, 23);
      color: #fff; 
    }
    .btn-Foto{
      width: 100%;
      color: #000;
    }
    .btn-Foto.active{
      width: 100%;
      color: #fff;
      border-color:transparent;
    }
    .btn-Pilih{
      width: 100%;
      color: #000;
    }
    .btn-Pilih.active{
      width: 100%;
      color: #fff;
      border-color:transparent;
    }
    .scroll-preview{
      width: 100%;
      height: 500px;
      overflow-y:scroll ;
      background-color: rgba(183, 183, 183, 0.1);
      border-radius: 10px;
      margin: 20px auto;
      padding-top: 20px;
    }
    .card-show-photo{
      width: 145px;
      /* max-width: 200px; */
      background-color: #fff;
      padding: 5px;
      /* position: relative; */
      border-radius: 12px;
      
    }
    .teks-judul{
      font-size: 24px;
      font-weight: bold;
    }
    .teks-isi{
      font-size: 13px;
    }
    .wrap-teks-1{
      justify-items: center;
      text-align: center;
    }
    .preview-item {
      display: inline-block;
      margin: 5px;
      position: relative;
    }
    .fa-circle-xmark{
      opacity: 0.2;
      position: absolute;
      font-size: 16px;
      top: 2px;
      right: 5px;
    }
    .fa-circle-xmark:hover{
      opacity: 1;
      transition: opacity 0.3s ease-in-out;
    }
    button,.form-check-input{
      font-size: 20px;
    }
    .fa-photo-film{
      opacity: 0.2;
    }
  
  </style>
<!-- Fontawesome -->
<script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
</head>
<body>
  <!-- Ini adalah awal navbar header -->
  <link rel="stylesheet" href="../style.css">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
      <nav class="navbar navbar-light border-bottom" style="background-color: #fff; padding: 15px; ">
          <div class="container d-flex justify-content-center" >
            <a href="../index.php" class="text-dark" style="text-decoration:none;">
              <img src="../img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
              <span class="navbar-light h1 ms-4 align-middle fw-bold" style=" margin-top: 15px; ">Rizal Foto Studio</span>
            </a>
          </div>
      </nav>
    <!-- Ini adalah akhir navbar header -->

  <div class="container justify-content-center">
    <div class="d-flex justify-content-center">
    <a href="logout-client.php" type="button" class="btn btn-dark mt-3" onclick="return confirm('Apakah anda yakin ingin keluar?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
    <div class="main-container mx-auto card mt-3 shadow mb-5">
      <h2 class="text-center">Pilih Foto untuk Cetak dan Edit</h2>
      <span>Hai <b><?=$hasilarray1["nama"];?></b>, segera download semua foto kamu yaa </span>
      <span>File foto disini hanya aktif selama 2 minggu, setelah lewat dari 2 minggu file akan terhapus</span>
      <span>Jadi jangan sampai lupa yaa <i class="fa-solid fa-face-smile-wink"></i></span>
      <div class="d-flex justify-content-around border shadow-sm mt-3 mb-4">
        <div class="teks-flex teks-flex1 active"><a class="btn btn-Foto active" id="btnFoto">Foto Kamu</a></div>
        <div class="teks-flex teks-flex2"><a class="btn btn-Pilih" id="btnPilih">Pilih Foto</a></div>
      </div>
      

      <!-- Menampilkan foto-foto client -->
      <div class="display-1">
        <form class="text-center mb-2" action="" method="POST">
          <button type="submit" name="downloadZip" class="btn btn-outline-dark"><i class="fa-solid fa-cloud-arrow-down"></i> Download All</button>
        </form>
        <?php 
        //
        $ambildataclient = mysqli_query($koneksi,"SELECT * FROM data_upload_foto_studio_admin WHERE uniqid_client = '$uniqid_client'");
        if ($display1["uniqid_client"]>0){
        ?>
        <div class="card shadow p-2">
          <div class="container-foto row justify-content-center scroll-preview">
            <div class="row row-show-photo justify-content-center">
            <?php 
            //
            $allData = [];
            while ($data = mysqli_fetch_assoc($ambildataclient)){
              $allData[] = $data;
            ?>
            <!-- <div class="col-md-4 mb-3"> -->
              <div class="col" style="max-width: 150px;padding:5px;">
                <div class="card card-show-photo shadow-sm">
                    <div class="text-center" style="position: relative;">
                        <div class="dropdown m-2" style="position:absolute; top:0;right:15px;">
                            <button class="btn btn-dark btn-sm" style="position: absolute;height:26px;border-radius:10px" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item view-image" href="#" data-image="../admin/admin-upload/<?= $data['nama_file'] ?>">Lihat Foto</a></li>
                                <li><a class="dropdown-item" href="../admin/admin-upload/<?= $data['nama_file'] ?>" download>Download</a></li>
                                <li><a class="dropdown-item text-danger" href="delete-foto-upload.php?id=<?= $data['id'] ?>&uniqid_client=<?= $data['uniqid_client'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')">Delete</a></li>
                            </ul>
                        </div>
                        <img src="../admin/admin-upload/<?= $data['nama_file'] ?>" class="img-fluid rounded" alt="Photo">
                        <p class="m-2" style="font-size: 13px;"><b><?= $data['nama_file'] ?></b></p>
                    </div>
                </div>
              </div>
            <!-- </div> -->
            <?php }
          
        }else{ ?>
              <p class="teks-judul">Foto belum tersedia</b></p>
            <?php }?>
              
            </div>
          </div>
        </div>
      </div>
      <!-- Form Input untuk Nama, Package, dan Jam Photoshoot -->
      <div class="display-2" style="display: none;">
        <?php
        //var_dump($hasilarray1);
        if ($hasilarray1["tipe_package"]=="best deal"){?>
          <!-- Best Deal -->
          <form class="best-deal" id="form-best-deal" method="POST">
            <div class="wrap-teks-1">
                <p class="teks-judul">Package Kamu</p>
                <p style="font-size: 20px;font-weight:bold;">
                    <?php echo $hasilarray1["package"] . "<span style='font-weight:normal'> (" . $hasilarray1["tipe_package"] . ") </span>"; ?>
                </p>
                <div class="preview row shadow mb-3">
                  <?php 
                  $datakodefoto = mysqli_query($koneksi,"SELECT * FROM data_kode_foto_dari_client WHERE uniqid_client = '$uniqid_client'");
                  $arraykodefoto = mysqli_fetch_array($datakodefoto);
                  if($arraykodefoto != 0){
                  ?>
                    <div style="height:fit-content">
                        <button type="button" class="btn btn-outline-primary" style="width: 200px;" data-bs-toggle="modal" data-bs-target="#editPhotoBestDealModal">Pilih Foto untuk Edit</button>
                    </div>
                    <span class="teks-isi">Pilih Foto Edit - max. 15 Photo</span>
                    <i class="fa-solid fa-photo-film photo-film-bestdeal mb-3"></i>
                    <div id="editPhotoBestDealPreview" class="col"></div>
                    <?php } else {?>
                      <div class="">empty</div>
                      <?php } ?>
                </div>
                <input type="hidden" name="nama" id="nama" value="<?= $hasilarray1["nama"]?>">
                <input type="hidden" name="uniqid_client" id="uniqid_client" value="<?= $uniqid_client?>">
                <input type="hidden" name="selected_photos" id="selectedPhotosInput">
                <label class="teks-judul">Peraturan</label>
                <p class="teks-isi">Karena Package kamu adalah <b>best deal</b></p>
                <p class="teks-isi">Maka, kamu hanya bisa memilih <b>Max. 15 Photo</b> (tanpa cetak) yang nanti-nya akan di edit oleh Tim Kami selama ...hari</p>
                <button type="submit" name="sub-bestdeal" class="btn btn-outline-dark w-100">Kirim</button>
            </div>
          </form>
          
        <?php }else if($hasilarray1["tipe_package"]=="special package"||$hasilarray1["tipe_package"]=="diamond package"){?>
          <!-- Special Package -->
          <form class="special-package" id="form-special-package">
            <div class="wrap-teks-1">
              <p class="teks-judul">Package Kamu</p>
              <p style="font-size: 20px;font-weight:bold;"><?php echo $hasilarray1["package"]."<span style='font-weight:normal'> (".$hasilarray1["tipe_package"].") </span>"; ?></p>
              
              <div class="preview row shadow mb-3">
                <div style="height:fit-content">
                  <button type="button" class="btn btn-outline-primary" style="width: 200px;" data-bs-toggle="modal" data-bs-target="#canvas17RSpecialModal">Pilih Foto</button>
                </div>
                <span class="teks-isi">Pilih Foto Canvas 17R - 1 Photo</span>
                <i class="fa-solid fa-photo-film photo-film-17RSpecial mb-3"></i>
                <div id="preview17Rspecial" class="col"></div>
              </div>
              <div class="preview row shadow mb-3">
                <div style="height:fit-content">
                  <button type="button" class="btn btn-outline-primary" style="width: 200px;" data-bs-toggle="modal" data-bs-target="#foto5RSpecialModal">Pilih Foto</button>
                </div>
                <span class="teks-isi">Pilih Foto 5R - 5 Photo</span>
                <i class="fa-solid fa-photo-film photo-film-5RSpecial mb-3"></i>
                <div id="5RspecialPreview" class="col"></div>
              </div>
              <div class="preview row shadow mb-3">
                <div style="height:fit-content">
                  <button type="button" class="btn btn-outline-primary" style="width: 200px;" data-bs-toggle="modal" data-bs-target="#editPhotoSpecialModal">Pilih Foto</button>
                </div>
                <span class="teks-isi">Pilih Foto edit only - 14 Photo</span>
                <i class="fa-solid fa-photo-film photo-film-editspecial mb-3"></i>
                <div id="editSpecialPhotoPreview" class="col"></div>
              </div>
              <label class="teks-judul">Peraturan</label>
              <p class="teks-isi">Karena Package kamu adalah <b>best deal</b></p>
              <p class="teks-isi">Maka, kamu hanya bisa memilih <b>Max. 15 Photo</b> (tanpa cetak) yang nanti-nya akan di edit oleh Tim Kami selama ...hari</p>
              <button type="submit" name="sub-special" class="btn btn-outline-dark w-100">Kirim</button>
            </div>
          </form>
        <?php }else if($hasilarray1["tipe_package"]=="prawedding"){ ?>
          <!-- Prawedding Package -->
          <form class="special-package" id="form-special-package">
            <div class="wrap-teks-1">
              <p class="teks-judul">Package Kamu</p>
              <p style="font-size: 20px;font-weight:bold;"><?php echo $hasilarray1["package"]."<span style='font-weight:normal'> (".$hasilarray1["tipe_package"].") </span>"; ?></p>
              
              <div class="preview row shadow mb-3">
                <div style="height:fit-content">
                  <button type="button" class="btn btn-outline-primary" style="width: 200px;" data-bs-toggle="modal" data-bs-target="#canvas17RModal">Pilih Foto</button>
                </div>
                <span class="teks-isi">Pilih Foto Canvas 17R - 2 Photo</span>
                <i class="fa-solid fa-photo-film photo-film-17R mb-3"></i>
                <div id="preview17R" class="col"></div>
              </div>
              <div class="preview row shadow mb-3">
                <div style="height:fit-content">
                  <button type="button" class="btn btn-outline-primary" style="width: 200px;" data-bs-toggle="modal" data-bs-target="#editPhotoSpecialModal">Pilih Foto</button>
                </div>
                <span class="teks-isi">Pilih Foto edit only - 18 Photo</span>
                <i class="fa-solid fa-photo-film photo-film-editspecial mb-3"></i>
                <div id="editSpecialPhotoPreview" class="col"></div>
              </div>
              <label class="teks-judul">Peraturan</label>
              <p class="teks-isi">Karena Package kamu adalah <b>best deal</b></p>
              <p class="teks-isi">Maka, kamu hanya bisa memilih <b>Max. 15 Photo</b> (tanpa cetak) yang nanti-nya akan di edit oleh Tim Kami selama ...hari</p>
              <button type="submit" name="sub-prawedd" class="btn btn-outline-dark w-100">Kirim</button>           
            </div>
            
          </form>
        <?php }else{ ?>
          <div class="wrap-teks-1"><span class="teks-judul">Belum tersedia</span></div>
        <?php } ?>
        
      </div>

    </div>
  </div>
  <!-- Ini adalah awal footer  -->
  <footer class="bg-dark text-white pt-5 pb-4">
      <div class="container-md text-start text-md-left mx-auto"> 
        <div class="row text-start text-md-left"> 
          
          <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
             <h5 class="text-uppercase mb-4 fw-bold text-warning text-start">RF Studio</h5>
            <p class="span" style="font-size: 16px;" > Sebuah foto dapat menggambarkan kita kenangan terhadap momen tersebut, segera abadikan momen-mu di Rizal Foto <h class="fw-bold">Studio</h> </p>
           
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 fw-bold text-warning">Social media</h5>
              <p >
                <a href="https://www.instagram.com/rizalstudio.id" class="fjadwal text-white" style="text-decoration:none; " ><i class="fa-brands fa-instagram me-2"></i> rizalstudio.id</a>
                </p>
                <p >
                <a href="https://www.instagram.com/rizalphotography" class="fjadwal text-white" style="text-decoration:none; " ><i class="fa-brands fa-instagram me-2"></i> rizalphotography</a>
                </p>
               
              </p>
                
            </div>

            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 fw-bold text-warning">On Going</h5>
              <p >
                <a href="jadwal.php" class="fjadwal text-white" style="text-decoration:none; " ><i class="fas fa-calendar-days me-2"></i> Jadwal</a>
                </p>
               
               <p>
                <a href="trackingprogress.php" class="fpelunasan text-white" style="text-decoration:none;" ><i class="fa-solid fa-rotate me-2"></i> Tracking Progress</a>
              </p>
                
              </div>

            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
              <h5 class="text-uppercase mb-4 fw-bold text-warning"> Contact Us</h5>
              <p>
                <!-- <a href="https://g.page/Rizalstudio?share" class="fmaps" style="text-decoration: none; color:#fff;"> -->
                <a class="" href="" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="text-decoration: none; color:#fff;">
                  <i class="fas fa-location-dot me-2"></i>RF Studio - Maps
                </a>
                <div class="collapse" id="collapseExample">
                    <a href="https://g.page/Rizalstudio?share" target="_blank" class="fmaps" style="text-decoration: none; color:#fff;"><i class="fa-solid fa-map-location-dot mb-3 ms-3 me-2"></i> 
                      Bekasi Kabupaten
                    </a><br>
                    <a href="https://goo.gl/maps/knP7cQPUXhfDEti2A" target="_blank" class="fmaps" style="text-decoration: none; color:#fff;"><i class="fa-solid fa-map-location-dot ms-3 me-2"></i> 
                      Bekasi Kota
                    </a>      
                </div>
              </p>
              <p>
                <a href="https://wa.me/6281288045066" class="fwa" style="text-decoration: none; color:#fff;">
                    <i class="fa-brands fa-whatsapp mt-2 me-2"></i>0812-8804-5066
                </a>
              </p>
              <p>
                <a href="https://mail.google.com/" class="femail" style="text-decoration: none; color:#fff;">
              <i class="fas fa-envelope  mt-2 me-2"></i>rizalphotography98@gmail.com
              </a>
              </p>
              <p>
                <a type="button" class="fadmin" data-bs-toggle="modal" data-bs-target="#adminModal" style="text-decoration: none; color:#fff;">
                <i class="fas fa-lock  mt-2 me-2"></i> Masuk Admin </a>
              </p>
            </div>
            
            </div>
            <hr class="mb-4">
            <div class="row align-items-center">
              <div class="col-md-6 col-lg-6 ">
              <p class="text-start">Copyright &copy;<span id="copyright-year"></span>
		          All rights reserved by :
            <a href="#" style="text-decoration: none;">
                <strong class="text-warning"> Rizal Foto Studio</strong>
            </a>
            </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </footer>
    <script>
        // Menampilkan tahun saat ini
        document.getElementById('copyright-year').textContent = new Date().getFullYear();
    </script>

    <!-- Ini adalah akhir footer  -->
  <!-- Best deal Edit Foto Only -->
    <div class="modal fade" id="editPhotoBestDealModal" tabindex="-1" aria-labelledby="editPhotoBestDealModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h5 class="modal-title" id="editPhotoBestDealModalLabel">Pilih Foto untuk Edit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <!-- Modal Body -->
          <div class="modal-body">
            <div class="container">
              <div class="row">
                <?php
                foreach ($result as $data) { ?>
                  <div class="col">
                    <div class="image-container">
                      <img src="../admin/admin-upload/<?= htmlspecialchars($data["nama_file"]) ?>" alt="<?= htmlspecialchars($data["nama_file"]) ?>">
                      <input class="form-check-input" type="checkbox" name="selected_file[]" value="<?= htmlspecialchars($data["nama_file"]) ?>" id="<?= htmlspecialchars($data["nama_file"]) ?>">
                      <div class="text-center" style="font-size: 11px; margin-top: 5px;width:100%;">
                        <label class="form-label" style='word-wrap:break-word;width:100px;'><?= htmlspecialchars($data["nama_file"]) ?></label>
                      </div>
                      <div class="d-flex justify-content-center">
                        <a href="../indexcontoh4-1.php?nama_file=<?= urlencode($data["nama_file"]) ?>" target="_blank" class="btn btn-outline-dark btn-sm">
                          Lihat Foto <i class="fa-solid fa-up-right-from-square"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-outline-dark" id="selectEditPhotoBestDeal">Oke</button>
          </div>
        </div>
      </div>
    </div>
  <!-- Kanvas 17R -->
    <div class="modal fade" id="canvas17RSpecialModal" tabindex="-1" aria-labelledby="canvas17RSpecialModalLabel" >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="canvas17RSpecialModalLabel">Pilih Foto untuk Cetak Foto 17R</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class='container'>
              <div class='row'> 
                <?php
                foreach ($result as $data) {?>

                    <div class='col'>
                    <div class='image-container'>
                      <img src='../admin/admin-upload/<?=$data["nama_file"]?>' alt='<?=$data["nama_file"]?>'>
                      <input class="form-check-input" type="checkbox" name="selected_file[]" value="<?= htmlspecialchars($data["nama_file"]) ?>" id="<?= htmlspecialchars($data["nama_file"]) ?>">
                      <div class='text-center' style='font-size:11px; margin-top:10px; width:100%;'>
                        <label class='form-label' style='word-wrap:break-word;width:100px;'><?=$data["nama_file"]?></label>
                      </div>
                      <div class='d-flex justify-content-center'>
                        <a href='../indexcontoh4-1.php?nama_file=<?=$data["nama_file"]?>' target='_blank' class='btn btn-outline-dark' style='font-size:12px;'>
                        Lihat Foto <i class='fa-solid fa-up-right-from-square'></i>
                        </a>
                      </div>
                    </div>
                    </div>
                <?php
                }
                ?>
            
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-outline-dark" id="select17R">Oke</button>
          </div>
        </div>
      </div>
    </div>
  <!-- Foto 5R -->
    <div class="modal fade" id="foto5RSpecialModal" tabindex="-1" aria-labelledby="foto5RSpecialModalLabel" >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="foto5RSpecialModalLabel">Pilih Foto untuk Cetak Foto 5R</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class='container'>
              <div class='row'> 
                <?php
                foreach ($result as $data) {?>

                    <div class='col'>
                    <div class='image-container'>
                      <img src='../admin/admin-upload/<?=$data["nama_file"]?>' alt='<?=$data["nama_file"]?>'>
                      <input class="form-check-input" type="checkbox" name="selected_file[]" value="<?= htmlspecialchars($data["nama_file"]) ?>" id="<?= htmlspecialchars($data["nama_file"]) ?>">
                      <div class='text-center' style='font-size:11px; margin-top:10px; width:100%;'>
                        <label class='form-label' style='word-wrap:break-word;width:100px;'><?=$data["nama_file"]?></label>
                      </div>
                      <div class='d-flex justify-content-center'>
                        <a href='../indexcontoh4-1.php?nama_file=<?=$data["nama_file"]?>' target='_blank' class='btn btn-outline-dark' style='font-size:12px;'>
                        Lihat Foto <i class='fa-solid fa-up-right-from-square'></i>
                        </a>
                      </div>
                    </div>
                    </div>
                <?php
                }
                ?>
            
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-outline-dark" id="select5R">Oke</button>
          </div>
        </div>
      </div>
    </div>
  <!-- Modal Edit Foto -->
    <div class="modal fade" id="editPhotoSpecialModal" tabindex="-1" aria-labelledby="editPhotoSpecialModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h5 class="modal-title" id="editPhotoSpecialModalLabel">Pilih Foto untuk Edit</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <!-- Modal Body -->
          <div class="modal-body">
            <div class="container">
              <div class="row">
                <?php
                foreach ($result as $data) { ?>
                  <div class="col">
                    <div class="image-container">
                      <img src="../admin/admin-upload/<?= htmlspecialchars($data["nama_file"]) ?>" alt="<?= htmlspecialchars($data["nama_file"]) ?>">
                      <input class="form-check-input" type="checkbox" name="selected_file[]" value="<?= htmlspecialchars($data["nama_file"]) ?>" id="<?= htmlspecialchars($data["nama_file"]) ?>">
                      <div class="text-center" style="font-size: 11px; margin-top: 5px;width:100%;">
                        <label class="form-label" style='word-wrap:break-word;width:100px;'><?= htmlspecialchars($data["nama_file"]) ?></label>
                      </div>
                      <div class="d-flex justify-content-center">
                        <a href="../indexcontoh4-1.php?nama_file=<?= urlencode($data["nama_file"]) ?>" target="_blank" class="btn btn-outline-dark btn-sm">
                          Lihat Foto <i class="fa-solid fa-up-right-from-square"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <!-- Modal Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-outline-dark" id="selectEditPhotoSpecial">Oke</button>
          </div>
        </div>
      </div>
    </div>

  <!-- Script JS dan Jquery -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    //btn pilih halaman fotokamu-pilihfoto
    $('.teks-flex1').on('click', function() {
      $(this).addClass("active");
      $(".btn-Foto").addClass("active");
      $(".teks-flex2").removeClass("active");
      $(".btn-Pilih").removeClass("active");
      $(".display-1").show();
      $(".display-2").hide();
    });
    $('.teks-flex2').on('click', function() {
      $(this).addClass("active");
      $(".btn-Pilih").addClass("active");
      $(".teks-flex1").removeClass("active");
      $(".btn-Foto").removeClass("active");
      $(".display-2").show();
      $(".display-1").hide();
    });

    // 
    $(".photo-film-17RSpecial").show();
    $(".photo-film-5RSpecial").show();
    $(".photo-film-editspecial").show();
    $(".photo-film-bestdeal").show();

    // 


    // Menangani pemilihan foto untuk best-deal
    // document.getElementById("selectEditPhotoBestDeal").addEventListener("click", function() {
    //   const selectedImages = document.querySelectorAll("#editPhotoBestDealModal input[type='checkbox']:checked");
    //   const previewDiv = document.getElementById("editPhotoBestDealPreview");
    //   previewDiv.innerHTML = "";

    //   if (selectedImages.length <= 15) {
    //     selectedImages.forEach((image, index) => {
    //       const fileName = image.value;
    //       const wrapper = document.createElement("div");
    //       wrapper.classList.add("preview-item");
    //       wrapper.innerHTML = `
    //         <div class="position-relative d-inline-block m-2 text-center">
    //           <img src="../admin/admin-upload/${fileName}" alt="${fileName}" 
    //             style="width:100px; height:100px; object-fit:cover; border-radius:8px;margin-bottom:10px;">
    //           <button type="button" class="btn btn-sm position-absolute top-0 end-0 delete-preview" data-filename="${fileName}" title="Hapus"><i class="fa-solid fa-circle-xmark"></i></button>
    //           <div style="font-size:12px; width:100px; word-wrap:break-word;">${fileName}</div>
    //         </div>
    //       `;
    //       $(".photo-film-bestdeal").hide();
    //       previewDiv.appendChild(wrapper);
    //     });
    //   } else {
    //     alert("Pilih maksimal 15 foto.");
    //     return;
    //   }

    //   bootstrap.Modal.getInstance(document.getElementById('editPhotoBestDealModal')).hide();

    //   // Tambahkan event listener untuk tombol hapus
    //   document.querySelectorAll(".delete-preview").forEach(btn => {
    //     btn.addEventListener("click", function () {
    //       const filenameToDelete = this.getAttribute("data-filename");
    //       // Hapus dari preview
    //       const previewItem = this.closest(".preview-item");
    //       if (previewItem) previewItem.remove();
    //       $(".photo-film-bestdeal").show();
    //       // Uncheck checkbox di modal
    //       const checkbox = document.querySelector(`#editPhotoBestDealModal input[type='checkbox'][value="${filenameToDelete}"]`);
    //       if (checkbox) checkbox.checked = false;
    //     });
    //   });
    // });
    document.getElementById("selectEditPhotoBestDeal").addEventListener("click", function() {
    const selectedImages = document.querySelectorAll("#editPhotoBestDealModal input[type='checkbox']:checked");
    const previewDiv = document.getElementById("editPhotoBestDealPreview");
    const selectedPhotosInput = document.getElementById("selectedPhotosInput");
    previewDiv.innerHTML = "";

    let selectedFiles = [];
    if (selectedImages.length <= 15) {
        selectedImages.forEach((image) => {
            const fileName = image.value;
            selectedFiles.push(fileName);
            const wrapper = document.createElement("div");
            wrapper.classList.add("preview-item");
            wrapper.innerHTML = `
                <div class="position-relative d-inline-block m-2 text-center">
                    <img src="../admin/admin-upload/${fileName}" alt="${fileName}" 
                        style="width:100px; height:100px; object-fit:cover; border-radius:8px;margin-bottom:10px;">
                    <button type="button" class="btn btn-sm position-absolute top-0 end-0 delete-preview" data-filename="${fileName}" title="Hapus"><i class="fa-solid fa-circle-xmark"></i></button>
                    <div style="font-size:12px; width:100px; word-wrap:break-word;">${fileName}</div>
                </div>
            `;
            previewDiv.appendChild(wrapper);
        });
        $(".photo-film-bestdeal").hide();
    } else {
        alert("Pilih maksimal 15 foto.");
        return;
    }

    selectedPhotosInput.value = JSON.stringify(selectedFiles);
    bootstrap.Modal.getInstance(document.getElementById('editPhotoBestDealModal')).hide();

    document.querySelectorAll(".delete-preview").forEach(btn => {
        btn.addEventListener("click", function () {
            const filenameToDelete = this.getAttribute("data-filename");
            const previewItem = this.closest(".preview-item");
            if (previewItem) previewItem.remove();
            selectedFiles = selectedFiles.filter(file => file !== filenameToDelete);
            selectedPhotosInput.value = JSON.stringify(selectedFiles);
            const checkbox = document.querySelector(`#editPhotoBestDealModal input[type='checkbox'][value="${filenameToDelete}"]`);
            if (checkbox) checkbox.checked = false;
            
            if (selectedFiles.length === 0) {
                $(".photo-film-bestdeal").show();
            }
        });
    });
});
  
    // Menangani pemilihan foto untuk special-package dan diamond package
    // 1. Canvas 17R
    document.getElementById("select17R").addEventListener("click", function() {
      const selectedImages = document.querySelectorAll("#canvas17RSpecialModal input[type='checkbox']:checked");
      const previewDiv = document.getElementById("preview17Rspecial");
      previewDiv.innerHTML = "";

      if (selectedImages.length <=1) {
        selectedImages.forEach((image, index) => {
          const fileName = image.value;
          const wrapper = document.createElement("div");
          wrapper.classList.add("preview-item");
          wrapper.innerHTML = `
            <div class="position-relative d-inline-block m-2 text-center">
              <img src="../admin/admin-upload/${fileName}" alt="${fileName}" 
                style="width:100px; height:100px; object-fit:cover; border-radius:8px;margin-bottom:10px;">
              <button type="button" class="btn btn-sm position-absolute top-0 end-0 delete-preview" data-filename="${fileName}" title="Hapus"><i class="fa-solid fa-circle-xmark"></i></button>
              <div style="font-size:12px; width:100px; word-wrap:break-word;">${fileName}</div>
            </div>
          `;
          
          previewDiv.appendChild(wrapper);
          $(".photo-film-17RSpecial").hide();
        });
      } else {
        alert("Pilih 1 Foto.");
        return;
      }

      bootstrap.Modal.getInstance(document.getElementById('canvas17RSpecialModal')).hide();

      // Tambahkan event listener untuk tombol hapus
      document.querySelectorAll(".delete-preview").forEach(btn => {
        btn.addEventListener("click", function () {
          const filenameToDelete = this.getAttribute("data-filename");
          // Hapus dari preview
          const previewItem = this.closest(".preview-item");
          if (previewItem) previewItem.remove();
          $(".photo-film-17RSpecial").show();

          // Uncheck checkbox di modal
          const checkbox = document.querySelector(`#canvas17RSpecialModal input[type='checkbox'][value="${filenameToDelete}"]`);
          if (checkbox) checkbox.checked = false;
        });
      });
    });
    // 2. Foto 5R
    document.getElementById("select5R").addEventListener("click", function() {
      const selectedImages = document.querySelectorAll("#foto5RSpecialModal input[type='checkbox']:checked");
      const previewDiv = document.getElementById("5RspecialPreview");
      previewDiv.innerHTML = "";

      if (selectedImages.length <=5) {
        selectedImages.forEach((image, index) => {
          const fileName = image.value;
          const wrapper = document.createElement("div");
          wrapper.classList.add("preview-item");
          wrapper.innerHTML = `
            <div class="position-relative d-inline-block m-2 text-center">
              <img src="../admin/admin-upload/${fileName}" alt="${fileName}" 
                style="width:100px; height:100px; object-fit:cover; border-radius:8px;margin-bottom:10px;">
              <button type="button" class="btn btn-sm position-absolute top-0 end-0 delete-preview" data-filename="${fileName}" title="Hapus"><i class="fa-solid fa-circle-xmark"></i></button>
              <div style="font-size:12px; width:100px; word-wrap:break-word;">${fileName}</div>
            </div>
          `;
          
          previewDiv.appendChild(wrapper);
          $(".photo-film-5RSpecial").hide();
        });
      } else {
        alert("Pilih 5 Foto.");
        return;
      }

      bootstrap.Modal.getInstance(document.getElementById('foto5RSpecialModal')).hide();

      // Tambahkan event listener untuk tombol hapus
      document.querySelectorAll(".delete-preview").forEach(btn => {
        btn.addEventListener("click", function () {
          const filenameToDelete = this.getAttribute("data-filename");
          // Hapus dari preview
          const previewItem = this.closest(".preview-item");
          if (previewItem) previewItem.remove();
          $(".photo-film-5RSpecial").show();

          // Uncheck checkbox di modal
          const checkbox = document.querySelector(`#foto5RSpecialModal input[type='checkbox'][value="${filenameToDelete}"]`);
          if (checkbox) checkbox.checked = false;
        });
      });
    });
    // 3. Edit Only
    document.getElementById("selectEditPhotoSpecial").addEventListener("click", function() {
      const selectedImages = document.querySelectorAll("#editPhotoSpecialModal input[type='checkbox']:checked");
      const previewDiv = document.getElementById("editSpecialPhotoPreview");
      previewDiv.innerHTML = "";

      if (selectedImages.length <= 14) {
        selectedImages.forEach((image, index) => {
          const fileName = image.value;
          const wrapper = document.createElement("div");
          wrapper.classList.add("preview-item");
          wrapper.innerHTML = `
            <div class="position-relative d-inline-block m-2 text-center">
              <img src="../admin/admin-upload/${fileName}" alt="${fileName}" 
                style="width:100px; height:100px; object-fit:cover; border-radius:8px;margin-bottom:10px;">
              <button type="button" class="btn btn-sm position-absolute top-0 end-0 delete-preview" data-filename="${fileName}" title="Hapus"><i class="fa-solid fa-circle-xmark"></i></button>
              <div style="font-size:12px; width:100px; word-wrap:break-word;">${fileName}</div>
            </div>
          `;
          previewDiv.appendChild(wrapper);
          $(".photo-film-editspecial").hide();
        });
      } else {
        alert("Pilih maksimal 14 foto.");
        return;
      }

      bootstrap.Modal.getInstance(document.getElementById('editPhotoSpecialModal')).hide();

      // Tambahkan event listener untuk tombol hapus
      document.querySelectorAll(".delete-preview").forEach(btn => {
        btn.addEventListener("click", function () {
          const filenameToDelete = this.getAttribute("data-filename");
          // Hapus dari preview
          const previewItem = this.closest(".preview-item");
          if (previewItem) previewItem.remove();
          $(".photo-film-editspecial").show();

          // Uncheck checkbox di modal
          const checkbox = document.querySelector(`#editPhotoSpecialModal input[type='checkbox'][value="${filenameToDelete}"]`);
          if (checkbox) checkbox.checked = false;
        });
      });
    });

    //Menangani pemilihan foto untuk Prawedding
    //1. Canvas 17 R

    //2. Edit Only
  </script>

</body>
</html>
