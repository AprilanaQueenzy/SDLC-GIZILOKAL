<?php
session_start();
require_once 'auth_check.php';

// Tingkatkan batas waktu eksekusi dan memori untuk proses gambar AI
@ini_set('max_execution_time', 120);
@ini_set('memory_limit', '256M');

$hasil_scan = null;
$error_msg = null;
$preview_image = null;

// Konfigurasi API Gemini (Model: gemini-3.6-flash)
$apiKey = "AQ.Ab8RN6JBApOgkIRs6n9-G-j1tsn9K5-TpAtxVoLmkQAe9UimXw";

if (isset($_POST['proses_scan'])) {
    if (isset($_FILES['foto_makanan'])) {
        $file_error = $_FILES['foto_makanan']['error'];
        
        if ($file_error === UPLOAD_ERR_OK) {
            $lokasi_tmp = $_FILES['foto_makanan']['tmp_name'];
            $mime_type = mime_content_type($lokasi_tmp);
            
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/heic'];
            if (in_array($mime_type, $allowed_mimes)) {
                $image_data = file_get_contents($lokasi_tmp);
                $base64_image = base64_encode($image_data);

                $folder_tujuan = 'uploads/';
                if (!is_dir($folder_tujuan)) {
                    mkdir($folder_tujuan, 0777, true);
                }
                $nama_file_baru = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['foto_makanan']['name']);
                $path_lokal = $folder_tujuan . $nama_file_baru;
                move_uploaded_file($lokasi_tmp, $path_lokal);
                $preview_image = $path_lokal;

                $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=" . $apiKey;
                
                $prompt_text = "Bertindaklah sebagai ahli gizi profesional. Analisis foto makanan ini secara akurat. Berikan respons dalam format JSON murni tanpa teks pengantar atau markdown block lainnya, dengan struktur kunci sebagai berikut:
                {
                  \"nama_makanan\": \"Nama hidangan lengkap\",
                  \"total_kalori\": \"Contoh: 450 kkal\",
                  \"protein\": \"Contoh: 25 gram\",
                  \"karbohidrat\": \"Contoh: 50 gram\",
                  \"lemak\": \"Contoh: 15 gram\",
                  \"rincian_komponen\": [
                    {\"komponen\": \"Nama bahan/lauk 1\", \"porsi\": \"Estimasi porsi\"},
                    {\"komponen\": \"Nama bahan/lauk 2\", \"porsi\": \"Estimasi porsi\"}
                  ],
                  \"catatan_gizi\": \"Analisis mendalam mengenai kandungan nutrisi dan saran kesehatan terkait makanan tersebut.\"
                }";

                $data = [
                    "contents" => [
                        [
                            "parts" => [
                                ["text" => $prompt_text],
                                [
                                    "inline_data" => [
                                        "mime_type" => $mime_type,
                                        "data" => $base64_image
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                
                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curl_error = curl_error($ch);
                curl_close($ch);

                if ($http_code == 200) {
                    $response_data = json_decode($response, true);
                    if (isset($response_data['candidates'][0]['content']['parts'][0]['text'])) {
                        $json_text = trim($response_data['candidates'][0]['content']['parts'][0]['text']);
                        
                        $json_text = preg_replace('/^```json\s*|\s*```$/i', '', $json_text);
                        $json_text = preg_replace('/^```\s*|\s*```$/i', '', $json_text);
                        $json_text = trim($json_text);
                        
                        $hasil_scan = json_decode($json_text, true);
                        
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            $error_msg = "Format data dari AI tidak valid (JSON Parse Error). Silakan coba lagi.";
                        }
                    } else {
                        $error_msg = "Gagal memproses struktur data dari AI.";
                    }
                } else {
                    $response_data = json_decode($response, true);
                    $pesan_api = isset($response_data['error']['message']) ? $response_data['error']['message'] : ($curl_error ?: "HTTP Code: $http_code");
                    
                    if (strpos($pesan_api, 'ResourceExhausted') !== false || strpos($pesan_api, 'quota') !== false) {
                        $error_msg = "Kapasitas AI sedang penuh (Rate Limit). Silakan tunggu beberapa detik dan coba lagi.";
                    } else {
                        $error_msg = "Terjadi kesalahan koneksi ke server AI ($pesan_api).";
                    }
                }
            } else {
                $error_msg = "Format file tidak didukung. Harap unggah file JPG, PNG, atau WEBP.";
            }
        } elseif ($file_error === UPLOAD_ERR_INI_SIZE || $file_error === UPLOAD_ERR_FORM_SIZE) {
            $error_msg = "Ukuran file foto terlalu besar. Silakan pilih foto di bawah 5MB.";
        } else {
            $error_msg = "Silakan pilih atau unggah foto makanan terlebih dahulu.";
        }
    } else {
        $error_msg = "Tidak ada file yang diunggah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Kalori Makanan AI - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #f0f7f4 0%, #e1f0eb 50%, #f4f7f6 100%); 
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            margin: 0;
        }
        .navbar-custom { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 12px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .main-container { max-width: 900px; width: 100%; margin: 20px auto; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(46, 125, 50, 0.08); padding: 30px; border: 1px solid #e2e8f0; }
        .upload-box { border: 2px dashed #a3d9c9; background: #f9fdfb; transition: all 0.3s ease; cursor: pointer; }
        .upload-box:hover { background: #f0f7f4; border-color: #2e7d32; }
        
        /* Fullscreen Loading Overlay */
        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

    <!-- Loading Overlay Fullscreen -->
    <div id="loadingOverlay">
        <div class="spinner-border text-success mb-3" style="width: 3.5rem; height: 3.5rem;" role="status"></div>
        <h5 class="fw-bold text-dark mb-1">Sedang Menganalisis Makanan...</h5>
        <p class="text-muted small">Gemini AI sedang membaca kandungan gizi pada foto kamu, mohon tunggu sebentar.</p>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-custom">
        <div class="container-fluid px-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                    <i class="fa-solid fa-camera-retro"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Scan Kalori & Nutrisi Makanan</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">GiziLokal &bull; Didukung Gemini 3.6 Flash AI</small>
                </div>
            </div>
            <a href="index.php" class="btn btn-outline-success btn-sm fw-semibold">
                <i class="fa-solid fa-house me-1"></i> Kembali ke Beranda
            </a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container my-4">
        <div class="main-container">
            
            <div class="text-center mb-4">
                <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-utensils text-success me-2"></i>Deteksi Kalori Berbasis Foto</h4>
                <p class="text-muted small">Unggah foto makanan atau hidangan Anda untuk mengetahui estimasi kalori dan rincian makronutrisi secara instan.</p>
            </div>

            <?php if ($error_msg): ?>
                <div class="alert alert-warning d-flex align-items-center rounded-3 p-3 mb-4 shadow-sm" role="alert">
                    <i class="fa-solid fa-triangle-exclamation fs-4 me-3 text-warning"></i>
                    <div class="small text-dark"><?php echo htmlspecialchars($error_msg); ?></div>
                </div>
            <?php endif; ?>

            <!-- Form Upload -->
            <form action="" method="POST" enctype="multipart/form-data" class="mb-4" id="scanForm">
                <div class="upload-box p-4 rounded-4 text-center position-relative mb-3" onclick="document.getElementById('foto_makanan').click()">
                    <input type="file" name="foto_makanan" id="foto_makanan" class="d-none" accept="image/jpeg,image/png,image/webp" onchange="previewFile(this)">
                    <div id="uploadPlaceholder" class="<?php echo $preview_image ? 'd-none' : ''; ?>">
                        <i class="fa-solid fa-cloud-arrow-up text-success fs-1 mb-2"></i>
                        <h6 class="fw-bold text-dark mb-1">Klik atau seret foto makanan ke sini</h6>
                        <p class="text-muted small mb-0">Mendukung format JPG, PNG, atau WEBP (Maks. 5MB)</p>
                    </div>
                    <div id="previewContainer" class="<?php echo $preview_image ? '' : 'd-none'; ?>">
                        <img id="imagePreview" src="<?php echo $preview_image ? $preview_image : '#'; ?>" alt="Preview" class="rounded-3 shadow-sm mb-2" style="max-height: 200px; max-width: 100%; object-fit: contain;">
                        <p class="small text-success fw-semibold mb-0" id="fileName">Foto terpilih siap dianalisis</p>
                    </div>
                </div>

                <button type="submit" name="proses_scan" class="btn btn-success w-100 py-2 rounded-pill fw-semibold shadow-sm" id="submitBtn">
                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Analisis Kalori Sekarang
                </button>
            </form>

            <!-- Hasil Analisis -->
            <?php if ($hasil_scan): ?>
                <div class="card border-0 bg-light rounded-4 p-4 shadow-sm mt-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                        <div>
                            <span class="badge bg-success bg-opacity-15 text-success px-3 py-1 rounded-pill fw-semibold mb-1" style="font-size: 0.75rem;">Hasil Analisis AI</span>
                            <h3 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($hasil_scan['nama_makanan'] ?? 'Makanan Terdeteksi'); ?></h3>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small d-block">Total Estimasi</span>
                            <h4 class="fw-bold text-success mb-0"><?php echo htmlspecialchars($hasil_scan['total_kalori'] ?? '-'); ?></h4>
                        </div>
                    </div>

                    <!-- Makronutrisi Grid -->
                    <div class="row g-3 mb-4 text-center">
                        <div class="col-4">
                            <div class="p-3 bg-white rounded-3 border shadow-sm">
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem;"><i class="fa-solid fa-dumbbell text-primary me-1"></i>Protein</small>
                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($hasil_scan['protein'] ?? '-'); ?></span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-white rounded-3 border shadow-sm">
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem;"><i class="fa-solid fa-wheat-awn text-warning me-1"></i>Karbohidrat</small>
                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($hasil_scan['karbohidrat'] ?? '-'); ?></span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-white rounded-3 border shadow-sm">
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem;"><i class="fa-solid fa-droplet text-danger me-1"></i>Lemak</small>
                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($hasil_scan['lemak'] ?? '-'); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Komponen Bahan -->
                    <?php if (!empty($hasil_scan['rincian_komponen']) && is_array($hasil_scan['rincian_komponen'])): ?>
                        <div class="mb-3">
                            <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-list-check text-success me-2"></i>Rincian Komponen Makanan:</h6>
                            <ul class="list-group list-group-flush rounded-3 overflow-hidden border shadow-sm">
                                <?php foreach ($hasil_scan['rincian_komponen'] as $komponen): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-white py-2 px-3 small">
                                        <span class="fw-semibold text-dark"><?php echo htmlspecialchars($komponen['komponen']); ?></span>
                                        <span class="badge bg-light text-secondary border"><?php echo htmlspecialchars($komponen['porsi']); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Catatan Gizi -->
                    <?php if (!empty($hasil_scan['catatan_gizi'])): ?>
                        <div class="p-3 bg-white rounded-3 border border-success border-opacity-25 shadow-sm mt-3">
                            <h6 class="fw-bold text-success mb-1 small"><i class="fa-solid fa-circle-info me-1"></i> Catatan & Saran Gizi:</h6>
                            <p class="small text-muted mb-0"><?php echo nl2br(htmlspecialchars($hasil_scan['catatan_gizi'])); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewFile(input) {
            const placeholder = document.getElementById('uploadPlaceholder');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const fileName = document.getElementById('fileName');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                fileName.innerText = file.name;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    placeholder.classList.add('d-none');
                    previewContainer.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        }
        document.getElementById('scanForm').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('foto_makanan');
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Silakan pilih atau unggah foto makanan terlebih dahulu!');
                e.preventDefault();
                return;
            }

            // Munculkan overlay loading
            document.getElementById('loadingOverlay').style.display = 'flex';
        });
    </script>
</body>
</html>