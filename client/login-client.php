<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Favicons -->
    <link href="img/logo.png" rel="icon">
    <link href="img/logo.png" rel="apple-touch-icon">
    
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login Admin</title>
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/8a35befa8d.js" crossorigin="anonymous"></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            background: white;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <img src="img/logo.png" alt="Rizal Foto Studio" width="80" class="mb-2">
            <h4 class="fw-bold">Login Admin</h4>
        </div>
        <p class="text-muted text-center">Masukkan email dan nomor handphone pada saat registrasi</p>
        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nomor Handphone</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function validateForm() {
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            if (email.trim() === "" || password.trim() === "") {
                alert("Mohon isi semua kolom");
                return false;
            }
            return true;
        }
    </script>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    if (isset($_SESSION['email']) || isset($_SESSION['uniqid_client'])) {
        header("Location: data-client.php");
        exit();
      }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $conn = new mysqli("localhost", "root", "", "rfstudio");
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }
        
        $query = mysqli_query($conn,"SELECT * FROM data_konfirmasi_cobahampirfinishjuga WHERE email='$email' AND no_telp='$password'
                  UNION 
                  SELECT * FROM data_booking_cobahampirfinishjuga WHERE email='$email' AND no_telp='$password'");
        if (mysqli_num_rows($query) > 0) {
            
            $data = mysqli_fetch_array($query);
            $_SESSION['email'] = $data['email'];
            $_SESSION['uniqid_client'] = $data['uniqid'];
        
       
            echo "<script>alert('Login berhasil!'); window.location='data-client.php';</script>";
        } else {
            echo "<script>alert('Email atau nomor handphone salah!');</script>";
        }
        
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
