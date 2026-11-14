<?php
session_start();

$nama_pengguna = isset($_SESSION['nama_panjang']) ? $_SESSION['nama_panjang'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Aprilana Queenzy');
$provinsi_pengguna = isset($_SESSION['provinsi']) ? $_SESSION['provinsi'] : 'DKI Jakarta';

if (!isset($_SESSION['riwayat_food_waste'])) {
    $_SESSION['riwayat_food_waste'] = [];
}

$hasil_ai = null;

function panggilGeminiBackendCurl($bahan_input) {
    $api_key = "AQ.Ab8RN6LzWBolEBh-H3Hg9nhxPTzFOdM8NtZHh62q4lIljNSKkw"; 
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=" . trim($api_key);
    $prompt_sistem = "Anda adalah AI Chef Assistant profesional dari aplikasi GiziLokal. Pengguna memasukkan sisa bahan makanan di kulkas mereka: '{$bahan_input}'. Tugas Anda adalah: 1. Berikan rekomendasi menu masakan terbaik untuk mencegah food waste. 2. Berikan penjelasan singkat mengapa menu ini cocok. 3. Berikan langkah-langkah memasak praktisnya. Buat jawabannya dalam format teks yang rapi dan terstruktur dalam Bahasa Indonesia.";
    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt_sistem]
                ]
            ]
        ]
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 45);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        return "Gagal koneksi cURL ke server AI: " . $err;
    }

    $result = json_decode($response, true);
    
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        return $result['candidates'][0]['content']['parts'][0]['text'];
    } else if (isset($result['error']['message'])) {
        $pesan_error = $result['error']['message'];
        if (strpos($pesan_error, 'quota') !== false || strpos($pesan_error, 'Exhausted') !== false) {
            return "⚠️ **Batas Kuota AI Tercapai (Rate Limit)**\n\nServer Google AI membatasi jumlah permintaan gratis dalam waktu singkat. Anda bisa mengetik bahan yang *sudah pernah dicari sebelumnya* (karena langsung dimuat instan dari cache lokal tanpa memotong kuota), atau tunggu sekitar 30 detik lalu coba lagi.";
        }
        return "Error dari Google AI: " . $pesan_error;
    } else {
        return "Gagal mendapatkan respons dari AI. Respons server mentah: " . htmlspecialchars($response);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'hapus_riwayat') {
        $_SESSION['riwayat_food_waste'] = [];
        $_SESSION['flash_message'] = "Riwayat berhasil dibersihkan!";
        $_SESSION['flash_type'] = "success";
        header("Location: cegah_food_waste.php");
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] == 'olah_bahan') {
        $nama_bahan = trim($_POST['nama_bahan']);

        if (!empty($nama_bahan)) {
            // --- FITUR CACHE LOKAL SMART CHECK (HEMAT KUOTA) ---
            $jawaban_ai = null;
            $sumber_data = "";
            
            foreach ($_SESSION['riwayat_food_waste'] as $item_riwayat) {
                if (strcasecmp(trim($item_riwayat['bahan']), $nama_bahan) == 0) {
                    $jawaban_ai = $item_riwayat['jawaban'];
                    $sumber_data = "cache";
                    break;
                }
            }

            if ($jawaban_ai === null) {
                $jawaban_ai = panggilGeminiBackendCurl($nama_bahan);
                $sumber_data = "api";
            }

            $hasil_ai = [
                'id'      => uniqid(),
                'bahan'   => $nama_bahan,
                'jawaban' => $jawaban_ai,
                'waktu'   => date('d M Y, H:i')
            ];

            if ($sumber_data === "api") {
                array_unshift($_SESSION['riwayat_food_waste'], $hasil_ai);
                $_SESSION['flash_message'] = "Resep berhasil diracik oleh Gemini 3.6 Flash!";
            } else {
                $_SESSION['flash_message'] = "Resep dimuat instan dari cache lokal (Hemat Kuota AI)!";
            }
            $_SESSION['flash_type'] = "success";

        } else {
            $_SESSION['flash_message'] = "Harap ketik sisa bahan makanan terlebih dahulu!";
            $_SESSION['flash_type'] = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cegah Sisa Makanan (AI) - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary-green: #059669; --dark-green: #064e3b; --light-green: #ecfdf5; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #334155; line-height: 1.6; }
        .navbar { backdrop-filter: blur(16px); background: rgba(255, 255, 255, 0.95); box-shadow: 0 4px 20px rgba(0,0,0,0.03); padding: 1rem 0; border-bottom: 1px solid #e2e8f0; }
        .header-section { padding: 40px 0 20px; margin-top: 60px; background: linear-gradient(180deg, #ecfdf5 0%, #f8fafc 100%); border-bottom: 1px solid #e2e8f0; }
        .main-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); padding: 24px; margin-bottom: 24px; position: relative; z-index: 2; }
        button[type="submit"] { cursor: pointer; position: relative; z-index: 5; }
        
        #loadingOverlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(4px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    </style>
</head>
<body>

    <div id="loadingOverlay">
        <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5 class="fw-bold text-success mb-1">AI Chef (Gemini 3.6 Flash) sedang meracik resep...</h5>
        <p class="text-muted small">Mohon tunggu sebentar, menghindari batas kuota server.</p>
    </div>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-extrabold fs-3 text-success d-flex align-items-center text-decoration-none" href="index.php">
                <i class="fa-solid fa-seedling me-2 text-success"></i>Gizi<span class="text-dark">Lokal</span>
            </a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Menu</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="header-section text-center">
        <div class="container">
            <span class="badge px-3 py-2 rounded-pill mb-2 fw-bold shadow-sm" style="background-color: #d1fae5; color: var(--dark-green);">
                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Gemini 3.6 Flash Terintegrasi
            </span>
            <h1 class="fw-extrabold text-dark mb-2 fs-3">Tanya AI Resep Sisa Makanan Kulkas</h1>
            <p class="text-muted small">Menggunakan model gemini-3.6-flash dengan sistem cache cerdas lokal.</p>
        </div>
    </header>

    <main class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'success' ?> alert-dismissible fade show py-2 small fw-bold" role="alert">
                        <i class="fa-solid fa-circle-info me-1"></i> <?= $_SESSION['flash_message']; ?>
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
                <?php endif; ?>

                <div class="main-card">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-robot text-success me-2"></i>Ketik Bahan Sisa Kulkas Anda</h5>
                    
                    <form id="formAi" action="cegah_food_waste.php" method="POST">
                        <input type="hidden" name="action" value="olah_bahan">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Bahan makanan (bebas apa saja):</label>
                            <textarea id="inputBahan" name="nama_bahan" class="form-control bg-light border-0 py-2 small" rows="3" placeholder="Contoh: Sisa brokoli, setengah potong dada ayam, dan keju mozarella..." required></textarea>
                        </div>

                        <button type="submit" id="btnSubmit" class="btn btn-success w-100 rounded-pill py-2 fw-bold shadow-sm" style="background-color: var(--primary-green); border: none;">
                            <i class="fa-solid fa-brain me-1"></i> Kirim ke AI Sekarang
                        </button>
                    </form>
                </div>

                <?php if ($hasil_ai): ?>
                <div class="main-card border-success bg-light">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-success p-2 rounded-circle me-2"><i class="fa-solid fa-check text-white"></i></span>
                        <h5 class="fw-bold text-success mb-0">Hasil Resep Gemini 3.6 Flash</h5>
                    </div>
                    
                    <div class="bg-white p-3 rounded-3 border mb-3">
                        <p class="text-muted small mb-1">Bahan yang Anda input:</p>
                        <p class="fw-bold text-dark small mb-0"><?= htmlspecialchars($hasil_ai['bahan']) ?></p>
                    </div>

                    <div class="bg-white p-3 rounded-3 border">
                        <div class="text-dark small" style="white-space: pre-line; line-height: 1.7;"><?= htmlspecialchars($hasil_ai['jawaban']) ?></div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($_SESSION['riwayat_food_waste'])): ?>
                <div class="main-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-history text-muted me-2"></i>Riwayat & Cache Tersimpan</h6>
                        <form action="cegah_food_waste.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh riwayat?');">
                            <input type="hidden" name="action" value="hapus_riwayat">
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 fw-bold" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-trash-can me-1"></i> Hapus Riwayat
                            </button>
                        </form>
                    </div>
                    <div class="d-flex flex-column gap-2" style="max-height: 300px; overflow-y: auto;">
                        <?php foreach ($_SESSION['riwayat_food_waste'] as $riwayat): ?>
                        <div class="p-3 rounded-3 bg-light border" style="font-size: 0.8rem;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-success">Bahan: <?= htmlspecialchars($riwayat['bahan']) ?></span>
                                <span class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($riwayat['waktu']) ?></span>
                            </div>
                            <p class="mb-0 text-muted text-truncate"><?= htmlspecialchars(substr($riwayat['jawaban'], 0, 100)) ?>...</p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <footer class="text-center py-3 text-muted border-top bg-white mt-4" style="font-size: 0.8rem;">
        <p class="mb-0">&copy; 2026 GiziLokal Indonesia. AI Assistant Terintegrasi.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('formAi').addEventListener('submit', function(e) {
            const inputVal = document.getElementById('inputBahan').value.trim();
            if (inputVal !== "") {
                document.getElementById('loadingOverlay').style.display = 'flex';
            }
        });
    </script>
</body>
</html>