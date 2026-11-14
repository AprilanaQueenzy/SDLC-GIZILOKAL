<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: landing.php");
    exit;
}
$username = htmlspecialchars($_SESSION['user_name']);

// Inisialisasi session cache khusus riwayat rekomendasi porsi AI
if (!isset($_SESSION['cache_porsi_ai'])) {
    $_SESSION['cache_porsi_ai'] = [];
}

$ai_result = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate_ai'])) {
    $target = $_POST['target_kelompok'] ?? '';
    $wilayah = $_POST['wilayah'] ?? '';
    $anggaran = $_POST['anggaran'] ?? '';
    $porsi = $_POST['porsi'] ?? '';

    if (empty($target) || empty($wilayah) || empty($anggaran) || empty($porsi)) {
        $error_message = "Mohon lengkapi seluruh data terlebih dahulu (Target Kelompok, Provinsi Wilayah, Anggaran, dan Porsi) sebelum menjalankan analisis Gemini AI!";
    } else {
        $formatted_anggaran = "Rp " . number_format(floatval($anggaran), 0, ',', '.');
        
        // Buat kunci unik (signature) berdasarkan input pengguna untuk pengecekan cache
        $cache_key = md5(strtolower(trim($target) . trim($wilayah) . trim($anggaran) . trim($porsi)));
        
        $teks_dari_ai = "";
        $sumber_data = "";

        // --- 1. CEK APAKAH SUDAH ADA DI CACHE LOKAL (HEMAT KUOTA) ---
        if (isset($_SESSION['cache_porsi_ai'][$cache_key])) {
            $teks_dari_ai = $_SESSION['cache_porsi_ai'][$cache_key];
            $sumber_data = "cache";
        } else {
            // --- 2. JIKA BELUM ADA, PANGGIL GEMINI API ---
            $api_key = "AQ.Ab8RN6Ko53SuPu1a7T-TD94Q-DBvR-ayXqkMc776LBfRB7J1Zg"; 
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=" . trim($api_key);

            $prompt_sistem = "Anda adalah Ahli Gizi profesional dari aplikasi GiziLokal. Pengguna meminta rekomendasi menu pangan lokal dengan detail berikut:
            - Target Kelompok: {$target}
            - Provinsi Wilayah: {$wilayah}
            - Anggaran Dana: {$formatted_anggaran}
            - Jumlah Porsi: {$porsi}
            
            Tugas Anda:
            1. Berikan rekomendasi Menu Pangan Lokal terbaik yang sesuai dengan anggaran dan wilayah tersebut.
            2. Berikan estimasi gizi (kalori & protein) serta kisaran biaya belanja yang ekonomis.
            3. Buat jawabannya terstruktur rapi menggunakan format HTML sederhana (seperti tag <strong>, <ul>, <li>, <p>, dan <div class='p-3 bg-white rounded-3 border border-success border-opacity-25'> untuk catatan gizi) agar langsung pas ditampilkan di dalam card web. Gunakan Bahasa Indonesia yang ramah.";

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
                $error_message = "<div class='alert alert-danger border-0 shadow-sm rounded-4 p-4 text-center my-auto'>Gagal koneksi cURL ke server AI: " . $err . "</div>";
            } else {
                $result = json_decode($response, true);
                
                if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                    $teks_dari_ai = $result['candidates'][0]['content']['parts'][0]['text'];
                    
                    // Simpan ke cache session agar pencarian serupa berikutnya tidak memotong kuota
                    $_SESSION['cache_porsi_ai'][$cache_key] = $teks_dari_ai;
                    $sumber_data = "api";
                } else {
                    $pesan_error = isset($result['error']['message']) ? $result['error']['message'] : 'Batas kuota harian tercapai.';
                    
                    // Ekstraksi sisa waktu tunggu jika ada dalam teks error Google
                    preg_match('/Please retry in ([\d\.]+)s/', $pesan_error, $matches);
                    $detik_tunggu = isset($matches[1]) ? ceil(floatval($matches[1])) : 60;

                    // Tampilan Pesan Limit yang Rapi, Elegan, dan User-Friendly
                    $error_message = "
                    <div class='p-4 rounded-4 bg-warning bg-opacity-10 border border-warning border-opacity-50 text-start shadow-sm my-auto'>
                        <div class='d-flex align-items-center mb-3'>
                            <div class='bg-warning text-dark p-3 rounded-circle me-3 shadow-sm' style='width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;'>
                                <i class='fa-solid fa-hourglass-half fa-spin'></i>
                            </div>
                            <div>
                                <h5 class='fw-bold text-dark mb-1'>Kapasitas AI Sedang Penuh (Rate Limit)</h5>
                                <p class='text-muted small mb-0'>Sistem Google AI membatasi jumlah permintaan gratis dalam waktu singkat.</p>
                            </div>
                        </div>
                        
                        <div class='bg-white p-3 rounded-3 border mb-3 small text-secondary'>
                            <i class='fa-solid fa-circle-info text-warning me-1'></i> 
                            <strong>Tips untuk Anda:</strong> Silakan tunggu sekitar <strong>{$detik_tunggu} detik</strong> lagi, atau gunakan kembali kombinasi data yang <em>sudah pernah dicari sebelumnya</em> (karena akan langsung dimuat secara instan tanpa memotong kuota).
                        </div>

                        <button type='button' class='btn btn-warning w-100 fw-bold text-dark py-2 rounded-pill shadow-sm' onclick='location.reload();'>
                            <i class='fa-solid fa-rotate-right me-1'></i> Muat Ulang Halaman & Coba Lagi
                        </button>
                    </div>";
                }
            }
        }

        // Jika berhasil mendapatkan teks, susun variabel $ai_result tanpa tombol duplikat di dalamnya
        if (!empty($teks_dari_ai)) {
            $badge_info = ($sumber_data === "cache") 
                ? "<span class='badge bg-secondary ms-2' style='font-size: 0.7rem;'>Dimuat dari Cache Lokal (Hemat Kuota)</span>" 
                : "<span class='badge bg-success ms-2' style='font-size: 0.7rem;'>Powered by Gemini 3.6 Flash</span>";

            $ai_result = "
            <div class='alert alert-success border-0 shadow-sm rounded-4 mb-3'>
                <div class='d-flex align-items-center mb-2'>
                    <i class='fa-solid fa-robot text-success fs-4 me-2'></i>
                    <h6 class='fw-bold mb-0 text-success'>Rekomendasi AI GiziLokal {$badge_info}</h6>
                </div>
                <p class='small mb-0 text-muted'>Target: <strong>{$target}</strong> | Wilayah: <strong>{$wilayah}</strong> | Anggaran: <strong>{$formatted_anggaran}</strong> ({$porsi})</p>
            </div>
            
            <div class='text-start small text-dark' id='aiContentText'>
                {$teks_dari_ai}
            </div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GiziLokal</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bs-success: #198754;
            --bs-success-rgb: 25, 135, 84;
            --primary-gradient: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
            --card-hover-shadow: 0 20px 40px rgba(4, 120, 87, 0.08);
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            overflow-x: hidden;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
        }

        .hero-section {
            background: var(--primary-gradient);
            color: white;
            border-radius: 0 0 50px 50px;
            padding: 70px 20px 100px 20px;
            box-shadow: 0 25px 50px rgba(4, 120, 87, 0.25);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before, .hero-section::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            background: rgba(255, 255, 255, 0.07);
        }

        .hero-section::before {
            top: -120px;
            right: -120px;
            width: 400px;
            height: 400px;
        }

        .hero-section::after {
            bottom: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 18px 22px;
            color: white;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.18);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-feature {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .card-feature::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card-feature:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-hover-shadow);
            border-color: rgba(16, 185, 129, 0.3);
        }

        .card-feature:hover::after {
            opacity: 1;
        }

        .icon-box {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .card-feature:hover .icon-box {
            transform: scale(1.08) rotate(5deg);
        }

        .badge-category {
            cursor: pointer;
            transition: all 0.25s ease;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
        }

        .badge-category:hover, .badge-category.active {
            background-color: #059669 !important;
            color: white !important;
            border-color: #059669;
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.3);
            transform: translateY(-2px);
        }

        .badge-category:hover span.text-muted, 
        .badge-category.active span.text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .form-control, .form-select {
            padding: 14px 18px;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.12);
            border-color: #059669;
        }

        .btn-success-custom {
            background: linear-gradient(135deg, #059669, #047857);
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-weight: 700;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(5, 150, 105, 0.3);
        }

        .btn-success-custom:hover {
            background: linear-gradient(135deg, #047857, #065f46);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(5, 150, 105, 0.4);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
    <div class="container">
        <a class="navbar-brand fw-extrabold fs-4 d-flex align-items-center" href="#">
            <div class="bg-success text-white rounded-4 p-2 d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 42px; height: 42px;">
                <i class="fa-solid fa-seedling fs-5"></i>
            </div>
            <span class="fw-bold tracking-wide text-dark">Gizi<span class="text-success">Lokal</span></span>
        </a>
        <div class="d-flex align-items-center">
            <div class="text-end me-3 d-none d-sm-block">
                <span class="d-block small text-muted">Selamat Datang,</span>
                <span class="fw-bold text-dark"><?= $username ?></span>
            </div>
            <!-- Tombol Akun untuk Biodata -->
            <a href="profil.php" class="btn btn-light btn-sm fw-bold px-3 py-2 rounded-pill text-success border border-success border-opacity-10 shadow-sm me-2">
                <i class="fa-solid fa-user me-1"></i> Akun
            </a>
            <a href="logout.php" class="btn btn-light btn-sm fw-bold px-3 py-2 rounded-pill text-danger border border-danger border-opacity-10 shadow-sm hover-danger">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Keluar
            </a>
        </div>
    </div>
</nav>

<div class="hero-section text-center mb-5">
    <div class="container position-relative z-1">
        <div class="d-inline-flex align-items-center bg-white bg-opacity-10 backdrop-blur px-4 py-2 rounded-pill mb-4 border border-white border-opacity-15 shadow-sm">
            <i class="fa-solid fa-shield-heart text-warning me-2"></i>
            <span class="small fw-semibold">Solusi Gizi Keluarga & Pencegahan Stunting Nasional</span>
        </div>
        <h1 class="fw-bold display-5 mb-3 tracking-tight">Sehat Berkualitas, Dompet Aman, Pangan Lokal Berjaya</h1>
        <p class="lead opacity-85 mb-5 mx-auto" style="max-width: 680px; font-size: 1.05rem; line-height: 1.6;">
            Platform cerdas berbasis kecerdasan buatan Gemini AI untuk merancang menu gizi harian sesuai kearifan lokal, anggaran, dan standar Kemenkes & WHO.
        </p>

        <div class="row justify-content-center g-3 mt-2">
            <div class="col-md-3 col-6">
                <div class="stat-card d-flex align-items-center text-start">
                    <div class="bg-warning bg-opacity-25 text-warning p-3 rounded-4 me-3">
                        <i class="fa-solid fa-map-pin fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">38 Provinsi</h6>
                        <small class="text-white-50">Cakupan Nasional</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card d-flex align-items-center text-start">
                    <div class="bg-info bg-opacity-25 text-info p-3 rounded-4 me-3">
                        <i class="fa-solid fa-bowl-food fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Berbasis Anggaran</h6>
                        <small class="text-white-50">Fleksibel & Hemat</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card d-flex align-items-center text-start">
                    <div class="bg-danger bg-opacity-25 text-danger p-3 rounded-4 me-3">
                        <i class="fa-solid fa-robot fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">Gemini AI</h6>
                        <small class="text-white-50">Analisis Gizi Pintar</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5" style="margin-top: -60px; position: relative; z-index: 2;">
    
    <div class="row g-4 mb-5">
        
        <!-- Sisi Kiri: Form Input -->
        <div class="col-lg-5">
            <div class="card card-feature p-4 h-100">
                <div class="d-flex align-items-center mb-4">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                    <div class="ms-3">
                        <h4 class="fw-bold mb-1 fs-5">Kalkulator Gizi & AI Gemini</h4>
                        <p class="text-muted small mb-0">Rekomendasi menu instan via AI</p>
                    </div>
                </div>

                <form id="aiForm" method="POST">
                    <input type="hidden" name="target_kelompok" id="inputTargetKelompok" value="Balita">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary mb-2"><i class="fa-solid fa-users me-1 text-success"></i> Target Kelompok:</label>
                        <div class="row g-1 text-center">
                            <div class="col">
                                <div class="badge-category active p-2 rounded-3 d-flex flex-column align-items-center justify-content-center" data-value="Balita" style="height: 100%;">
                                    <img src="https://1.bp.blogspot.com/-k2nSDE2qj2I/VLPrImkKlcI/AAAAAAAAApE/1fayyC22YSM/s1600/foto%2Bbayi%2Bimut%2Bmain%2Bcilup%2Bba.jpg" class="rounded-circle mb-1 shadow-sm" width="30" height="30" alt="Balita">
                                    <span class="fw-bold" style="font-size: 0.65rem;">Balita</span>
                                    <span class="text-muted" style="font-size: 0.5rem;">< 5 Thn</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="badge-category p-2 rounded-3 d-flex flex-column align-items-center justify-content-center" data-value="Anak" style="height: 100%;">
                                    <img src="https://cdn.majalahpama.my/2018/01/anak-belajar-mengenal-jenis-kelamin.jpg" class="rounded-circle mb-1 shadow-sm" width="30" height="30" alt="Anak">
                                    <span class="fw-bold" style="font-size: 0.65rem;">Anak</span>
                                    <span class="text-muted" style="font-size: 0.5rem;">5-9 Thn</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="badge-category p-2 rounded-3 d-flex flex-column align-items-center justify-content-center" data-value="Remaja" style="height: 100%;">
                                    <img src="https://desy.blog.uma.ac.id/wp-content/uploads/sites/761/2022/02/Perkembangan-Usia-Remaja.jpg" class="rounded-circle mb-1 shadow-sm" width="30" height="30" alt="Remaja">
                                    <span class="fw-bold" style="font-size: 0.65rem;">Remaja</span>
                                    <span class="text-muted" style="font-size: 0.5rem;">10-18 Thn</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="badge-category p-2 rounded-3 d-flex flex-column align-items-center justify-content-center" data-value="Dewasa" style="height: 100%;">
                                    <img src="https://media.istockphoto.com/id/1355499535/id/foto/gambar-vertikal-seorang-wanita-dewasa-tersenyum-dengan-tangan-disilangkan-melihat-kamera-di.jpg?s=612x612&w=0&k=20&c=u6ectbBmvQB3I_aaRPKbtk4_rYyfkSMABCP9uAV_r2k=" class="rounded-circle mb-1 shadow-sm" width="30" height="30" alt="Dewasa">
                                    <span class="fw-bold" style="font-size: 0.65rem;">Dewasa</span>
                                    <span class="text-muted" style="font-size: 0.5rem;">18-59 Thn</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="badge-category p-2 rounded-3 d-flex flex-column align-items-center justify-content-center" data-value="Lansia" style="height: 100%;">
                                    <img src="https://cdn.medcom.id/images/library/images/gaya/lansia%20sehat2.jpg" class="rounded-circle mb-1 shadow-sm" width="30" height="30" alt="Lansia">
                                    <span class="fw-bold" style="font-size: 0.65rem;">Lansia</span>
                                    <span class="text-muted" style="font-size: 0.5rem;">60+ Thn</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="badge-category p-2 rounded-3 d-flex flex-column align-items-center justify-content-center" data-value="Ibu Hamil" style="height: 100%;">
                                    <img src="https://cdn.pixabay.com/photo/2020/12/24/15/41/woman-5857739_1280.jpg" class="rounded-circle mb-1 shadow-sm" width="30" height="30" alt="Ibu Hamil">
                                    <span class="fw-bold" style="font-size: 0.55rem;">Ibu Hamil</span>
                                    <span class="text-muted" style="font-size: 0.5rem;">Khusus</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary"><i class="fa-solid fa-map-location-dot me-1 text-success"></i> Pilih Wilayah / Provinsi <span class="text-danger">*</span>:</label>
                        <select class="form-select" id="selectWilayah" name="wilayah" required>
                            <option value="" disabled selected>Pilih Provinsi Asal Anda</option>
                            <optgroup label="Sumatera">
                                <option value="Aceh">Aceh</option>
                                <option value="Sumatera Utara">Sumatera Utara</option>
                                <option value="Sumatera Barat">Sumatera Barat</option>
                                <option value="Riau">Riau</option>
                                <option value="Kepulauan Riau">Kepulauan Riau</option>
                                <option value="Jambi">Jambi</option>
                                <option value="Sumatera Selatan">Sumatera Selatan</option>
                                <option value="Kepulauan Bangka Belitung">Kepulauan Bangka Belitung</option>
                                <option value="Bengkulu">Bengkulu</option>
                                <option value="Lampung">Lampung</option>
                            </optgroup>
                            <optgroup label="Jawa & Bali">
                                <option value="Banten">Banten</option>
                                <option value="DKI Jakarta">DKI Jakarta</option>
                                <option value="Jawa Barat">Jawa Barat</option>
                                <option value="Jawa Tengah">Jawa Tengah</option>
                                <option value="DI Yogyakarta">DI Yogyakarta</option>
                                <option value="Jawa Timur">Jawa Timur</option>
                                <option value="Bali">Bali</option>
                            </optgroup>
                            <optgroup label="Nusa Tenggara">
                                <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                                <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                            </optgroup>
                            <optgroup label="Kalimantan">
                                <option value="Kalimantan Barat">Kalimantan Barat</option>
                                <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                                <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                                <option value="Kalimantan Timur">Kalimantan Timur</option>
                                <option value="Kalimantan Utara">Kalimantan Utara</option>
                            </optgroup>
                            <optgroup label="Sulawesi">
                                <option value="Sulawesi Utara">Sulawesi Utara</option>
                                <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                                <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                                <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                                <option value="Sulawesi Barat">Sulawesi Barat</option>
                                <option value="Gorontalo">Gorontalo</option>
                            </optgroup>
                            <optgroup label="Maluku & Papua">
                                <option value="Maluku">Maluku</option>
                                <option value="Maluku Utara">Maluku Utara</option>
                                <option value="Papua">Papua</option>
                                <option value="Papua Barat">Papua Barat</option>
                                <option value="Papua Selatan">Papua Selatan</option>
                                <option value="Papua Tengah">Papua Tengah</option>
                                <option value="Papua Pegunungan">Papua Pegunungan</option>
                                <option value="Papua Barat Daya">Papua Barat Daya</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary"><i class="fa-solid fa-wallet me-1 text-success"></i> Anggaran Dana (Rp) <span class="text-danger">*</span>:</label>
                        <input type="number" id="inputAnggaran" name="anggaran" class="form-control" placeholder="Contoh: 15000" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary"><i class="fa-solid fa-user-group me-1 text-success"></i> Jumlah Porsi <span class="text-danger">*</span>:</label>
                        <select id="selectPorsi" name="porsi" class="form-select" required>
                            <option value="" disabled selected>Pilih Jumlah Porsi</option>
                            <option value="1 Orang">1 Orang (Porsi Individu)</option>
                            <option value="2 Orang">2 Orang</option>
                            <option value="3 - 4 Orang">3 - 4 Orang (Satu Keluarga)</option>
                            <option value="5 Orang atau Lebih">5 Orang atau Lebih</option>
                        </select>
                    </div>

                    <button type="submit" name="calculate_ai" class="btn btn-success-custom w-100 text-white">
                        <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Minta Rekomendasi Gemini AI
                    </button>
                </form>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card card-feature p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold text-dark fs-5 mb-1"><i class="fa-solid fa-bowl-food me-2 text-success"></i>Hasil Analisis AI & Takaran Gizi</h4>
                        <p class="text-muted small mb-0">Rekomendasi otomatis berbasis Gemini AI & kearifan lokal</p>
                    </div>
                        <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" id="btnAiVoice" onclick="playAiVoice()">
                        <i class="fa-solid fa-volume-high me-1"></i> Bacakan Suara
                    </button>
                </div>
                <div id="resultContainer" class="d-flex flex-column justify-content-center h-100">
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 p-4 text-center my-auto">
                            <i class="fa-solid fa-triangle-exclamation fs-3 mb-2 text-danger"></i>
                            <h6 class="fw-bold">Data Belum Lengkap!</h6>
                            <p class="small mb-0"><?= $error_message ?></p>
                        </div>
                    <?php elseif (!empty($ai_result)): ?>
                        <div class="p-4 rounded-4 bg-light border border-success border-opacity-25 my-auto d-flex flex-column justify-content-center" style="min-height: 380px;">
                            <?= $ai_result ?>
                        </div>
                    <?php else: ?>
                        <div class="p-5 rounded-4 bg-light border border-2 border-dashed text-center my-auto d-flex flex-column align-items-center justify-content-center" style="min-height: 380px;">
                            <div class="bg-success bg-opacity-10 text-success p-4 rounded-circle mb-3 shadow-sm" style="width: 75px; height: 75px; display: flex; align-items: center; justify-content: center; font-size: 30px;">
                                <i class="fa-solid fa-robot"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Belum Ada Analisis AI</h5>
                            <p class="text-muted small mb-0" style="max-width: 360px; line-height: 1.5;">
                                Silakan isi lengkap data kelompok, provinsi wilayah, anggaran dana, dan jumlah porsi terlebih dahulu, lalu klik <strong>"Minta Rekomendasi Gemini AI"</strong>.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bold text-dark fs-4 mb-0">Fitur Utama GiziLokal</h3>
        <span class="text-muted small fw-semibold">Standar Kemenkes & WHO</span>
    </div>

    <div class="row g-4">
        <!-- 1. Hitung Gizi Harian -->
        <div class="col-md-4">
            <div class="card card-feature p-4 h-100 border-0 shadow-sm rounded-4">
                <div class="icon-box bg-primary bg-opacity-10 text-primary mb-3 p-3 rounded-3 d-inline-block">
                    <i class="fa-solid fa-calculator fs-4"></i>
                </div>
                <h5 class="fw-bold mb-2">Hitung Gizi Harian</h5>
                <p class="text-muted small mb-3">Kalkulator praktis keluarga untuk memantau kecukupan kalori dan zat gizi harian secara akurat.</p>
                <a href="kalkulator_gizi.php" class="text-success fw-bold text-decoration-none mt-auto small d-flex align-items-center">
                    Buka Kalkulator <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- 2. AI Konsultasi Kesehatan -->
        <div class="col-md-4">
            <div class="card card-feature p-4 h-100 border-0 shadow-sm rounded-4">
                <div class="icon-box bg-danger bg-opacity-10 text-danger mb-3 p-3 rounded-3 d-inline-block">
                    <i class="fa-solid fa-robot fs-4"></i>
                </div>
                <h5 class="fw-bold mb-2">AI Konsultasi Kesehatan</h5>
                <p class="text-muted small mb-3">Konsultasi masalah kesehatan ringan, rekomendasi makanan pemulihan, dan takaran istirahat ideal.</p>
                <a href="ai_konsultasi.php" class="text-success fw-bold text-decoration-none mt-auto small d-flex align-items-center">
                    Mulai Konsultasi <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- 3. Katalog Resep & Nutrisi -->
        <div class="col-md-4">
            <div class="card card-feature p-4 h-100 border-0 shadow-sm rounded-4">
                <div class="icon-box bg-success bg-opacity-10 text-success mb-3 p-3 rounded-3 d-inline-block">
                    <i class="fa-solid fa-book-bookmark fs-4"></i>
                </div>
                <h5 class="fw-bold mb-2">Katalog Resep & Nutrisi</h5>
                <p class="text-muted small mb-3">Daftar resep pangan lokal lengkap dengan penjelasan ilmiah mengapa bahan tersebut kaya protein.</p>
                <a href="resep_nutrisi.php" class="text-success fw-bold text-decoration-none mt-auto small d-flex align-items-center">
                    Jelajahi Resep <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- 4. Jurnal Asupan Harian Real-time -->
        <div class="col-md-4">
            <div class="card card-feature p-4 h-100 border-0 shadow-sm rounded-4">
                <div class="icon-box bg-info bg-opacity-10 text-info mb-3 p-3 rounded-3 d-inline-block">
                    <i class="fa-solid fa-chart-line fs-4"></i>
                </div>
                <h5 class="fw-bold mb-2">Jurnal Asupan Harian</h5>
                <p class="text-muted small mb-3">Pantau dan bandingkan grafik konsumsi kalori secara real-time dari hari ke hari dengan mudah.</p>
                <a href="jurnal-asupan.php" class="text-success fw-bold text-decoration-none mt-auto small d-flex align-items-center">
                    Buka Jurnal <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- 5. Foto Hitung Kalori Makanan -->
        <div class="col-md-4">
            <div class="card card-feature p-4 h-100 border-0 shadow-sm rounded-4">
                <div class="icon-box bg-purple bg-opacity-10 mb-3 p-3 rounded-3 d-inline-block" style="color: #6f42c1;">
                    <i class="fa-solid fa-camera fs-4"></i>
                </div>
                <h5 class="fw-bold mb-2">Scan Kalori Makanan</h5>
                <p class="text-muted small mb-3">Cukup foto hidangan Anda, biarkan AI mendeteksi jenis makanan dan menghitung total kalorinya.</p>
                <a href="scan_kalori.php" class="text-success fw-bold text-decoration-none mt-auto small d-flex align-items-center">
                    Foto Makanan <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- 6. Cemilan Sehat Tradisional Indonesia -->
        <div class="col-md-4">
            <div class="card card-feature p-4 h-100 border-0 shadow-sm rounded-4">
                <div class="icon-box bg-orange bg-opacity-10 mb-3 p-3 rounded-3 d-inline-block" style="color: #fd7e14;">
                    <i class="fa-solid fa-cookie-bite fs-4"></i>
                </div>
                <h5 class="fw-bold mb-2">Cemilan Sehat Nusantara</h5>
                <p class="text-muted small mb-3">Koleksi jajanan pasar tradisional Indonesia yang sehat lengkap dengan informasi gambar dan kalori.</p>
                <a href="cemilan_sehat.php" class="text-success fw-bold text-decoration-none mt-auto small d-flex align-items-center">
                    Lihat Cemilan <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- 7. Berbagi Makanan Sehat Antar Pengguna -->
        <div class="col-md-4">
            <div class="card card-feature p-4 h-100 border-0 shadow-sm rounded-4">
                <div class="icon-box bg-teal bg-opacity-10 mb-3 p-3 rounded-3 d-inline-block" style="color: #20c997;">
                    <i class="fa-solid fa-share-nodes fs-4"></i>
                </div>
                <h5 class="fw-bold mb-2">Berbagi Makanan Sehat</h5>
                <p class="text-muted small mb-3">Salurkan kelebihan makanan sehat atau berbagi hidangan bergizi dengan sesama pengguna di sekitar Anda.</p>
                <a href="berbagi_makanan.php" class="text-success fw-bold text-decoration-none mt-auto small d-flex align-items-center">
                    Mulai Berbagi <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- 8. Cegah Food Waste dengan AI (Smart Resep Sisa) -->
        <div class="col-md-4">
            <div class="card card-feature p-4 h-100 border-0 shadow-sm rounded-4">
                <div class="icon-box bg-secondary bg-opacity-10 text-secondary mb-3 p-3 rounded-3 d-inline-block">
                    <i class="fa-solid fa-utensils fs-4"></i>
                </div>
                <h5 class="fw-bold mb-2">Cegah Sisa Makanan (AI)</h5>
                <p class="text-muted small mb-3">Foto bahan atau sisa makanan yang ada di kulkas, AI akan meracik menu lezat baru untuk Anda.</p>
                <a href="cegah_food_waste.php" class="text-success fw-bold text-decoration-none mt-auto small d-flex align-items-center">
                    Olah Sisa Bahan <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <!-- Kosongkan 1 kolom agar grid 3 kolom tetap rapi (8 kartu habis dibagi grid Bootstrap) atau biarkan layout mengisi secara fleksibel -->
        <div class="col-md-4">
            <div class="card card-feature p-4 h-100 border-0 shadow-sm rounded-4 bg-light d-flex align-items-center justify-content-center text-center">
                <div>
                    <i class="fa-solid fa-seedling text-success fs-1 mb-2"></i>
                    <h6 class="fw-bold text-dark">GiziLokal Sehat Selalu</h6>
                    <p class="text-muted small mb-0">Fitur lengkap untuk keluarga.</p>
                </div>
            </div>
        </div>

        <!-- Edukasi Pangan (Paling Bawah - Melebar Full Width) -->
        <div class="col-md-12">
            <div class="card card-feature p-4 bg-gradient border-0 shadow-sm rounded-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <span class="bg-warning bg-opacity-25 text-dark px-3 py-1 rounded-pill small fw-bold me-2 d-inline-flex align-items-center shadow-sm">
                                <i class="fa-solid fa-lightbulb me-1 text-warning"></i> Edukasi Pangan
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">Mitos vs Fakta: Makanan Bergizi Itu Mahal?</h5>
                        </div>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">
                            "Mitos: Ikan salmon dan buah impor adalah satu-satunya sumber gizi terbaik." <br>
                            <strong class="text-success">Fakta: Pangan lokal seperti ikan kembung, daun kelor, dan umbi-umbian memiliki kandungan protein & antioksidan jauh lebih tinggi dengan harga yang sangat terjangkau di pasar sekitar Anda!</strong>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="mitos_fakta.php" class="btn btn-outline-success btn-sm rounded-pill px-4 py-2 fw-bold shadow-sm">
                            Baca Artikel Lainnya <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    const apiKey = "AQ.Ab8RN6LzWBolEBh-H3Hg9nhxPTzFOdM8NtZHh62q4lIljNSKkw"; 
    let currentUtterance = null;

    // Fungsi Utama Text-to-Speech (Membaca hanya teks rekomendasi AI)
    function playAiVoice() {
        if (!('speechSynthesis' in window)) {
            alert("Maaf, browser perangkat Anda tidak mendukung fitur suara.");
            return;
        }

        const btn = document.getElementById('btnAiVoice');
        window.speechSynthesis.cancel();

        if (btn.classList.contains('btn-danger')) {
            btn.innerHTML = '<i class="fa-solid fa-volume-high me-1"></i> Bacakan Suara';
            btn.classList.replace('btn-danger', 'btn-outline-success');
            return;
        }

        const contentDiv = document.getElementById('aiContentText');
        if (!contentDiv) {
            alert("Teks rekomendasi belum tersedia.");
            return;
        }

        let rawText = contentDiv.innerText || "";
        let cleanText = rawText
            .replace(/[#*•_`]/g, '')
            .replace(/[\n\r]+/g, '. ')
            .trim();

        if (!cleanText) {
            alert("Tidak ada teks untuk dibacakan.");
            return;
        }

        currentUtterance = new SpeechSynthesisUtterance(cleanText);
        currentUtterance.lang = 'id-ID'; 
        currentUtterance.rate = 1.0; 

        btn.innerHTML = '<i class="fa-solid fa-stop me-1"></i> Berhentikan Audio';
        btn.classList.replace('btn-outline-success', 'btn-danger');

        currentUtterance.onend = () => {
            btn.innerHTML = '<i class="fa-solid fa-volume-high me-1"></i> Bacakan Suara';
            btn.classList.replace('btn-danger', 'btn-outline-success');
        };

        currentUtterance.onerror = () => {
            btn.innerHTML = '<i class="fa-solid fa-volume-high me-1"></i> Bacakan Suara';
            btn.classList.replace('btn-danger', 'btn-outline-success');
        };

        window.speechSynthesis.speak(currentUtterance);
    }

    // Fungsi Filter Artikel Utama
    function filterArticles() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const items = document.querySelectorAll('.article-item');
        const noResult = document.getElementById('noResult');
        let visibleCount = 0;

        items.forEach(item => {
            const keywords = item.getAttribute('data-keywords').toLowerCase();
            const textContent = item.innerText.toLowerCase();

            if (keywords.includes(input) || textContent.includes(input)) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (visibleCount === 0) {
            noResult.classList.remove('d-none');
        } else {
            noResult.classList.add('d-none');
        }
    }

    // Riwayat chat AI Mitos & Fakta
    let chatHistory = [
        {
            role: "user",
            parts: [{ text: "Bertindaklah sebagai pakar edukasi Mitos & Fakta Gizi untuk platform GiziLokal yang berstandar Kemenkes & WHO. Tugas Anda adalah meluruskan berbagai mitos seputar makanan, pantangan tradisional, atau klaim kesehatan dengan penjelasan ilmiah berupa Fakta yang objektif, ramah, dan mudah dipahami dalam Bahasa Indonesia." }]
        },
        {
            role: "model",
            parts: [{ text: "Baik, saya siap membantu mengklarifikasi berbagai mitos dan fakta seputar gizi, makanan tradisional, serta kesehatan berdasarkan rujukan ilmiah Kemenkes dan WHO." }]
        }
    ];

    // Fungsi AI Nutrition Assistant dengan Real Gemini API & Rate Limit Handler
    async function askNutritionAI() {
        const queryInput = document.getElementById('aiQueryInput');
        const chatBox = document.getElementById('aiChatBox');
        const sendBtn = document.getElementById('aiSendBtn');
        const query = queryInput.value.trim();

        if (query === '') return;

        chatBox.style.display = 'block';
        chatBox.innerHTML += `
            <div class="mb-2 text-end">
                <span class="badge bg-success text-white p-2 rounded-3 text-start d-inline-block shadow-sm" style="max-width: 80%;">
                    <strong>Anda:</strong> ${query}
                </span>
            </div>
        `;
        queryInput.value = '';
        queryInput.disabled = true;
        sendBtn.disabled = true;

        const typingId = 'typing-' + Date.now();
        chatBox.innerHTML += `
            <div class="mb-2 text-start" id="${typingId}">
                <div class="p-3 rounded-3 border bg-white d-inline-block text-secondary small">
                    <div class="typing"><span></span><span></span><span></span></div> Menganalisis fakta ilmiah...
                </div>
            </div>
        `;
        chatBox.scrollTop = chatBox.scrollHeight;

        try {
            chatHistory.push({ role: "user", parts: [{ text: query }] });

            const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=${apiKey}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ contents: chatHistory })
            });

            const data = await response.json();
            document.getElementById(typingId).remove();

            if (data.candidates && data.candidates[0].content) {
                const botReply = data.candidates[0].content.parts[0].text;
                chatHistory.push({ role: "model", parts: [{ text: botReply }] });

                const uniqueAiId = 'ai_resp_' + Date.now();
                chatBox.innerHTML += `
                    <div class="mb-2 text-start">
                        <div class="p-3 rounded-3 border bg-white text-dark d-inline-block shadow-sm w-100" style="border-left: 4px solid #2e7d32 !important;">
                            <p class="mb-2 small" id="${uniqueAiId}"><strong>Verifikasi AI (Kemenkes &bull; WHO):</strong><br>${botReply.replace(/\n/g, '<br>')}</p>
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" onclick="playAiVoice(this)" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Jawaban AI
                            </button>
                        </div>
                    </div>
                `;
            } else {
                let pesanError = "Batas kuota harian AI tercapai.";
                if (data.error && data.error.message) {
                    pesanError = data.error.message;
                }

                let detikTunggu = 60;
                const match = pesanError.match(/Please retry in ([\d\.]+)s/);
                if (match && match[1]) {
                    detikTunggu = Math.ceil(parseFloat(match[1]));
                }

                const errorCard = `
                <div class='p-3 rounded-4 bg-warning bg-opacity-10 border border-warning border-opacity-50 text-start shadow-sm mb-2'>
                    <div class='d-flex align-items-center mb-2'>
                        <div class='bg-warning text-dark p-2 rounded-circle me-2 shadow-sm' style='width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; font-size: 13px;'>
                            <i class='fa-solid fa-hourglass-half fa-spin'></i>
                        </div>
                        <div>
                            <h6 class='fw-bold text-dark mb-0' style='font-size: 0.85rem;'>Kapasitas AI Sedang Penuh (Rate Limit)</h6>
                        </div>
                    </div>
                    <p class='text-muted small mb-2'>Tunggu sekitar <strong>${detikTunggu} detik</strong> atau muat ulang halaman.</p>
                    <button type='button' class='btn btn-warning btn-sm w-100 fw-bold text-dark py-1 rounded-pill' onclick='location.reload();'>
                        <i class='fa-solid fa-rotate-right me-1'></i> Muat Ulang Halaman
                    </button>
                </div>`;
                chatBox.innerHTML += errorCard;
            }
        } catch (error) {
            document.getElementById(typingId).remove();
            chatBox.innerHTML += `<div class="alert alert-danger small py-2">Gagal terhubung ke server AI.</div>`;
        } finally {
            queryInput.disabled = false;
            sendBtn.disabled = false;
            queryInput.focus();
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    // Interaksi pemilihan kelompok umur (DIPASTIKAN ADA AGAR BISA DIPENCET)
    const badges = document.querySelectorAll('.badge-category');
    const inputTarget = document.getElementById('inputTargetKelompok');

    if (badges && inputTarget) {
        badges.forEach(badge => {
            badge.addEventListener('click', () => {
                badges.forEach(b => b.classList.remove('active'));
                badge.classList.add('active');
                inputTarget.value = badge.getAttribute('data-value');
            });
        });
    }

    // Skrip untuk mencegah halaman melompat/scroll ke atas saat submit form
    const aiForm = document.getElementById('aiForm');
    if (aiForm) {
        aiForm.addEventListener('submit', function(e) {
            const wilayah = document.getElementById('selectWilayah');
            const anggaran = document.getElementById('inputAnggaran');
            const porsi = document.getElementById('selectPorsi');

            if (wilayah && anggaran && porsi && (!wilayah.value || !anggaran.value || !porsi.value)) {
                return; 
            }

            e.preventDefault();
            
            const resultContainer = document.getElementById('resultContainer');
            if (resultContainer) {
                resultContainer.innerHTML = `
                    <div class="p-5 rounded-4 bg-light border border-2 border-dashed text-center my-auto d-flex flex-column align-items-center justify-content-center" style="min-height: 380px;">
                        <div class="spinner-border text-success mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="fw-bold text-dark">Gemini AI Sedang Menganalisis...</h5>
                        <p class="text-muted small mb-0" style="max-width: 360px;">
                            Mohon tunggu sebentar, AI sedang meracik rekomendasi gizi terbaik berdasarkan anggaran dan wilayah Anda.
                        </p>
                    </div>
                `;
            }

            const formData = new FormData(aiForm);
            formData.append('calculate_ai', '1');

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('resultContainer');
                
                if (resultContainer && newContainer) {
                    resultContainer.innerHTML = newContainer.innerHTML;
                }
            })
            .catch(error => {
                if (resultContainer) {
                    resultContainer.innerHTML = `
                        <div class="alert alert-danger border-0 shadow-sm rounded-4 p-4 text-center my-auto">
                            <h6 class="fw-bold">Terjadi Kesalahan</h6>
                            <p class="small mb-0">Gagal memuat rekomendasi AI. Silakan coba beberapa saat lagi.</p>
                        </div>
                    `;
                }
            });
        });
    }
</script>

</body>
</html>