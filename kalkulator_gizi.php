<?php
require_once 'auth_check.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$calc_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calculate_gizi'])) {
    $berat = floatval($_POST['berat'] ?? 0);
    $tinggi = floatval($_POST['tinggi'] ?? 0);
    $usia = intval($_POST['usia'] ?? 0);
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? 'L';
    $aktivitas = floatval($_POST['aktivitas'] ?? 1.2);

    if ($berat > 0 && $tinggi > 0 && $usia > 0) {
        if ($jenis_kelamin === 'L') {
            $bmr = (10 * $berat) + (6.25 * $tinggi) - (5 * $usia) + 5;
        } else {
            $bmr = (10 * $berat) + (6.25 * $tinggi) - (5 * $usia) - 161;
        }
        $tdee = $bmr * $aktivitas;
        $protein_rec = ($tdee * 0.15) / 4; 
        $lemak_rec = ($tdee * 0.25) / 9;   
        $karbo_rec = ($tdee * 0.60) / 4;   

        $calc_result = [
            'status' => 'success',
            'bmr' => round($bmr),
            'tdee' => round($tdee),
            'protein' => round($protein_rec),
            'lemak' => round($lemak_rec),
            'karbo' => round($karbo_rec)
        ];
    } else {
        $calc_result = [
            'status' => 'error',
            'message' => 'Mohon masukkan angka berat badan, tinggi badan, dan usia yang valid!'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung Gizi Harian - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            position: relative;
            overflow-x: hidden;
            background-color: #f0fdf4;
            background-image: 
                radial-gradient(at 10% 20%, rgba(16, 185, 129, 0.12) 0px, transparent 50%),
                radial-gradient(at 90% 80%, rgba(5, 150, 105, 0.1) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(209, 250, 229, 0.5) 0px, transparent 70%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Efek Cahaya Ambient Blur di Sudut */
        body::before,
        body::after {
            content: "";
            position: fixed;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            z-index: -1;
            filter: blur(90px);
            opacity: 0.5;
            pointer-events: none;
        }

        body::before {
            top: -100px;
            left: -100px;
            background: linear-gradient(135deg, #34d399 0%, #059669 100%);
        }

        body::after {
            bottom: -100px;
            right: -100px;
            background: linear-gradient(135deg, #6ee7b7 0%, #10b981 100%);
        }

        /* Dekorasi Kartu Melayang Sisi Kiri & Kanan (Hanya tampil di layar besar/Desktop) */
        @media (min-width: 1400px) {
            .container {
                position: relative;
            }

            /* Badge Statistik Kiri */
            .container::before {
                content: "⚡ Analisis Real-Time";
                position: absolute;
                left: -220px;
                top: 180px;
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(10px);
                padding: 12px 20px;
                border-radius: 16px;
                font-size: 13px;
                font-weight: 700;
                color: #059669;
                box-shadow: 0 10px 30px rgba(5, 150, 105, 0.08);
                border: 1px solid rgba(255, 255, 255, 1);
                animation: floatLeft 6s ease-in-out infinite alternate;
                z-index: 0;
            }

            /* Badge Statistik Kanan */
            .container::after {
                content: "🥗 Standar Gizi Akurat";
                position: absolute;
                right: -220px;
                bottom: 200px;
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(10px);
                padding: 12px 20px;
                border-radius: 16px;
                font-size: 13px;
                font-weight: 700;
                color: #047857;
                box-shadow: 0 10px 30px rgba(5, 150, 105, 0.08);
                border: 1px solid rgba(255, 255, 255, 1);
                animation: floatRight 6s ease-in-out infinite alternate;
                z-index: 0;
            }
        }

        /* Animasi Melayang Halus */
        @keyframes floatLeft {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-12px); }
        }

        @keyframes floatRight {
            0% { transform: translateY(0px); }
            100% { transform: translateY(12px); }
        }
     </style>
</head>
<body>

<div class="container py-5">
    <div class="mb-4">
        <a href="index.php" class="btn btn-light btn-sm rounded-pill px-3 fw-bold border shadow-sm text-secondary hover-scale">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke halaman utama
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card card-custom p-4 p-md-5">
                <!-- Header Section -->
                <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-4 me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 68px; height: 68px; font-size: 28px;">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                    <div>
                        <h3 class="fw-extrabold text-dark mb-1">Hitung Gizi Harian</h3>
                        <p class="text-muted mb-0">Analisis Basal Metabolic Rate (BMR) & Total Daily Energy Expenditure (TDEE) secara presisi.</p>
                    </div>
                </div>

                <div class="row g-4 align-items-stretch">
                    <!-- Form Input -->
                    <div class="col-lg-6">
                        <div class="p-4 p-lg-4 bg-white rounded-4 h-100 border shadow-sm">
                            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-sliders me-2 text-success"></i> Data Fisik & Aktivitas</h5>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Jenis Kelamin</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-venus-mars"></i></span>
                                        <select name="jenis_kelamin" class="form-select" required>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Berat Badan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-weight-scale"></i></span>
                                        <input type="number" step="0.1" name="berat" class="form-control" placeholder="Contoh: 60" required>
                                        <span class="input-group-text bg-light text-muted border-start-0 rounded-end-4" style="border-radius: 0 14px 14px 0;">kg</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Tinggi Badan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-ruler-vertical"></i></span>
                                        <input type="number" step="0.1" name="tinggi" class="form-control" placeholder="Contoh: 165" required>
                                        <span class="input-group-text bg-light text-muted border-start-0" style="border-radius: 0 14px 14px 0;">cm</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Usia</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                                        <input type="number" name="usia" class="form-control" placeholder="Contoh: 25" required>
                                        <span class="input-group-text bg-light text-muted border-start-0" style="border-radius: 0 14px 14px 0;">tahun</span>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-secondary">Tingkat Aktivitas Fisik</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-person-running"></i></span>
                                        <select name="aktivitas" class="form-select" required>
                                            <option value="1.2">Jarang / Duduk seharian (1.2)</option>
                                            <option value="1.375">Ringan (Olahraga 1-3x/mg) (1.375)</option>
                                            <option value="1.55">Sedang (Olahraga 3-5x/mg) (1.55)</option>
                                            <option value="1.725">Berat (Olahraga 6-7x/mg) (1.725)</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="calculate_gizi" class="btn btn-success-custom w-100 fw-bold py-3 rounded-3 shadow-sm text-white">
                                    <i class="fa-solid fa-bolt me-2"></i> Hitung Kebutuhan Gizi
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Area Hasil -->
                    <div class="col-lg-6 d-flex flex-column">
                        <div class="result-card p-4 p-lg-4 rounded-4 h-100 d-flex flex-column justify-content-center text-center shadow-sm">
                            <?php if ($calc_result): ?>
                                <?php if ($calc_result['status'] === 'success'): ?>
                                    <div class="text-success mb-2"><i class="fa-solid fa-circle-check fs-2"></i></div>
                                    <h5 class="fw-bold text-dark mb-3">Hasil Analisis Gizi Anda</h5>
                                    
                                    <div class="p-3 bg-white rounded-4 mb-3 border shadow-sm text-start position-relative overflow-hidden">
                                        <div class="position-absolute top-0 start-0 bottom-0 bg-secondary opacity-25" style="width: 4px;"></div>
                                        <span class="d-block small text-muted fw-semibold ps-2">BMR (Basal Metabolic Rate):</span>
                                        <h4 class="fw-extrabold text-dark mb-0 ps-2 mt-1"><?= $calc_result['bmr'] ?> <span class="fs-6 text-muted fw-normal">kkal/hari</span></h4>
                                    </div>
                                    
                                    <div class="p-3 bg-success bg-opacity-10 rounded-4 mb-3 border border-success border-opacity-25 shadow-sm text-start position-relative overflow-hidden">
                                        <div class="position-absolute top-0 start-0 bottom-0 bg-success" style="width: 4px;"></div>
                                        <span class="d-block small text-success fw-bold ps-2">Total Kebutuhan Kalori (TDEE):</span>
                                        <h3 class="fw-extrabold text-success mb-0 ps-2 mt-1"><?= $calc_result['tdee'] ?> <span class="fs-6 fw-normal">kkal/hari</span></h3>
                                    </div>

                                    <div class="text-start bg-white p-3 rounded-4 border shadow-sm">
                                        <p class="mb-2 small fw-bold text-dark"><i class="fa-solid fa-utensils me-1 text-success"></i> Rekomendasi Makronutrien Harian:</p>
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <div class="p-2 bg-light rounded-3 text-center border">
                                                    <span class="d-block text-muted" style="font-size: 11px;">Protein</span>
                                                    <strong class="text-dark small"><?= $calc_result['protein'] ?>g</strong>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 bg-light rounded-3 text-center border">
                                                    <span class="d-block text-muted" style="font-size: 11px;">Lemak</span>
                                                    <strong class="text-dark small"><?= $calc_result['lemak'] ?>g</strong>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-2 bg-light rounded-3 text-center border">
                                                    <span class="d-block text-muted" style="font-size: 11px;">Karbo</span>
                                                    <strong class="text-dark small"><?= $calc_result['karbo'] ?>g</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-danger p-3">
                                        <i class="fa-solid fa-triangle-exclamation fs-2 mb-2"></i>
                                        <p class="small mb-0"><?= $calc_result['message'] ?></p>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-muted p-4 my-auto">
                                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 75px; height: 75px; font-size: 30px;">
                                        <i class="fa-solid fa-heart-pulse"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-2">Belum Ada Perhitungan</h5>
                                    <p class="small mb-0 text-muted mx-auto" style="max-width: 280px;">Silakan isi formulir data fisik di samping dan klik tombol hitung untuk melihat rincian kalori Anda.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>