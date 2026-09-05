<?php
require "../session.php";
// error_reporting(0);
// ini_set('display_errors', 0);
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rfstudio";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
$ambiluniqidclient = $_GET["uniqid_client"];
$ambildataclient =  mysqli_query($conn, "SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE uniqid = '$ambiluniqidclient'");
if (!$ambildataclient) {
    die("Query gagal: " . mysqli_error($conn));
}
$ambildataclientarray = mysqli_fetch_assoc($ambildataclient);
$ambildatauploadfotodariadmin = mysqli_query($conn, "SELECT * FROM data_upload_foto_studio_admin WHERE uniqid_client = '$ambiluniqidclient'") 
    or die("Query gagal: " . mysqli_error($conn));
$ambildataupload = mysqli_query($conn, "SELECT * FROM data_upload_foto_studio_admin WHERE uniqid_client = '$ambiluniqidclient'");
$upload_success = false; // Menandakan apakah upload berhasil atau tidak
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Periksa apakah ada file yang di-upload
    if (!empty($_FILES['files']['name'][0])) {
        $uploadDir = 'admin-upload/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowedExtensions = ['png', 'jpg', 'jpeg', 'mov', 'mp4'];
        $stmt = $conn->prepare("INSERT INTO data_upload_foto_studio_admin (uniqid_client, nama, no_telp, email, nama_file, waktu_upload) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($_FILES['files']['name'] as $key => $name) {
            $fileTmpName = $_FILES['files']['tmp_name'][$key];
            $fileSize = $_FILES['files']['size'][$key];
            $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowedExtensions)) {
                echo "Format file '$name' tidak diperbolehkan.";
                continue;
            }

            if ($fileSize > 50 * 1024 * 1024) { // Maksimum 50MB
                echo "Ukuran file '$name' terlalu besar.";
                continue;
            }

            $uniqid_client = $ambiluniqidclient;
            $safeFileName = basename($name);
            $filePath = $uploadDir . $safeFileName;
            $i=1;
            if (file_exists($filePath)) {
                $safeFileName =  $i."_".$safeFileName;
                $filePath = $uploadDir . $safeFileName;
                $i++;
            }

            if (move_uploaded_file($fileTmpName, $filePath)) {
                $nama = $ambildataclientarray["nama"];
                $no_telp = $ambildataclientarray["no_telp"];
                $email = $ambildataclientarray["email"];
                date_default_timezone_set('Asia/Jakarta');
                $waktu_upload = date("Y-m-d H:i:s");

                $stmt->bind_param("ssssss", $uniqid_client, $nama, $no_telp, $email, $safeFileName, $waktu_upload);
                if ($stmt->execute()) {
                    $upload_success = true; // Menandakan upload berhasil
                } else {
                    echo "Error saat menyimpan ke database.";
                }
            } else {
                echo "Gagal mengunggah file '$name'.";
            }
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload & View Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        #drop-area {
            width: 100%;
            max-width: 800px;
            height: 400px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            transition: background-color 0.3s ease;
            text-align: center;
            margin: 20px auto;
        }
        #drop-area:hover{
            background-color: rgba(111, 111, 111, 0.1);
        }
        #preview {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 15px;
        }
        .preview-card {
            max-width: 150px;
            width: 100%;
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            position: relative;
            transition: background-color 0.3sease;
        }
        .boder{
            background-color: white;
            border-radius: 20px;
            width: 100%;
            max-width: 1000px;
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 20px;
        }
        .container{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .preview-card:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
        .preview-card img, .preview-card video {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .file-name {
            margin-top: 5px;
            font-size: 14px;
            font-weight: bold;
        }
        .remove-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: none;
            border: none;
            color: red;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .remove-btn:hover, .remove-btn:active {
            transform: scale(1.3);
        }
        .upload-div{
            justify-items: center;
        }
        .file-name {
            font-size: 12px; /* Ubah sesuai keinginan */
            font-weight:500;
        }
        .scroll-preview{
            width: 100%;
            height: 500px;
            overflow-y:scroll ;
            background-color: rgba(183, 183, 183, 0.1);
            border-radius: 50px;
            margin: 20px auto;
        }
        .clear-all-div{
            justify-items: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light border-bottom" style="background-color: #fff; padding: 15px;">
        <div class="container d-flex justify-content-center">
            <a href="../index.php" class="text-dark" style="text-decoration:none;">
                <img src="../img/logo.png" class="img-thumbnail rounded-circle" alt="Rizal Photography" width="80">
                <span class="navbar-light h1 ms-4 align-middle fw-bold" style="margin-top: 15px;">Rizal Foto Studio</span>
            </a>
        </div>
    </nav>

    <div class="container mt-5 text-center">
        <?php if (mysqli_num_rows($ambildatauploadfotodariadmin)>0){ ?>
        <div class="boder shadow">
            <div class="d-flex justify-content-between">
                <div class="button-back" style="width: 33%;"><a type="button" class="btn btn-outline-dark" href="javascript:history.go(-1)"><i class="fa-solid fa-angle-left"></i> Back</a></div>
                <h2 style="width: 33%;">Add Photos - <b><?=$ambildataclientarray["nama"]?></b></h2>
                <div class="empty" style="width: 33%;"> </div>
            </div>
                <form id="upload-form" action="" method="POST" enctype="multipart/form-data">
                    <div id="drop-area" class="mt-3">
                        <p>Drag & Drop files here or click to browse</p>
                        <input type="file" id="fileElem" name="files[]" multiple hidden accept=".png,.jpg,.jpeg,.mov,.mp4">
                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('fileElem').click();">
                            Browse Your File
                        </button>
                    </div>
                    <div class="scroll-preview" style="display:none;">
                        <div class="clear-all-div">
                            <button id="clear-all-btn" class="btn btn-danger mt-3" type="button" onclick="clearAll()"><i class="fa-solid fa-trash"></i>
                                Clear All
                            </button>
                        </div>
                        <div id="preview" class="mt-3"></div>
                    </div>
                    <div class="upload-div">
                        <button id="upload-btn" class="btn btn-success mt-3" style="display:none;" type="submit">
                            Upload
                        </button>
                    </div>
                </form>
        </div>
        <?php }else{?>
            <script>
                window.addEventListener('DOMContentLoaded', (event) => {
                const modal = new bootstrap.Modal(document.getElementById('modalNodata'));
                modal.show();
                
                });
            </script>
            <?php } ?>
    </div>
    <!-- Modal untuk menampilkan upload berhasil -->
            <div class="modal fade" id="modalNodata" tabindex="-1" aria-labelledby="uploadModalLabel">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadModalLabel">Data tidak di temukan</h5>
                            
                        </div>
                        <div class="modal-body text-center">
                            Silahkan Hubungi Admin!</br>
                        <a href="https://wa.me/6281288045066" target="_blank" class="fwa" style="text-decoration: none; color:#fff; background-color:#000;padding:8px; border-radius:20px">
                            <i class="fa-brands fa-whatsapp mt-4" style="font-size: 18px;"></i> WhatsApp
                        </a>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <a href="../index.php" class="btn btn-outline-dark">Go to Main Page</a>
                        </div>
                    </div>
                </div>
            </div>
    <!-- Modal untuk menampilkan upload berhasil -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Data Berhasil Diupload!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    File Anda telah berhasil di-upload.
                </div>
                <div class="modal-footer">
                    <a href="new_page.php" class="btn btn-success">Go to Page</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tampilkan modal jika upload berhasil
        if (<?php echo $upload_success ? 'true' : 'false'; ?>) {
            var uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
            uploadModal.show();

        }
    </script>

    <!-- script modal -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".view-image").forEach(item => {
            item.addEventListener("click", function(event) {
                event.preventDefault();
                let imageUrl = this.getAttribute("data-image");
                document.getElementById("modalImage").src = imageUrl;
                let imageModal = new bootstrap.Modal(document.getElementById("imageModal"));
                imageModal.show();
            });
        });
    });
    </script>
    <script>
        let files = []; // Menyimpan file yang diupload

        // document.getElementById('fileElem').addEventListener('change', function(event) {
        //     handleFiles(event.target.files);
        // });
        document.addEventListener("DOMContentLoaded", function() {
            const fileElem = document.getElementById('fileElem');
            
            if (fileElem) {
                fileElem.addEventListener('change', function(event) {
                    handleFiles(event.target.files);
                });
            } else {
                console.error("Element with ID 'fileElem' not found.");
            }
        });

        const dropArea = document.getElementById('drop-area');

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.style.backgroundColor = "rgba(111, 111, 111, 0.2)";
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.style.backgroundColor = "transparent";
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.style.backgroundColor = "transparent";
            handleFiles(e.dataTransfer.files);
        });

        function handleFiles(selectedFiles) {
            // Proses file di sini
            console.log(files);
            const allowedExtensions = ["png", "jpg", "jpeg", "mov", "mp4"];
            let newFiles = Array.from(selectedFiles).filter(file => {
                let fileExt = file.name.split('.').pop().toLowerCase();
                
                if (!allowedExtensions.includes(fileExt)) {
                    alert(`File ${file.name} tidak diperbolehkan.`);
                    return false;
                }

                if (files.some(f => f.name === file.name && f.size === file.size)) {
                    alert(`File ${file.name} sudah ada di daftar.`);
                    return false;
                }

                return true;
            });

            files = files.concat(newFiles);
            document.getElementById('fileElem').files = fileListFromArray(files);
            displayPreview();
        }

        function fileListFromArray(fileArray) {
            let dataTransfer = new DataTransfer();
            fileArray.forEach(file => dataTransfer.items.add(file));
            return dataTransfer.files;
        }

        function displayPreview() {
            let preview = document.getElementById('preview');
            let scrollPreview = document.querySelector('.scroll-preview');
            preview.innerHTML = '';

            files.forEach((file, index) => {
                let card = document.createElement('div');
                card.classList.add('card', 'preview-card');

                let removeBtn = document.createElement('button');
                removeBtn.classList.add('remove-btn');
                removeBtn.innerHTML = '<i class="fa-solid fa-times-circle"></i>';
                removeBtn.onclick = (event) => {
                    event.preventDefault();
                    files.splice(index, 1);
                    document.getElementById('fileElem').files = fileListFromArray(files);
                    displayPreview();
                };

                let mediaElement;
                if (file.type.startsWith('image/')) {
                    mediaElement = document.createElement('img');
                } else if (file.type.startsWith('video/')) {
                    mediaElement = document.createElement('video');
                    mediaElement.controls = true;
                }
                mediaElement.src = URL.createObjectURL(file);

                let fileName = document.createElement('div');
                fileName.classList.add('file-name');
                fileName.textContent = file.name;

                card.appendChild(removeBtn);
                card.appendChild(mediaElement);
                card.appendChild(fileName);
                preview.appendChild(card);
            });

            scrollPreview.style.display = files.length ? 'block' : 'none';
            document.getElementById('upload-btn').style.display = files.length > 0 ? 'block' : 'none';
        }

        function clearAll() {
            files = [];
            document.getElementById('fileElem').value = "";
            displayPreview();
        }


    </script>
</body>
</html>
