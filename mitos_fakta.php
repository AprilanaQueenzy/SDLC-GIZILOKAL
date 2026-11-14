<?php
require_once 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Edukasi Mitos & Fakta Gizi - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2e7d32;
            --primary-light: #e8f5e9;
            --accent-red: #d32f2f;
            --accent-red-light: #ffebee;
            --bg-gradient: linear-gradient(135deg, #f0f7f4 0%, #e1f0eb 50%, #f4f7f6 100%);
        }

        body { 
            background: var(--bg-gradient); 
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; 
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            color: #334155;
        }

        .navbar-custom { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0; 
            padding: 14px 20px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .hero-section {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
            color: white;
            padding: 50px 20px;
            border-radius: 24px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(46, 125, 50, 0.15);
            position: relative;
            overflow: hidden;
        }

        .main-container { max-width: 1100px; width: 100%; margin: 30px auto; padding: 0 15px; flex: 1; }

        .search-box {
            background: white;
            border-radius: 50px;
            padding: 8px 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
            border: 1px solid #cbd5e1;
        }

        .article-card { 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(46, 125, 50, 0.04); 
            background: #ffffff; 
            transition: all 0.3s ease; 
            height: 100%;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .article-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 15px 35px rgba(46, 125, 50, 0.1); 
        }

        .badge-mitos { 
            background-color: var(--accent-red-light); 
            color: var(--accent-red); 
            font-weight: 700; 
            padding: 6px 14px; 
            border-radius: 30px; 
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .source-tag { 
            font-size: 0.75rem; 
            color: #475569; 
            background: #f1f5f9; 
            padding: 5px 12px; 
            border-radius: 10px; 
            border: 1px solid #e2e8f0; 
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        .card-body-custom { padding: 25px; }
        
        .highlight-box {
            background: #f8fafc;
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            border-radius: 0 12px 12px 0;
            margin-top: 15px;
        }

        /* AI Section Style */
        .ai-consult-card {
            background: linear-gradient(135deg, #ffffff 0%, #f4fbf7 100%);
            border: 2px dashed #2e7d32;
            border-radius: 24px;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0 10px 30px rgba(46, 125, 50, 0.08);
        }

        .ai-chat-box {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 20px;
            min-height: 120px;
            max-height: 250px;
            overflow-y: auto;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-custom">
        <div class="container-fluid px-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">GiziLokal Edukasi</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">Pusat Klarifikasi Mitos & Fakta Gizi</small>
                </div>
            </div>
            <div>
                <a href="index.php" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-semibold">
                    <i class="fa-solid fa-house me-1"></i> Beranda
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        
        <!-- Hero Banner -->
        <div class="hero-section text-center">
            <span class="badge bg-white text-success px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">
                <i class="fa-solid fa-shield-halved me-1"></i> Standar Kemenkes RI, WHO & BSA
            </span>
            <h1 class="fw-bold mb-3 display-6">Pojok Mitos vs Fakta Gizi Lengkap</h1>
            <p class="mb-4 text-white-50 mx-auto" style="max-width: 650px;">
                Kumpulan artikel verifikasi kesehatan pangan nusantara. Dilengkapi fitur Text-to-Speech agar artikel dapat didengarkan secara audio.
            </p>
            
            <!-- Search Bar Interaktif -->
            <div class="mx-auto" style="max-width: 500px;">
                <div class="input-group search-box">
                    <span class="input-group-text bg-transparent border-0 text-success"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="searchInput" class="form-control border-0 shadow-none bg-transparent" placeholder="Cari topik mitos/fakta di daftar..." onkeyup="filterArticles()">
                </div>
            </div>
        </div>

        <!-- Daftar Artikel Grid (12 Artikel) -->
        <div class="row g-4" id="articleContainer">
            
            <!-- Artikel 1 -->
            <div class="col-md-6 article-item" data-keywords="salmon buah impor kembung lokal protein omega">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Kemenkes & FAO/WHO</span>
                            <span class="badge-mitos">Mitos #1</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Ikan Salmon & Buah Impor Adalah Satu-Satunya Sumber Gizi Terbaik</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Gizi tinggi dan omega-3 hanya bisa didapatkan dari buah impor mahal serta ikan salmon.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Pangan lokal Ikan Kembung memiliki kandungan Omega-3 yang sangat tinggi dan setara salmon dengan harga jauh lebih terjangkau.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 2 -->
            <div class="col-md-6 article-item" data-keywords="susu sapi mahal stunting anak otak protein hewani">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Pedoman Gizi Seimbang</span>
                            <span class="badge-mitos">Mitos #2</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Anak Harus Diberi Susu Sapi Mahal Agar Cerdas & Tidak Stunting</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Pencegahan stunting mutlak memerlukan produk susu formula impor mahal.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Protein hewani lokal seperti telur, ikan lele, dan ikan gabus memiliki asam amino esensial yang sangat efektif mencegah stunting secara ekonomis.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 3 -->
            <div class="col-md-6 article-item" data-keywords="karbohidrat nasi ubi diet gula darah WHO">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Standar WHO</span>
                            <span class="badge-mitos">Mitos #3</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Menghindari Karbohidrat Total Secara Ketat Adalah Kunci Diet Sehat</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Diet sukses mengharuskan seseorang berhenti total mengonsumsi karbohidrat.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">WHO merekomendasikan karbohidrat kompleks seperti ubi jalar, singkong, dan beras merah untuk menjaga kestabilan metabolisme.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 4 -->
            <div class="col-md-6 article-item" data-keywords="telur kolesterol kuning jantung hipertensi">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Asosiasi Dietisien Indonesia</span>
                            <span class="badge-mitos">Mitos #4</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Makan Kuning Telur Setiap Hari Menyebabkan Penyakit Jantung</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Kuning telur mengandung kolesterol tinggi yang memicu lonjakan penyakit jantung.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Kuning telur kaya akan kolin dan vitamin D yang aman dan penting untuk fungsi kognitif otak serta kesehatan sel tubuh.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 5 -->
            <div class="col-md-6 article-item" data-keywords="suplemen vitamin kimia instan daya tahan tubuh">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Pedoman Gizi Kemenkes</span>
                            <span class="badge-mitos">Mitos #5</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Suplemen Vitamin Buatan Wajib Dikonsumsi untuk Jaga Imunitas</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Makanan harian tidak cukup melindungi tubuh, sehingga pil vitamin sintetis wajib diminum.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Vitamin dari sayur dan buah segar jauh lebih optimal karena dilengkapi enzim alami dan serat yang tidak ada di dalam suplemen pil.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 6 -->
            <div class="col-md-6 article-item" data-keywords="malam gemuk berat badan metabolisme malam hari">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Riset Kesehatan WHO</span>
                            <span class="badge-mitos">Mitos #6</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Makan Malam Setelah Jam 8 Otomatis Membuat Berat Badan Naik</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Metabolisme melambat di malam hari sehingga makanan langsung berubah jadi lemak.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Penambahan berat badan ditentukan oleh total kalori harian, bukan jam makan. Selama porsi terkontrol, makan malam sehat aman dikonsumsi.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 7 -->
            <div class="col-md-6 article-item" data-keywords="susu kental manis susu pertumbuhan anak balita gizi">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Badan POM & Kemenkes</span>
                            <span class="badge-mitos">Mitos #7</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Susu Kental Manis Sangat Baik Diseduh Sebagai Susu Pertumbuhan Anak</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Kental manis dianggap produk susu bernutrisi tinggi yang menyehatkan balita.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Kental manis tinggi kandungan gula dan rendah protein, tidak boleh diseduh sebagai minuman susu pengganti karena memicu risiko obesitas dini.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 8 -->
            <div class="col-md-6 article-item" data-keywords="air putih 8 gelas dehidrasi ginjal metabolisme">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Panduan Klinis WHO</span>
                            <span class="badge-mitos">Mitos #8</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Semua Orang Wajib Minum Air Putih Tepat 8 Gelas Sehari Tanpa Terkecuali</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Takaran universal 8 gelas sehari adalah aturan kaku mutlak bagi setiap individu.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Kebutuhan cairan bersifat personal bergantung pada berat badan, tingkat aktivitas fisik, dan iklim lingkungan tempat tinggal seseorang.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 9 -->
            <div class="col-md-6 article-item" data-keywords="garam laut himalaya natrium darah tinggi hipertensi">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Kemenkes RI</span>
                            <span class="badge-mitos">Mitos #9</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Garam Himalaya Bebas Natrium dan Aman Dikonsumsi Berlebihan</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Garam merah muda ini tidak menaikkan tekanan darah seperti garam dapur biasa.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Garam Himalaya tetap tersusun atas 98% natrium klorida yang efeknya serupa pada tekanan darah jika dikonsumsi melebihi batas anjuran harian.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 10 -->
            <div class="col-md-6 article-item" data-keywords="makanan organik bebas pestisida nutrisi lebih tinggi">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> FAO & WHO Scientific Review</span>
                            <span class="badge-mitos">Mitos #10</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Produk Makanan Organik Pasti Memiliki Kandungan Gizi Jauh Lebih Unggul</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Sayur dan buah organik mengandung vitamin dan mineral dua kali lipat lebih banyak.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Secara nutrisi makro, buah/sayur organik setara dengan konvensional; perbedaannya terletak pada minimnya residu bahan kimia sintetis pestisida.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 11 -->
            <div class="col-md-6 article-item" data-keywords="margarin mentega trans fat kolesterol jahat sehat">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Standar Kesehatan WHO</span>
                            <span class="badge-mitos">Mitos #11</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Margarin Nabati Selalu Lebih Menyehatkan Daripada Mentega Hewani</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Karena terbuat dari tumbuhan, margarin aman dikonsumsi tanpa batas untuk jantung.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Sebagian margarin padat proses industri mengandung lemak trans yang justru berbahaya bagi pembuluh darah jika tidak dipilih jenis yang bebas lemak trans.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artikel 12 -->
            <div class="col-md-6 article-item" data-keywords="makan buah sesudah makan pencernaan fermentasi perut kembung">
                <div class="card article-card">
                    <div class="card-body card-body-custom d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="source-tag"><i class="fa-solid fa-book-medical text-success"></i> Ahli Gastroenterologi & BSA</span>
                            <span class="badge-mitos">Mitos #12</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-3 article-title">Makan Buah Setelah Makan Utama Dapat Menyebabkan Pembusukan Perut</h5>
                        <div class="mb-3">
                            <p class="text-danger small mb-1 fw-bold"><i class="fa-solid fa-circle-xmark me-1"></i> Klaim Keliru:</p>
                            <p class="text-muted small mb-2 article-claim">Buah yang dimakan setelah makan berat akan tertahan dan membusuk di dalam lambung.</p>
                        </div>
                        <div class="highlight-box mt-auto">
                            <p class="text-success small mb-1 fw-bold"><i class="fa-solid fa-circle-check me-1"></i> Fakta Ilmiah:</p>
                            <p class="text-dark small mb-0 fw-medium article-fact">Sistem pencernaan manusia sangat efisien memecah berbagai zat gizi secara bersamaan. Enzim lambung akan memproses makanan tanpa ada proses pembusukan.</p>
                        </div>
                        <div class="mt-3 pt-2 border-top text-end">
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3 speech-btn" onclick="speakArticle(this)">
                                <i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Pesan Jika Tidak Ditemukan -->
        <div id="noResult" class="text-center py-5 d-none">
            <i class="fa-solid fa-face-frown text-muted fs-1 mb-3"></i>
            <h5 class="text-dark fw-bold">Topik tidak ditemukan dalam daftar di atas</h5>
            <p class="text-muted small">Tenang, Anda bisa menggunakan fitur **AI Nutrition Assistant** di bawah untuk mencari kebenaran topik tersebut!</p>
        </div>


        <!-- FITUR AI NUTRITION ASSISTANT (Kemenkes, WHO, BSA) -->
        <div class="ai-consult-card">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; font-size: 1.3rem;">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-0">AI Nutrition Assistant (Kemenkes &bull; WHO &bull; BSA)</h4>
                    <p class="text-muted small mb-0">Tanyakan langsung mitos atau fakta makanan pada AI untuk memperoleh informasi valid berbasis instansi kesehatan resmi.</p>
                </div>
            </div>
            <div id="aiChatBox" class="p-3 mb-3 bg-white rounded-3 border" style="max-height: 350px; overflow-y: auto; display: none;">
            </div>
            <div class="input-group">
                <input type="text" id="aiQueryInput" class="form-control rounded-start-pill px-3 py-2" placeholder="Contoh: Apakah benar makan mie instan pakai nasi itu berbahaya bagi tubuh?" onkeydown="if(event.key === 'Enter') askNutritionAI();">
                <button type="button" id="aiSendBtn" class="btn btn-success rounded-end-pill px-4" onclick="askNutritionAI()">
                    <i class="fa-solid fa-paper-plane me-1"></i> Tanya AI
                </button>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 text-center text-muted mt-auto">
        <div class="container">
            <small>&copy; 2026 GiziLokal &bull; Sumber Data Valid: Kementerian Kesehatan RI, WHO & Badan Standar (BSA)</small>
        </div>
    </footer>

    <!-- Bootstrap JS & Script Logika Lengkap Termasuk Text-to-Speech -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const apiKey = "AQ.Ab8RN6LzWBolEBh-H3Hg9nhxPTzFOdM8NtZHh62q4lIljNSKkw"; 
        const synth = window.speechSynthesis;
        let activeSpeechButton = null;

        // Fungsi Text-to-Speech untuk Artikel
        function speakArticle(buttonElement) {
            if (!synth) {
                alert("Maaf, browser Anda tidak mendukung fitur Text-to-Speech.");
                return;
            }

            if (synth.speaking) {
                synth.cancel();
                if (activeSpeechButton) {
                    activeSpeechButton.innerHTML = '<i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel';
                    activeSpeechButton.classList.replace('btn-danger', 'btn-outline-success');
                }
                if (activeSpeechButton === buttonElement) {
                    activeSpeechButton = null;
                    return;
                }
            }

            const card = buttonElement.closest('.article-card');
            const title = card.querySelector('.article-title').innerText;
            const claim = card.querySelector('.article-claim').innerText;
            const fact = card.querySelector('.article-fact').innerText;

            const textToSpeak = `Judul artikel: ${title}. ${claim}. ${fact}`;
            const utterThis = new SpeechSynthesisUtterance(textToSpeak);
            utterThis.lang = 'id-ID';
            utterThis.rate = 1.0;

            activeSpeechButton = buttonElement;
            activeSpeechButton.innerHTML = '<i class="fa-solid fa-stop me-1"></i> Berhentikan Audio';
            activeSpeechButton.classList.replace('btn-outline-success', 'btn-danger');

            utterThis.onend = () => {
                activeSpeechButton.innerHTML = '<i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel';
                activeSpeechButton.classList.replace('btn-danger', 'btn-outline-success');
                activeSpeechButton = null;
            };

            utterThis.onerror = () => {
                activeSpeechButton.innerHTML = '<i class="fa-solid fa-volume-high me-1"></i> Dengarkan Artikel';
                activeSpeechButton.classList.replace('btn-danger', 'btn-outline-success');
                activeSpeechButton = null;
            };

            synth.speak(utterThis);
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

        // Fungsi AI Nutrition Assistant dengan Real Gemini API & Rate Limit Handler serta Loading State
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

            // Ubah tombol menjadi status loading
            const originalBtnContent = sendBtn.innerHTML;
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menganalisis...';

            const typingId = 'typing-' + Date.now();
            chatBox.innerHTML += `
                <div class="mb-2 text-start" id="${typingId}">
                    <div class="p-3 rounded-3 border bg-white d-inline-block text-secondary small shadow-sm">
                        <div class="spinner-grow spinner-grow-sm text-success me-1" role="status"></div> Menganalisis fakta ilmiah, mohon tunggu...
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
                                <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1" style="font-size: 0.75rem;" onclick="speakAiResponse('${uniqueAiId}', this)">
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
                chatBox.innerHTML += `<div class="alert alert-danger small py-2 shadow-sm">Gagal terhubung ke server AI. Periksa koneksi internet Anda.</div>`;
            } finally {
                queryInput.disabled = false;
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalBtnContent;
                queryInput.focus();
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        }

        // Fungsi Text-to-Speech untuk Jawaban AI
        function speakAiResponse(elementId, buttonElement) {
            if (!synth) {
                alert("Browser tidak mendukung Text-to-Speech.");
                return;
            }

            if (synth.speaking) {
                synth.cancel();
                if (activeSpeechButton) {
                    activeSpeechButton.innerHTML = '<i class="fa-solid fa-volume-high me-1"></i> Dengarkan Jawaban AI';
                    activeSpeechButton.classList.replace('btn-danger', 'btn-outline-success');
                }
                if (activeSpeechButton === buttonElement) {
                    activeSpeechButton = null;
                    return;
                }
            }

            const text = document.getElementById(elementId).innerText;
            const utterThis = new SpeechSynthesisUtterance(text);
            utterThis.lang = 'id-ID';

            activeSpeechButton = buttonElement;
            activeSpeechButton.innerHTML = '<i class="fa-solid fa-stop me-1"></i> Berhentikan Audio';
            activeSpeechButton.classList.replace('btn-outline-success', 'btn-danger');

            utterThis.onend = () => {
                activeSpeechButton.innerHTML = '<i class="fa-solid fa-volume-high me-1"></i> Dengarkan Jawaban AI';
                activeSpeechButton.classList.replace('btn-danger', 'btn-outline-success');
                activeSpeechButton = null;
            };

            utterThis.onerror = () => {
                activeSpeechButton.innerHTML = '<i class="fa-solid fa-volume-high me-1"></i> Dengarkan Jawaban AI';
                activeSpeechButton.classList.replace('btn-danger', 'btn-outline-success');
                activeSpeechButton = null;
            };

            synth.speak(utterThis);
        }
    </script>
</body>
</html>