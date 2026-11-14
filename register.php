<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
// Jika user sudah login, cegah masuk ke halaman register dan arahkan langsung ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
require_once 'koneksi.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        $message = '<div class="alert alert-danger py-2 small">Email sudah terdaftar!</div>';
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$nama, $email, $password])) {
            // AMBIL ID TERAKHIR YANG BARU SAJA MASUK KE DATABASE
            $user_id = $pdo->lastInsertId();

            // OTOMATIS LOGIN / SET SESSION AGAR LANGSUNG NYAMBUNG KE PROFIL
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $nama;
            $_SESSION['nama_panjang'] = $nama;
            $_SESSION['email'] = $email;

            // LANGSUNG ARAHKAN KE INDEX ATAU PROFIL
            header("Location: index.php");
            exit;
        } else {
            $message = '<div class="alert alert-danger py-2 small">Gagal mendaftar.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome untuk ikon mata -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(-45deg, #d4fc79, #96e6a1, #84fab0, #8fd3f4);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .card {
            border: none !important;
            backdrop-filter: blur(15px);
            transition: transform 0.3s ease;
        }

        .btn-success {
            background: linear-gradient(to right, #28a745, #20c997);
            border: none;
            transition: 0.3s;
        }

        .btn-success:hover {
            filter: brightness(1.1);
            transform: translateY(-2px);
        }

        .form-control {
            border-radius: 10px 0 0 10px;
            padding: 10px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            box-shadow: none;
            border-color: #28a745;
        }

        .input-group-text {
            border-radius: 0 10px 10px 0;
            background: #fff;
            border: 1px solid #ddd;
            border-left: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card d-flex flex-row overflow-hidden shadow-lg" style="width: 800px; border-radius: 25px;">
        
        <!-- Kolom Kiri: Gambar HD -->
        <div class="d-none d-md-block" style="width: 45%; background: url('https://images.unsplash.com/photo-1498837167922-ddd27525d352?q=85&w=1200&auto=format&fit=crop') center/cover;">
            <div class="h-100 d-flex flex-column justify-content-end p-4" style="background: rgba(0,0,0,0.3); color: white;">
                <h2 class="fw-bold">GiziLokal</h2>
                <p>Bergabunglah dengan komunitas hidup sehat dan pantau kebutuhan nutrisi harianmu.</p>
            </div>
        </div>

        <!-- Kolom Kanan: Form Register -->
        <div class="p-5" style="width: 55%; background: rgba(255, 255, 255, 0.95);">
            <h3 class="fw-bold mb-3">Buat Akun Baru</h3>
            
            <?= $message ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-muted">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control rounded-3" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Email</label>
                    <input type="email" name="email" class="form-control rounded-3" placeholder="nama@email.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Kata Sandi</label>
                    <div class="input-group">
                        <input type="password" name="password" id="regPassword" class="form-control" placeholder="Password" required>
                        <span class="input-group-text" onclick="togglePassword('regPassword', 'toggleIcon2')">
                            <i class="fa-solid fa-eye text-muted" id="toggleIcon2"></i>
                        </span>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold py-2 mt-2">Daftar Sekarang</button>
            </form>
            <p class="text-center mt-3 small">Sudah punya akun? <a href="login.php" class="text-success fw-bold text-decoration-none">Masuk di sini</a></p>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId, iconId) {
    const passwordField = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>