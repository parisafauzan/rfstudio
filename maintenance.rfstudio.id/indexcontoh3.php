<?php
require "function.php";

// Cek koneksi
if ($koneksi->connect_error) {
  die("Connection failed: " . $koneksi->connect_error);
}

// Query untuk mengambil data gambar
$sql = mysqli_query($koneksi,"SELECT * FROM data_upload_foto_studio_admin");
$result = (mysqli_fetch_array($sql));

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
// var_dump($result);


    // Menangani upload file dan penyimpanan ke database
    // if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files'])) {
    //     $files = $_FILES['files'];
    //     $fileCount = count($files['name']); // Jumlah file yang diupload
    //     $uniqid_client = uniqid(); // Membuat uniqid untuk client

    //     $nama = $_POST['nama'];
    //     $no_telp = $_POST['no_telp'];
    //     $email = $_POST['email'];

    //     // Untuk setiap file yang diupload
    //     for ($i = 0; $i < $fileCount; $i++) {
    //         $fileTmpName = $files['tmp_name'][$i];
    //         $fileName = $files['name'][$i];
    //         $fileSize = $files['size'][$i];
    //         $fileError = $files['error'][$i];

    //         if ($fileError === 0) {
    //             $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    //             $fileNewName = uniqid('', true) . '.' . $fileExt; // Membuat nama unik untuk file

    //             $fileDestination = 'admin-upload/' . $fileNewName;

    //             // Pindahkan file ke folder 'uploads'
    //             if (move_uploaded_file($fileTmpName, $fileDestination)) {
    //                 // Menyimpan informasi file ke database
    //                 $sql = "INSERT INTO data_upload_foto_studio_admin (uniqid_client, nama, no_telp, email, nama_file,waktu_upload) 
    //                         VALUES ('$uniqid_client', '$nama', '$no_telp', '$email', '$fileNewName', NOW())";
    //                 if ($koneksi->query($sql) === TRUE) {
    //                     echo "File $fileName berhasil diupload dan disimpan di database.<br>";
    //                 } else {
    //                     echo "Error: " . $sql . "<br>" . $koneksi->error;
    //                 }
    //             } else {
    //                 echo "Gagal mengupload file $fileName.<br>";
    //             }
    //         } else {
    //             echo "Error saat upload file $fileName.<br>";
    //         }
    //     }
    // }
    // Fitur download ZIP
    // if (isset($_POST['downloadZip'])) {
    //     // Buat file ZIP
    //     $zip = new ZipArchive();
    //     $zipFilename = 'images.zip';

    //     // Cek jika file ZIP dapat dibuat
    //     if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    //         die("Tidak bisa membuat file ZIP.");
    //     }

    //     // Ambil data gambar dari database
    //     $ambilfotocetakdariadmin = mysqli_query($koneksi,"SELECT * FROM data_upload_foto_studio_admin;");
    //     while ($cetak = mysqli_fetch_assoc($ambilfotocetakdariadmin)) {
    //         $imagePath = 'admin-upload/' . $cetak["nama_file"];
    //         if (file_exists($imagePath)) {
    //             $zip->addFile($imagePath, $cetak["nama_file"]);
    //         }
    //     }

    //     // Menutup file ZIP
    //     $zip->close();

    //     // Mengirim file ZIP untuk diunduh
    //     header('Content-Type: application/zip');
    //     header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
    //     header('Content-Length: ' . filesize($zipFilename));

    //     // Membaca file ZIP dan mengirimkan ke browser
    //     readfile($zipFilename);

    //     // Menghapus file ZIP setelah dikirim
    //     unlink($zipFilename);

    //     // Menghentikan eksekusi lebih lanjut
    //     exit;
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Favicons -->
    <link href="img/logo.png" rel="icon">
    <link href="img/logo.png" rel="apple-touch-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiple File Upload with Preview</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <style>
        body{
            background-color:#f7f7f7;
        }
        .file-preview {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
            justify-content: center;
        }

        .file-preview .card {
            width: 100%;
            max-width: 150px;
            margin:5px;
        }

        .file-preview img {
            max-width: 140px;
            max-height: 160px;
            margin-right: 10px;
            margin-bottom: 10px;
            /* object-fit: cover; */
        }

        .status-bar {
            /* display: none; */
            margin: auto;
            max-width: 100%;
            height: 8px;
            background-color: #28a745;
            margin-top: 20px;
            transition: opacity 0.5s ease;
            border-radius: 20px;
        }

        .input-container {
            margin-bottom: 20px;
            padding-left: 20px;
            padding-right: 20px;
            margin-top: 20px;
        }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .boder{
            background-color: white;
            border-radius: 20px;
            width: 100%;
            max-width: 1000px;
            margin-top: 20px;
            padding-bottom: 20px ;
            padding-top: 20px;
            
        }
        .form{  
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px; 
            max-width: 100%;
            margin-left: 10px;
            margin-right: 10px;
        }
        /* Tab Navigation */
        .tab-container {
          display: flex;
          background-color:#34495e;
          height: 100%;
          max-height: 50px;
        }

        .tab {
          flex: 1;
          text-align: center;
          padding: 14px;
          color:white;
          font-size: 18px;
          cursor: pointer;
          transition: all 0.3s ease;
          background-color:rgb(21, 21, 21);
          
        }

        .tab:hover {
          background-color: #34495e;
          color:white;
        }

        .active-tab {
          background-color:rgb(87, 87, 87);;
          color: white;
          font-weight: bold;
        } 
        .active-content {
          display: block;
        }
        .content-container {
          display: none;
          padding: 5px;
          transition: all 0.3s ease;
        }
        
        /* modal checkbox gambar */
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
</head>
<body>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
    <nav class="navbar navbar-light border-bottom" style="background-color: #fff; padding: 15px; ">
         <div class="container d-flex justify-content-center" >
          <a href="index.php" class="text-dark" style="text-decoration:none;">
             <img src="img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80"  >
             <span class="navbar-light h1 ms-4 align-middle fw-bold" style=" margin-top: 15px; ">Rizal Foto Studio</span>
          </a>
        </div>
    </nav>
    <?php 
      $ambilfotocetakdariadmin = mysqli_query($koneksi,"SELECT * FROM data_upload_foto_studio_admin;");
      $result = mysqli_fetch_assoc($ambilfotocetakdariadmin);
      ?>
<div class="container mb-5">
    <div class="form-container">
        <div class="boder shadow">
          <form id="home" action="" method="POST" enctype="multipart/form-data" class="form border shadow  " >
            <h4 for="upload" class="fw-bold text-center">Pilih Foto untuk di Edit</h4>
            <label for="here" class="form-label ms-4">Here! your data </label>
                <div class="input-container">
                    <label for="nama" class="form-label">Nama: </label>
                    <input type="text" id="nama" name="nama" class="form-control fw-bold" value="<?=$result["nama"]?>" required><br>

                    <label for="" class="form-label">Package</label>
                    <input type="text" id="" name="" class="form-control" required><br>

                    <label for="" class="form-label">Tanggal Photoshoot:</label>
                    <input type="text" id="" name="" class="form-control" required><br>
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
                </div>

                <!-- Modal untuk Pilih Foto edit Best Deal Package -->
                
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
                        while ($data = (mysqli_fetch_array($sql))) 
                        {?>

                                <div class='col' style="width:100%;max-width:150px;">
                                <div class='image-container'>
                                  <img src='admin-upload/<?=$data["nama_file"]?>' alt='<?=$data["nama_file"]?>'>
                                  <input class='form-check-input' type='checkbox' value='<?=$data["nama_file"]?>' id='<?=$data["nama_file"]?>'>
                                  <div class='text-center' style='font-size:11px; margin-top:10px; width:100%;'>
                                    <label class='form-label' style='word-wrap:break-word;width:100px;'><?=$data["nama_file"]?></label>
                                  </div>
                                  <div class='d-flex justify-content-center'>
                                    <a href='indexcontoh4-1.php?nama_file=<?=$data["nama_file"]?>' target='_blank' class='btn btn-outline-dark' style='font-size:11px;'>
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
                 <!-- Modal Cetak Foto Besar -->
                
                <!-- Modal untuk Pilih Foto edit dan Cetak Special dan Diamond Package -->
                <!-- Modal untuk Pilih Foto edit dan Cetak Prawedding Package -->

              <!-- Status Bar (upload progress) -->
          
          </form>
           <!-- Form Download ZIP -->
           <form action="" method="POST">
            <button type="submit" name="downloadZip" class="btn btn-outline-dark mt-4"><i class="fa-solid fa-cloud-arrow-down"></i> ZIP</button>
           </form>
          <form id="upload" action="" method="POST" enctype="multipart/form-data" class="form border shadow" >
          <div class="tab-container">
            <div class="tab">Pilih Foto</div>
          </div>
            
             
            <div class="">
              <div class="file-preview" id="filePreview">
             <?php
                $hasil = ($ambilfotocetakdariadmin);
                if(mysqli_num_rows($hasil)>0){
                while ($cetak = mysqli_fetch_assoc($hasil)){
              ?>
              <div class="card ">
                <img src="admin-upload/<?=$cetak["nama_file"]?>" class="card-img-top shadow bg-body rounded mx-auto gallery-item" style="width: 100%; 
                    height: 100%; margin:5px;" alt="<?=$cetak["nama_file"]?>">
                <div class="card-body mb-auto">
                  <div class="row">
                    <div class="col">
                    <p class="card-text text-center" style="font-size:12px;"><?=$cetak["nama_file"]?></p>
                    </div>
                  </div>
                </div>
              </div>
                      <?php
                } 
                }else{
                  echo "Data tidak ditemukan";
              }
              ?>
              </div>
              <div class="status-bar" id="statusBar"></div>
              <button type="submit" class="btn btn-outline-dark">Upload Files</button>
            </div>
          </form>
         
        </div>
    </div>
</div>
   

<!-- Bootstrap 5 JS & Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src=" https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src=" https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

<script>
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');
    const statusBar = document.getElementById('statusBar');

    // Menangani dragover untuk efek drag-and-drop
    dropArea.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropArea.classList.add('drag-over');
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('drag-over');
    });

    dropArea.addEventListener('drop', (event) => {
        event.preventDefault();
        dropArea.classList.remove('drag-over');
        const files = event.dataTransfer.files;
        previewFiles(files);
        fileInput.files = files;  // Set file input to the dropped files
    });

    // Trigger file input when drop area is clicked
    dropArea.addEventListener('click', () => {
        fileInput.click();
    });

    // Menangani file input untuk memilih file secara manual
    fileInput.addEventListener('change', (event) => {
        const files = event.target.files;
        previewFiles(files);
    });

    // Menampilkan status bar selama upload
    function showStatusBar() {
        statusBar.style.display = 'block';
        let width = 0;
        let interval = setInterval(() => {
            if (width >= 100) {
                clearInterval(interval);
                setTimeout(() => statusBar.style.display = 'none', 500); // Fade out after completion
            } else {
                width++;
                statusBar.style.width = width + '%';
            }
        }, 10);
    }

    // Menangani pengiriman form (untuk sementara menampilkan progress bar)
    document.querySelector('form').addEventListener('submit', (event) => {
        event.preventDefault();
        showStatusBar();
        // Form submit will occur here after progress bar animation
        setTimeout(() => event.target.submit(), 1000); // Simulate form submission after progress
    });
  </script>
  <script>
    // Menangani pemilihan foto untuk cetak besar
    document.getElementById("selectLargePhoto").addEventListener("click", function() {
      const selectedImage = document.querySelector("#largePhotoModal input[type='checkbox']:checked");
      if (selectedImage) {
        const previewDiv = document.getElementById("largePhotoPreview");
        previewDiv.innerHTML = `
        <div class='image-container'>
          <img src='admin-upload/${selectedImage.value}' alt='${selectedImage.value}' style="width:100%;max-width:140px;height:100%;max-height:140px;">
          <div class='text-center' style='font-size:11px; margin-top:10px; width:100%;'>
            <label class='form-label' style='word-wrap:break-word;width:100px;'>${selectedImage.value}</label>
          </div>
          <div class='d-flex justify-content-center'>
            <a href='indexcontoh4-1.php?nama_file=${selectedImage.value}' target='_blank' class='btn btn-outline-dark' style='font-size:11px;'>
            lihat gambar <i class='fa-solid fa-up-right-from-square'></i>
            </a>
          </div>
        </div>
       `;
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
