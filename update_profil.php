<?php
session_start();

// Cek apakah form dikirim menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari input form dan bersihkan
    $nama_panjang = trim($_POST['nama_panjang']);
    $provinsi     = trim($_POST['provinsi']);
    $umur         = trim($_POST['umur']);
    $berat        = trim($_POST['berat']);
    $tujuan       = trim($_POST['tujuan']);

    // Simpan data baru ke dalam sesi (Session)
    if (!empty($nama_panjang)) {
        $_SESSION['nama_panjang'] = $nama_panjang;
        $_SESSION['user_name']    = $nama_panjang;
    }
    
    if (!empty($provinsi)) {
        $_SESSION['provinsi'] = $provinsi;
    }
    
    if (!empty($umur)) {
        $_SESSION['umur'] = $umur;
    }
    
    if (!empty($berat)) {
        $_SESSION['berat'] = $berat;
    }
    
    if (!empty($tujuan)) {
        $_SESSION['tujuan'] = $tujuan;
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menyimpan Profil - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #334155; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="card p-4 shadow-sm border-0 mx-auto" style="max-width: 400px; border-radius: 16px;">
            <div class="alert alert-success fw-bold py-3 mb-0 shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check fa-lg mb-2 d-block"></i>
                Profil berhasil diupdate!<br>
                <span class="fw-normal small text-muted">Mengalihkan ke beranda...</span>
            </div>
        </div>
    </div>

    <!-- Script redirect otomatis setelah 2 detik (2000 milidetik) -->
    <script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 2000);
    </script>
</body>
</html>
<?php
    exit;
} else {
    header("Location: profil.php");
    exit;
}
?>