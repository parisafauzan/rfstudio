<?php
// Menghubungkan ke database MySQL
$servername = "localhost"; // Ganti dengan nama server Anda
$username = "root";        // Ganti dengan username MySQL Anda
$password = "";            // Ganti dengan password MySQL Anda
$dbname = "rfstudio";      // Nama database

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk mengambil data gambar
$sql = "SELECT * FROM data_upload_foto_studio_admin";
$result = $conn->query($sql);

// Menyimpan hasil gambar dalam array
// $images = [];
// $uniqid_client =[];
// if ($result->num_rows > 0) {
//     while($row = $result->fetch_assoc()) {
//         $images[] = $row['nama_file'];
//         $uniqid_client[] = $row['uniqid_client'];
//     }
// } else {
//     $images = [];
//     $uniqid_client=[];
// }
// var_dump($uniqid_client);
$conn->close();
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
      width: 100%;
      height: auto;
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
    
  </style>
<!-- Fontawesome -->
<script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
</head>
<body>

  <div class="container mt-5">
    <h2>Pilih Foto untuk Cetak dan Edit</h2>

    <!-- Form Input untuk Nama, Package, dan Jam Photoshoot -->
    <form id="form">
      <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" class="form-control" id="name" required>
      </div>
      <div class="mb-3">
        <label for="package" class="form-label">Paket</label>
        <select class="form-select" id="package" required>
          <option value="package1">Paket 1</option>
          <option value="package2">Paket 2</option>
          <option value="package3">Paket 3</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="time" class="form-label">Jam Photoshoot</label>
        <input type="time" class="form-control" id="time" required>
      </div>

      <!-- Button Pilih Foto untuk Cetak -->
      <div class="mb-3">
        <label class="form-label">Cetak Foto Besar</label>
        <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#largePhotoModal">Pilih Foto untuk Cetak Foto Besar</button>
        <div id="largePhotoPreview" class="preview"></div>
      </div>

      <!-- Button Pilih Foto untuk Cetak Kecil -->
      <div class="mb-3">
        <label class="form-label">Cetak Foto Kecil</label>
        <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#smallPhotoModal">Pilih Foto untuk Cetak Foto Kecil</button>
        <div id="smallPhotoPreview" class="preview"></div>
      </div>

      <!-- Button Pilih Foto untuk Edit -->
      <div class="mb-3">
        <label class="form-label">Photo Edit</label>
        <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#editPhotoModal">Pilih Foto untuk Edit</button>
        <div id="editPhotoPreview" class="preview"></div>
      </div>

      <button type="submit" class="btn btn-outline-dark w-100">Kirim</button>
    </form>
  </div>

  <!-- Modal Cetak Foto Besar -->
  <div class="modal fade" id="largePhotoModal" tabindex="-1" aria-labelledby="largePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="largePhotoModalLabel">Pilih Foto untuk Cetak Foto Besar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class='container'>
          <div class='row'>
           
          <?php
          while ($data = mysqli_fetch_array($result)) {?>

                  <div class='col'>
                  <div class='image-container'>
                    <img src='admin-upload/<?=$data["nama_file"]?>' alt='<?=$data["nama_file"]?>'>
                    <input class='form-check-input' type='checkbox' value='<?=$data["nama_file"]?>' id='<?=$data["nama_file"]?>'>
                    <div class='text-center' style='font-size:11px; margin-top:10px; width:100%;'>
                      <label class='form-label' style='word-wrap:break-word;width:100px;'><?=$data["nama_file"]?></label>
                    </div>
                    <div class='d-flex justify-content-center'>
                      <a href='indexcontoh4-1.php?nama_file=<?=$data["nama_file"]?>' target='_blank' class='btn btn-outline-dark' style='font-size:12px;'>
                      lihat gambar <i class='fa-solid fa-up-right-from-square'></i>
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
          <button type="button" class="btn btn-outline-dark" id="selectLargePhoto">Oke</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Cetak Foto Kecil -->
  <div class="modal fade" id="smallPhotoModal" tabindex="-1" aria-labelledby="smallPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="smallPhotoModalLabel">Pilih Foto untuk Cetak Foto Kecil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php
          foreach ($images as $image) {
            echo "<div class='image-container'>
                    <img src='admin-upload/$image' alt='$image'>
                    <input class='form-check-input' type='checkbox' value='$image' id='$image'>
                  </div>";
          }
          ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-outline-dark" id="selectSmallPhoto">Oke</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Edit Foto -->
  <div class="modal fade" id="editPhotoModal" tabindex="-1" aria-labelledby="editPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPhotoModalLabel">Pilih Foto untuk Edit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php
          foreach ($images as $image) {
            echo "<div class='image-container'>
                    <img src='admin-upload/$image' alt='$image'>
                    <input class='form-check-input' type='checkbox' value='$image' id='$image'>
                  </div>";
          }
          ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-outline-dark" id="selectEditPhoto">Oke</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Menambahkan JS Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Menangani pemilihan foto untuk cetak besar
    document.getElementById("selectLargePhoto").addEventListener("click", function() {
      const selectedImage = document.querySelector("#largePhotoModal input[type='checkbox']:checked");
      if (selectedImage) {
        const previewDiv = document.getElementById("largePhotoPreview");
        previewDiv.innerHTML = `<img src="admin-upload/${selectedImage.value}" alt="${selectedImage.value}"> ${selectedImage.value}`;
      }
      bootstrap.Modal.getInstance(document.getElementById('largePhotoModal')).hide();
    });

    // Menangani pemilihan foto untuk cetak kecil
    document.getElementById("selectSmallPhoto").addEventListener("click", function() {
      const selectedImages = document.querySelectorAll("#smallPhotoModal input[type='checkbox']:checked");
      if (selectedImages.length <= 6) {
        const previewDiv = document.getElementById("smallPhotoPreview");
        previewDiv.innerHTML = "";
        selectedImages.forEach(image => {
          previewDiv.innerHTML += `<img src="admin-upload/${image.value}" alt="${image.value}"> ${image.value}`;
        });
      } else {
        alert("Pilih maksimal 6 foto.");
      }
      bootstrap.Modal.getInstance(document.getElementById('smallPhotoModal')).hide();
    });

    // Menangani pemilihan foto untuk edit
    document.getElementById("selectEditPhoto").addEventListener("click", function() {
      const selectedImages = document.querySelectorAll("#editPhotoModal input[type='checkbox']:checked");
      if (selectedImages.length <= 15) {
        const previewDiv = document.getElementById("editPhotoPreview");
        previewDiv.innerHTML = "";
        selectedImages.forEach(image => {
          previewDiv.innerHTML += `<img src="admin-upload/${image.value}" alt="${image.value}"> ${image.value}`;
        });
      } else {
        alert("Pilih maksimal 15 foto.");
      }
      bootstrap.Modal.getInstance(document.getElementById('editPhotoModal')).hide();
    });
  </script>

</body>
</html>
