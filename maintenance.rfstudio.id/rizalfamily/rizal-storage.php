<?php
session_start();
if (!isset($_SESSION['email_family'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "data_pribadi_rizal");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Handle buat folder
if (isset($_POST['buat_folder'])) {
    $folder_name = trim($_POST['folder_name']);
    if ($folder_name != '') {
        $stmt = $conn->prepare("INSERT INTO data_folder (tipe, nama_folder) VALUES ('folder', ?)");
        $stmt->bind_param("s", $folder_name);
        $stmt->execute();
        $stmt->close();
        header("Location: rizal-storage.php");
        exit();
    }
}

// Handle upload file
if (isset($_POST['upload_file'])) {
    $folder_id = $_POST['folder_id'] ?? null;
    foreach ($_FILES['files']['name'] as $key => $name) {
        $tmp_name = $_FILES['files']['tmp_name'][$key];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($name);
        move_uploaded_file($tmp_name, $target_file);

        $tipe = mime_content_type($target_file);
        $stmt = $conn->prepare("INSERT INTO data_upload (uniqid, nama_file, waktu_upload) VALUES (?, ?, NOW())");
        if (!$stmt) {
            die("Query error: " . $conn->error); // Debugging query error
        }
        $stmt->bind_param("ss", $folder_id, $name);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: rizal-storage.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rizal Drive</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>
  <style>
    body { background: #f1f3f4; font-family: 'Segoe UI', sans-serif; }
    .tile { background: #fff; border-radius: 10px; padding: 20px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.05); cursor: pointer; }
    .tile:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .icon { font-size: 40px; color: #5f6368; }
    .grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 20px; }
    .upload-area { border: 2px dashed #ccc; border-radius: 10px; padding: 30px; text-align: center; }
    #previewArea img, #previewArea video {
  max-width: 120px;
  max-height: 120px;
  border-radius: 8px;
  object-fit: cover;
}

  </style>
</head>
<body>

<div class="container py-4">
  <div class="d-flex justify-content-between mb-3">
    <h4>Rizal Drive</h4>
    <div>
      <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#folderModal">
        <i class="fas fa-folder-plus"></i>
      </button>
      <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="fas fa-upload"></i>
      </button>
    </div>
  </div>

  <div class="grid-container">
    <?php
    $result = $conn->query("SELECT * FROM data_folder ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        echo "<div class='tile' onclick=\"window.location='lihat_folder.php?folder_id={$row['id']}'\">
                <div class='icon'><i class='fas fa-folder'></i></div>
                <div class='mt-2'>{$row['nama_folder']}</div>
              </div>";
    }
    ?>
  </div>

  <?php if (isset($_GET['folder_id'])): ?>
    <hr>
    <h5>Isi Folder</h5>
    <div class="grid-container mt-3">
    <?php
    $folder_id = (int)$_GET['folder_id'];
    $result = $conn->query("SELECT * FROM data_upload WHERE folder_id = $folder_id");
    while ($file = $result->fetch_assoc()) {
        $icon = 'file';
        if (strpos($file['tipe'], 'image/') === 0) $icon = 'file-image';
        else if (strpos($file['tipe'], 'video/') === 0) $icon = 'file-video';

        echo "<div class='tile'>
                <div class='icon'><i class='fas fa-{$icon}'></i></div>
                <div class='mt-2'>{$file['nama_file']}</div>
              </div>";
    }
    ?>
    </div>
  <?php endif; ?>
</div>

<!-- Modal Buat Folder -->
<div class="modal fade" id="folderModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header"><h5>Buat Folder Baru</h5></div>
      <div class="modal-body">
        <input type="text" name="folder_name" class="form-control" placeholder="Nama folder" required>
      </div>
      <div class="modal-footer">
        <button type="submit" name="buat_folder" class="btn btn-primary">Buat</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Upload -->
<div class="modal fade" id="uploadModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header"><h5>Upload File</h5></div>
      <div class="modal-body">
        <div class="upload-area mb-3">Drag & drop file ke sini</div>
        <input type="file" name="files[]" multiple required>
        <input type="hidden" name="folder_id" value="<?php echo $_GET['folder_id'] ?? ''; ?>">

        <!-- Preview Area -->
        <div id="previewArea" class="mt-3 d-flex flex-wrap gap-2"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="upload_file" class="btn btn-success">Upload</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('fileInput').addEventListener('change', function(event) {
    const previewArea = document.getElementById('previewArea');
    previewArea.innerHTML = ''; // Clear previous previews

    Array.from(event.target.files).forEach(file => {
        const fileType = file.type;
        const reader = new FileReader();

        reader.onload = function(e) {
            let element;
            if (fileType.startsWith('image/')) {
                element = document.createElement('img');
                element.src = e.target.result;
            } else if (fileType.startsWith('video/')) {
                element = document.createElement('video');
                element.src = e.target.result;
                element.controls = true;
            } else {
                element = document.createElement('div');
                element.textContent = file.name;
                element.style.padding = '10px';
                element.style.border = '1px solid #ccc';
                element.style.borderRadius = '5px';
            }
            previewArea.appendChild(element);
        };

        reader.readAsDataURL(file);
    });
});
</script>

</body>
</html>
