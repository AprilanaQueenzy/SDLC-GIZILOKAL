<?php
require_once 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Resep & Nutrisi Nusantara - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #f0f7f4 0%, #e1f0eb 50%, #f4f7f6 100%); 
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; 
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }
        .navbar-custom { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 12px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .main-container { max-width: 1200px; width: 100%; margin: 30px auto; padding: 0 15px; flex: 1; }
        .filter-section { background: #ffffff; padding: 20px; border-radius: 16px; box-shadow: 0 8px 25px rgba(46, 125, 50, 0.05); border: 1px solid #e2e8f0; margin-bottom: 30px; }
        .recipe-card { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(46, 125, 50, 0.06); transition: transform 0.2s ease; overflow: hidden; background: #ffffff; height: 100%; }
        .recipe-card:hover { transform: translateY(-5px); }
        .recipe-img-wrapper { height: 180px; background-color: #e8f5e9; display: flex; align-items: center; justify-content: center; color: #2e7d32; font-size: 2.5rem; }
        .badge-nutrition { background-color: #e8f5e9; color: #2e7d32; font-weight: 600; padding: 5px 10px; border-radius: 20px; font-size: 0.75rem; }
        .btn-success-custom { background-color: #2e7d32; border: none; font-weight: 600; border-radius: 10px; padding: 8px 15px; }
        .btn-success-custom:hover { background-color: #1b5e20; }

        .recipe-modal .modal-content { border: none; border-radius: 20px; overflow: hidden; }
        .recipe-modal .modal-header { background: linear-gradient(135deg, #2e7d32, #43a047); color: #fff; border: 0; padding: 20px 24px; }
        .recipe-modal .modal-body { padding: 24px; }
        .recipe-section { background: #f7faf8; border: 1px solid #e4eee6; border-radius: 14px; padding: 16px; height: 100%; }
        .recipe-section h6 { color: #2e7d32; font-weight: 700; margin-bottom: 12px; }
        .recipe-section ul, .recipe-section ol { margin-bottom: 0; padding-left: 20px; }
        .recipe-section li { margin-bottom: 7px; color: #4b5563; }
        .recipe-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .recipe-meta span { background: rgba(255,255,255,.18); padding: 5px 10px; border-radius: 999px; font-size: .8rem; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-custom">
        <div class="container-fluid px-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Katalog Resep & Nutrisi Nusantara</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">GiziLokal &bull; Katalog Mega 38 Provinsi (3 Menu Per Daerah)</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="ai_konsultasi.php" class="btn btn-outline-success btn-sm fw-semibold">
                    <i class="fa-solid fa-stethoscope me-1"></i> Konsultasi AI
                </a>
                <a href="index.php" class="btn btn-outline-dark btn-sm fw-semibold">
                    <i class="fa-solid fa-house me-1"></i> Utama
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        
        <!-- Filter Provinsi -->
        <div class="filter-section">
            <div class="row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-map-location-dot text-success me-2"></i>Pilih Provinsi</h5>
                    <p class="text-muted small mb-0">Eksplorasi katalog masakan pemulihan kesehatan terlengkap.</p>
                </div>
                <div class="col-md-8">
                    <select id="provinceSelect" class="form-select form-select-lg border-success" onchange="filterRecipes()">
                        <option value="all">Tampilkan Semua Provinsi</option>
                        <option value="Aceh">Aceh</option>
                        <option value="Sumatera Utara">Sumatera Utara</option>
                        <option value="Sumatera Barat">Sumatera Barat</option>
                        <option value="Riau">Riau</option>
                        <option value="Kepulauan Riau">Kepulauan Riau</option>
                        <option value="Jambi">Jambi</option>
                        <option value="Sumatera Selatan">Sumatera Selatan</option>
                        <option value="Bangka Belitung">Kepulauan Bangka Belitung</option>
                        <option value="Bengkulu">Bengkulu</option>
                        <option value="Lampung">Lampung</option>
                        <option value="DKI Jakarta">DKI Jakarta</option>
                        <option value="Jawa Barat">Jawa Barat</option>
                        <option value="Banten">Banten</option>
                        <option value="Jawa Tengah">Jawa Tengah</option>
                        <option value="DI Yogyakarta">DI Yogyakarta</option>
                        <option value="Jawa Timur">Jawa Timur</option>
                        <option value="Bali">Bali</option>
                        <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                        <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                        <option value="Kalimantan Barat">Kalimantan Barat</option>
                        <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                        <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                        <option value="Kalimantan Timur">Kalimantan Timur</option>
                        <option value="Kalimantan Utara">Kalimantan Utara</option>
                        <option value="Sulawesi Utara">Sulawesi Utara</option>
                        <option value="Gorontalo">Gorontalo</option>
                        <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                        <option value="Sulawesi Barat">Sulawesi Barat</option>
                        <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                        <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                        <option value="Maluku">Maluku</option>
                        <option value="Maluku Utara">Maluku Utara</option>
                        <option value="Papua">Papua</option>
                        <option value="Papua Barat">Papua Barat</option>
                        <option value="Papua Selatan">Papua Selatan</option>
                        <option value="Papua Tengah">Papua Tengah</option>
                        <option value="Papua Pegunungan">Papua Pegunungan</option>
                        <option value="Papua Barat Daya">Papua Barat Daya</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Daftar Resep Grid (3 Menu Per Provinsi) -->
        <div class="row g-4" id="recipeContainer">
            
            <!-- 1. ACEH -->
            <div class="col-md-4 recipe-item" data-province="Aceh">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Aceh &bull; Imun Booster</span>
                        <h5 class="fw-bold text-dark mb-2">Kuah Pliek U</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Gulai sayuran kaya antioksidan dan serat tinggi untuk pemulihan daya tahan tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Aceh">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-mug-hot"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Aceh &bull; Vitamin & Mineral</span>
                        <h5 class="fw-bold text-dark mb-2">Keumamah Ikan Kayu</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan ikan tongkol kering khas Aceh kaya protein tinggi untuk pemulihan otot dan stamina.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Aceh">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Aceh &bull; Protein Omega</span>
                        <h5 class="fw-bold text-dark mb-2">Asam Keueng Aceh</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Gulai ikan kuah asam pedas menyegarkan penambah nafsu makan masa penyembuhan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 2. SUMATERA UTARA -->
            <div class="col-md-4 recipe-item" data-province="Sumatera Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sumatera Utara &bull; Protein Tinggi</span>
                        <h5 class="fw-bold text-dark mb-2">Arsik Ikan Mas</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan mas bumbu arsik khas Batak dengan andaliman dan kunyit untuk memulihkan tenaga.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sumatera Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sumatera Utara &bull; Serat & Karbohidrat</span>
                        <h5 class="fw-bold text-dark mb-2">Soto Medan Kuah Santan Rempah</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Soto berkaldu gurih harum serai dan jinten untuk membangkitkan nafsu makan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sumatera Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sumatera Utara &bull; Zat Besi Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Daun Ubi Tumbuk Batak</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sayur daun singkong ditumbuk dengan kecombrang dan santan kaya nutrisi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 3. SUMATERA BARAT -->
            <div class="col-md-4 recipe-item" data-province="Sumatera Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-plate-wheat"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sumatera Barat &bull; Kaldu Bening</span>
                        <h5 class="fw-bold text-dark mb-2">Soto Padang Kuah Bening</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Soto daging sapi kuah rempah bening yang menghangatkan pencernaan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sumatera Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sumatera Barat &bull; Vitamin Nabati</span>
                        <h5 class="fw-bold text-dark mb-2'>Gulai Paku & Pucuk Ubi</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sayur daun singkong dan paku kaya zat besi untuk mencegah anemia masa pemulihan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sumatera Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sumatera Barat &bull; Protein Ikan Asam</span>
                        <h5 class="fw-bold text-dark mb-2'>Asam Padeh Ikan Tuna</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan ikan tanpa santan berkuah asam pedas segar penguat daya tahan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 4. RIAU -->
            <div class="col-md-4 recipe-item" data-province="Riau">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish-fins"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Riau &bull; Omega-3 Tinggi</span>
                        <h5 class="fw-bold text-dark mb-2'>Gulai Patin Asam Pedas</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan patin segar berkuah asam pedas rimpang untuk membangkitkan selera.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Riau">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Riau &bull; Serat Sagu Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Rujak Sagu Riau</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan sagu tradisional bergizi tinggi penambah cadangan energi tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Riau">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Riau &bull; Vitamin C Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Kelakai Riau</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Tumis pakis darat segar penambah darah penangkal letih lesu.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 5. KEPULAUAN RIAU -->
            <div class="col-md-4 recipe-item" data-province="Kepulauan Riau">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kepri &bull; Protein Hewani</span>
                        <h5 class="fw-bold text-dark mb-2'>Mie Lendir Khas Kepri</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan mi kuah kacang berkaldu udang gurih yang mudah dicerna tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kepulauan Riau">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kepri &bull; Vitamin Laut Segar</span>
                        <h5 class="fw-bold text-dark mb-2'>Sup Ikan Batam</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan tenggiri kuah bening dengan tomat hijau dan sawi asin penurun panas.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kepulauan Riau">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kepri &bull; Karbohidrat Sehat</span>
                        <h5 class="fw-bold text-dark mb-2'>Gonggong Rebus Hangat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Siput laut kaya protein zink penambah daya tahan dan vitalitas tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 6. JAMBI -->
            <div class="col-md-4 recipe-item" data-province="Jambi">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jambi &bull; Protein Air Tawar</span>
                        <h5 class="fw-bold text-dark mb-2'>Gulai Terjun Ikan</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Masakan tradisional Jambi kaya rempah pemberi kehangatan tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Jambi">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jambi &bull; Serat & Antioksidan</span>
                        <h5 class="fw-bold text-dark mb-2'>Tempoyak Ikan Patin</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Fermentasi durian berpadu ikan patin kaya nutrisi untuk pemulihan stamina.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Jambi">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jambi &bull; Vitamin Nabati</span>
                        <h5 class="fw-bold text-dark mb-2'>Nasi Gemuk Sehat Jambi</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Nasi gurih santan ringan berpadu telur rebus dan lalapan segar penambah energi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 7. SUMATERA SELATAN -->
            <div class="col-md-4 recipe-item" data-province="Sumatera Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sumsel &bull; Vitamin & Mineral</span>
                        <h5 class="fw-bold text-dark mb-2'>Pindang Patin Serai Kunyit</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan kuah bening dengan aroma serai, kemangi, dan kunyit penangkal radang.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sumatera Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sumsel &bull; Protein Ikan Gabus</span>
                        <h5 class="fw-bold text-dark mb-2'>Model Ikan Kuah Kaldu</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan gandum dan ikan tenggiri berkuah kaldu udang hangat yang mengenyangkan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sumatera Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sumsel &bull; Nutrisi Asam Amino</span>
                        <h5 class="fw-bold text-dark mb-2'>Laksan Kuah Santan Gurih</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Pempek gepeng lembut berkuah santan hangat penambah nafsu makan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 8. BANGKA BELITUNG -->
            <div class="col-md-4 recipe-item" data-province="Bangka Belitung">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Babel &bull; Segar & Bergizi</span>
                        <h5 class="fw-bold text-dark mb-2'>Lempah Kuning Ikan</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan laut segar kuah kuning nanas dan kunyit penambah vitalitas.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Bangka Belitung">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Babel &bull; Serat Nabati</span>
                        <h5 class="fw-bold text-dark mb-2'>Lempah Darat Sayuran</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup aneka sayuran hijau dan ubi, kaya vitamin mineral pelindung tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Bangka Belitung">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Babel &bull; Energi Seimbang</span>
                        <h5 class="fw-bold text-dark mb-2'>Mi Bangka Kuah Kaldu Ikan</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Mi lembut dengan taburan tauge dan irisan ikan segar penghilang lelah.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 9. BENGKULU -->
            <div class="col-md-4 recipe-item" data-province="Bengkulu">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Bengkulu &bull; Antioksidan Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Gulai Asam Undan</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan ikan segar dengan asam kandis dan belimbing wuluh penambah vitalitas.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Bengkulu">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Bengkulu &bull; Karbohidrat Sehat</span>
                        <h5 class="fw-bold text-dark mb-2'>Rebung Asam Undan</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Rebung bambu fermentasi sehat pencernaan kaya serat alami.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Bengkulu">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Bengkulu &bull; Protein Ikan Laut</span>
                        <h5 class="fw-bold text-dark mb-2'>Pendap Ikan Daun Talas</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan bumbu rempah kelapa dibungkus daun talas kukus pemulih tenaga.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 10. LAMPUNG -->
            <div class="col-md-4 recipe-item" data-province="Lampung">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Lampung &bull; Protein Nabati Hewani</span>
                        <h5 class="fw-bold text-dark mb-2'>Seruit Ikan Bakar Sambal Terasi</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Menu kebersamaan kaya nutrisi dengan ikan segar dan tempoyak khas Lampung.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Lampung">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Lampung &bull; Serat Sayur Segar</span>
                        <h5 class="fw-bold text-dark mb-2'>Gulai Taboh Lampung</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Gulai sayur kuah santan ringan berisi ubi kayu, kacang panjang, dan ikan asap.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Lampung">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Lampung &bull; Vitamin & Mineral</span>
                        <h5 class="fw-bold text-dark mb-2'>Gabing Batang Kelapa</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sayur berkuah santan ringan dari irisan batang kelapa muda kaya serat.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 11. DKI JAKARTA -->
            <div class="col-md-4 recipe-item" data-province="DKI Jakarta">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">DKI Jakarta &bull; Energi Seimbang</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Babanci & Nasi Tim</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Menu lunak tradisional Betawi kaya rempah yang ramah metabolisme tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="DKI Jakarta">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-mug-hot"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">DKI Jakarta &bull; Imunitas Tubuh</span>
                        <h5 class="fw-bold text-dark mb-2'>Soto Betawi Kuah Susu Rempah</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Soto daging sapi empuk berkuah susu dan rempah hangat memulihkan stamina.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="DKI Jakarta">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">DKI Jakarta &bull; Protein Nabati Kacang</span>
                        <h5 class="fw-bold text-dark mb-2'>Ketoprak Betawi Sehat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Perpaduan ketupat, tahu, bihun, dan bumbu kacang kaya protein nabati penambah energi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 12. JAWA BARAT -->
            <div class="col-md-4 recipe-item" data-province="Jawa Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jawa Barat &bull; Vitamin & Mineral</span>
                        <h5 class="fw-bold text-dark mb-2'>Karedok Leunca Sehat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sayuran mentah segar kaya vitamin dipadu kencur hangat pereda pegal.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Jawa Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jawa Barat &bull; Protein Ikan Air Tawar</span>
                        <h5 class="fw-bold text-dark mb-2'>Pepes Ikan Mas Sunda</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan mas lembut berbumbu rempah daun kemangi kukus penambah gizi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Jawa Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jawa Barat &bull; Serat Daun Pepaya</span>
                        <h5 class="fw-bold text-dark mb-2'>Sop Tulang Sumsum Sederhana</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Kaldu tulang sumsum hangat gurih kaya kolagen pembangun sel tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 13. BANTEN -->
            <div class="col-md-4 recipe-item" data-province="Banten">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Banten &bull; Serat & Imunitas</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Menes Khas Banten</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sayuran kuah gurih sehat khas Pandeglang kaya serat dan nutrisi mikro.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Banten">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Banten &bull; Protein Tinggi</span>
                        <h5 class="fw-bold text-dark mb-2'>Sate Bandeng Tanpa Duri</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan bandeng olahan rempah lembut aman dikonsumsi masa pemulihan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Banten">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Banten &bull; Sumber Vitamin Asam</span>
                        <h5 class="fw-bold text-dark mb-2'>Numbu Ikan Kuah Belimbing</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan segar belimbing wuluh penurun panas tubuh alami.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 14. JAWA TENGAH -->
            <div class="col-md-4 recipe-item" data-province="Jawa Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-mug-hot"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jawa Tengah &bull; Pemulihan Lambung</span>
                        <h5 class="fw-bold text-dark mb-2'>Sup Tim Ayam Kampung</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ayam tim obat tradisional dengan jahe merah murni pemulih stamina cepat.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Jawa Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jawa Tengah &bull; Vitamin Nabati</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Lodeh Labu Siam</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sayur berkuah santan encer gurih lembut penambah nafsu makan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Jawa Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jawa Tengah &bull; Serat Sayur Rebus</span>
                        <h5 class="fw-bold text-dark mb-2'>Pecel Tumpang Lethok Sehat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Aneka sayuran rebus dengan sambal tumpang tempe semangit kaya probiotik pencernaan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 15. DI YOGYAKARTA -->
            <div class="col-md-4 recipe-item" data-province="DI Yogyakarta">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">DI Yogyakarta &bull; Tinggi Zat Besi</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Brongkos Sehat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Kluwek dan kacang tolo penambah kadar hemoglobin darah.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="DI Yogyakarta">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">DI Yogyakarta &bull; Karbohidrat Kompleks</span>
                        <h5 class="fw-bold text-dark mb-2'>Soto Bathok Madi</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Soto daging sapi berkaldu bening segar penghangat badan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="DI Yogyakarta">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">DI Yogyakarta &bull; Vitamin Pepaya Muda</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Lombok Ijo Mangut Lele</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Lele asap kuah santan cabai hijau ramah pencernaan dan tinggi protein.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 16. JAWA TIMUR -->
            <div class="col-md-4 recipe-item" data-province="Jawa Timur">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jawa Timur &bull; Imunitas Tubuh</span>
                        <h5 class="fw-bold text-dark mb-2'>Soto Ayam Lamongan Koya</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Kaldu ayam rempah kunyit serai dengan bubuk koya protein tinggi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Jawa Timur">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jawa Timur &bull; Vitamin Sayur Rebus</span>
                        <h5 class="fw-bold text-dark mb-2'>Pecel Madiun Sehat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Aneka sayuran rebus segar berbalut bumbu kacang kaya nutrisi alami.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Jawa Timur">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Jawa Timur &bull; Protein Ikan Asap</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Pe Kuah Santan (Ikan Pari)</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan pari asap berkuah santan pedas gurih pembangkit selera makan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 17. BALI -->
            <div class="col-md-4 recipe-item" data-province="Bali">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fire-flame-curved"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Bali &bull; Anti Inflamasi Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Sup Ikan Menega Base Genep</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan segar bumbu genep Bali sarat rempah rimpang penangkal radang.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Bali">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Bali &bull; Protein Ayam Rempah</span>
                        <h5 class="fw-bold text-dark mb-2'>Ayam Betutu Kuah Kaldu</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan ayam empuk sarat rempah hangat penguat daya tahan tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Bali">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Bali &bull; Serat Sayur Kacang Panjang</span>
                        <h5 class="fw-bold text-dark mb-2'>Jukut Ares Batang Pisang Muda</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup batang pisang muda kuah rempah Bali pembersih pencernaan alami.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 18. NUSA TENGGARA BARAT -->
            <div class="col-md-4 recipe-item" data-province="Nusa Tenggara Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">NTB &bull; Sumber Serat Lokal</span>
                        <h5 class="fw-bold text-dark mb-2'>Bebalung Sapi Kuah Hangat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup iga sapi khas Lombok dengan kuah jahe lengkuas pemulih tenaga.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Nusa Tenggara Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">NTB &bull; Vitamin Nabati Segar</span>
                        <h5 class="fw-bold text-dark mb-2'>Beberuk Terong Sehat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Lalapan terong mentah segar sambal tomat kaya vitamin C penambah selera.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Nusa Tenggara Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">NTB &bull; Protein Ikan Bakar</span>
                        <h5 class="fw-bold text-dark mb-2'>Ayam Taliwang Kuah Rempah</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ayam bakar bumbu plecing kencur hangat penambah kebugaran tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 19. NUSA TENGGARA TIMUR -->
            <div class="col-md-4 recipe-item" data-province="Nusa Tenggara Timur">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">NTT &bull; Karbohidrat Kompleks</span>
                        <h5 class="fw-bold text-dark mb-2'>Se'i Sapi & Jagung Bose</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan jagung tawar bergizi tinggi dipadu daging asap berkualitas.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Nusa Tenggara Timur">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">NTT &bull; Serat Pangan Lokal</span>
                        <h5 class="fw-bold text-dark mb-2'>Ubi Tumbuk NTT</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Makanan lunak sumber energi ramah pencernaan masa penyembuhan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Nusa Tenggara Timur">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">NTT &bull; Vitamin Ikan Kuah Asam</span>
                        <h5 class="fw-bold text-dark mb-2'>Ikan Kuah Asam Kupang</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan segar belimbing wuluh khas NTT pengurang letih dan penurun panas.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 20. KALIMANTAN BARAT -->
            <div class="col-md-4 recipe-item" data-province="Kalimantan Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kalbar &bull; Multivitamin Rempah</span>
                        <h5 class="fw-bold text-dark mb-2'>Bubur Pedas Sambas</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Bubur beras sayuran lokal dan daun kesum melegakan tenggorokan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kalimantan Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kalbar &bull; Protein Ikan Laut</span>
                        <h5 class="fw-bold text-dark mb-2'>Asam Pedas Ikan Kakap Kalbar</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan kuah asam segar penguat metabolisme tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kalimantan Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kalbar &bull; Sayur Pakis Organik</span>
                        <h5 class="fw-bold text-dark mb-2'>Tumis Kelakai Pontianak</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sayur paku darat merah kaya zat besi pembentuk sel darah merah.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 21. KALIMANTAN TENGAH -->
            <div class="col-md-4 recipe-item" data-province="Kalimantan Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kalteng &bull; Omega Ikan Sungai</span>
                        <h5 class="fw-bold text-dark mb-2'>Juhu Singkah Baung</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Gulai umbut rotan khas Dayak dengan ikan baung segar kaya mineral.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kalimantan Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kalteng &bull; Serat Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Kalumpe Sayur Singkong</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan daun singkong tumbuk halus kuah santan kaya zat besi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kalimantan Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kalteng &bull; Protein Ikan Patin</span>
                        <h5 class="fw-bold text-dark mb-2'>Wadi Ikan Sungai Fermentasi</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan awetan fermentasi sehat penambah asam amino esensial tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 22. KALIMANTAN SELATAN -->
            <div class="col-md-4 recipe-item" data-province="Kalimantan Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kalsel &bull; Energi Cepat</span>
                        <h5 class="fw-bold text-dark mb-2'>Soto Banjar Kuah Susu Rempah</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Soto ayam berkaldu harum kayu manis kapulaga pemulih kebugaran.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kalimantan Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kalsel &bull; Vitamin Ikan Gabus</span>
                        <h5 class="fw-bold text-dark mb-2'>Lontong Orari Kuah Ikan Gabus</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Menu lunak berkuah santan gurih kaya protein albumin penyembuh luka.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kalimantan Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kalsel &bull; Protein Ikan Haruan</span>
                        <h5 class="fw-bold text-dark mb-2'>Gangan Asam Banjar</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan gabus kuah asam kunyit nanas segar penyegar badan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 23. KALIMANTAN TIMUR -->
            <div class="col-md-4 recipe-item" data-province="Kalimantan Timur">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish-fins"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kaltim &bull; Albumin Tinggi</span>
                        <h5 class="fw-bold text-dark mb-2'>Gence Ruan Ikan Gabus</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan gabus kaya albumin untuk mempercepat penyembuhan sel tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kalimantan Timur">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kaltim &bull; Karbohidrat Singkong</span>
                        <h5 class="fw-bold text-dark mb-2'>Nasi Bekatak Sehat Kaltim</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan beras unik bernutrisi tinggi khas pedalaman Kalimantan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kalimantan Timur">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kaltim &bull; Sup Tulang Iga Sapi</span>
                        <h5 class="fw-bold text-dark mb-2'>Sop Iga Sapi Kutai Hangat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup iga sapi empuk dengan kuah bening rempah jahe penguat stamina.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 24. KALIMANTAN UTARA -->
            <div class="col-md-4 recipe-item" data-province="Kalimantan Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kaltara &bull; Serat & Protein</span>
                        <h5 class="fw-bold text-dark mb-2'>Kepiting Soka Asam Manis Sehat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Kepiting cangkang lunak kaya mineral zinc penambah vitalitas tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kalimantan Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kaltara &bull; Vitamin Laut Segar</span>
                        <h5 class="fw-bold text-dark mb-2'>Tudai Kupang Kuah Rempah</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Kerang air payau kaya zat besi pencegah kelelahan kronis.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Kalimantan Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Kaltara &bull; Protein Ikan Gabus</span>
                        <h5 class="fw-bold text-dark mb-2'>Ikan Asin Tahai Kuah Santan</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan ikan sungai kuah santan gurih penambah selera makan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 25. SULAWESI UTARA -->
            <div class="col-md-4 recipe-item" data-province="Sulawesi Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulut &bull; Antioksidan Daun</span>
                        <h5 class="fw-bold text-dark mb-2'>Tinutuan (Bubur Manado)</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Bubur sehat kaya sayuran labu, kangkung, bayam, dan kemangi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sulawesi Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulut &bull; Omega Ikan Cakalang</span>
                        <h5 class="fw-bold text-dark mb-2'>Cakalang Fufu Kuah Asam</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan cakalang asap kuah segar rempah rimpang penambah stamina.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sulawesi Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulut &bull; Serat Pepaya Muda</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Garo Manado Pepaya</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Tumis pepaya muda dengan daun kemangi dan santan tipis sehat pencernaan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 26. GORONTALO -->
            <div class="col-md-4 recipe-item" data-province="Gorontalo">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Gorontalo &bull; Protein Ikan Laut</span>
                        <h5 class="fw-bold text-dark mb-2'>Ikan Bilenthung Kuah Asam</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan segar kuah asam pedas menyegarkan pencernaan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Gorontalo">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Gorontalo &bull; Karbohidrat Jagung</span>
                        <h5 class="fw-bold text-dark mb-2'>Binte Biluhuta (Sup Jagung)</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup jagung manis lokal dengan ikan tuna dan parutan kelapa bergizi tinggi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Gorontalo">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Gorontalo &bull; Vitamin C Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Tili'aya Sehat Gorontalo</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan telur dan santan gurih lembut pembangun sel tubuh yang lemah.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 27. SULAWESI TENGAH -->
            <div class="col-md-4 recipe-item" data-province="Sulawesi Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulteng &bull; Kolagen Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Kaledo Palu Kaki Sapi</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup kaki sapi berkuah bening asam jeruk nipis pemulih sendi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sulawesi Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulteng &bull; Protein Teluk Palu</span>
                        <h5 class="fw-bold text-dark mb-2'>Uta Dada Ikan Kuah Santan Rempah</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan kuah santan ringan kaya rempah kemangi penyejuk tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sulawesi Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulteng &bull; Serat Umbi Hutan</span>
                        <h5 class="fw-bold text-dark mb-2'>Ubi Jalar Rebus Palu Madu</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ubi jalar lokal kukus sumber energi sehat pencernaan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 28. SULAWESI BARAT -->
            <div class="col-md-4 recipe-item" data-province="Sulawesi Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulbar &bull; Vitamin C & Tuna</span>
                        <h5 class="fw-bold text-dark mb-2'>Sup Ikan Tuna Mandar</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan tuna segar kuah asam mangga muda kaya vitamin C dan asam lemak.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sulawesi Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulbar &bull; Karbohidrat Singkong</span>
                        <h5 class="fw-bold text-dark mb-2'>Kukure Sayur Mandar</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan makanan pokok tradisional lembut ramah pencernaan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sulawesi Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulbar &bull; Protein Ikan Teri</span>
                        <h5 class="fw-bold text-dark mb-2'>Bau Peapi Ikan Kuah Kuning Mandar</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan segar kuah kuning asam belimbing wuluh penambah stamina.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 29. SULAWESI SELATAN -->
            <div class="col-md-4 recipe-item" data-province="Sulawesi Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulsel &bull; Protein Tinggi</span>
                        <h5 class="fw-bold text-dark mb-2'>Coto Makassar Kuah Rempah</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Daging sapi pilihan dengan kuah kacang dan puluhan rempah penambah kebugaran.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sulawesi Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-mug-hot"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulsel &bull; Kaldu Tulang Sapi</span>
                        <h5 class="fw-bold text-dark mb-2'>Sup Konro Bening Sehat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Iga sapi empuk berkuah rempah kluwek hangat pengisi energi tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sulawesi Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sulsel &bull; Omega Ikan Bandeng</span>
                        <h5 class="fw-bold text-dark mb-2'>Pallubasa Kuah Rempah Kelapa</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan daging sapi berkuah kelapa sangrai gurih pemulih tenaga.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 30. SULAWESI TENGGARA -->
            <div class="col-md-4 recipe-item" data-province="Sulawesi Tenggara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sultra &bull; Serat Sagu</span>
                        <h5 class="fw-bold text-dark mb-2'>Sinonggi Kuah Ikan</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sari pati sagu kenyal disajikan bersama kuah ikan hangat kaya gizi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sulawesi Tenggara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sultra &bull; Protein Ikan Laut</span>
                        <h5 class="fw-bold text-dark mb-2'>Ikan Parende Wakatobi</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan segar kuah asam bening penurun kolesterol dan penyegar tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Sulawesi Tenggara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Sultra &bull; Karbohidrat Jagung Muda</span>
                        <h5 class="fw-bold text-dark mb-2'>Kambalu Sultra Sehat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan talas dan santan lembut bergizi tinggi ramah pencernaan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 31. MALUKU -->
            <div class="col-md-4 recipe-item" data-province="Maluku">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Maluku &bull; Nutrisi Rempah Pala</span>
                        <h5 class="fw-bold text-dark mb-2'>Ikan Kuah Pala Banda</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan kaya buah pala asli Banda penenang saraf dan penghangat tubuh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Maluku">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Maluku &bull; Serat Sagu & Ikan</span>
                        <h5 class="fw-bold text-dark mb-2'>Papeda Ikan Komplit Maluku</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Bubur sagu lembut berpadu kuah kuning ikan laut segar bernutrisi tinggi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Maluku">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Maluku &bull; Protein Ikan Asar</span>
                        <h5 class="fw-bold text-dark mb-2'>Colo-Colo Ikan Asar Maluku</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan asap dengan sambal mentah segar kaya vitamin C penambah nafsu makan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 32. MALUKU UTARA -->
            <div class="col-md-4 recipe-item" data-province="Maluku Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Malut &bull; Karbohidrat Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Gohu Ikan & Kasbi Rebus</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sashimi lokal ikan cakalang segar dengan ubi kayu rebus sumber tenaga.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Maluku Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Malut &bull; Kuah Rempah Ikan</span>
                        <h5 class="fw-bold text-dark mb-2'>Vede Ternate Ikan Kuah Rempah</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan khas Ternate berlimpah rempah daun cengkeh dan sereh.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Maluku Utara">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Malut &bull; Serat Daun Pepaya</span>
                        <h5 class="fw-bold text-dark mb-2'>Popeda Sayur Jantung Pisang</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Olahan jantung pisang rebus kuah rempah sehat penurun tekanan darah.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 33. PAPUA -->
            <div class="col-md-4 recipe-item" data-province="Papua">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish-fins"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua &bull; Omega-3 Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Papeda Kuah Kuning Ikan Kakap</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Makanan pokok sagu berpadu ikan kuah kuning asam segar penambah energi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua &bull; Serat Umbi Sehat</span>
                        <h5 class="fw-bold text-dark mb-2'>Petatas Rebus & Ikan Asar</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ubi jalar manis Papua kaya serat dipadu ikan asar awet alami.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua &bull; Protein Ikan Danau Sentani</span>
                        <h5 class="fw-bold text-dark mb-2'>Sup Ikan Gabus Danau Sentani</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Kaldu ikan gabus air tawar murni tinggi albumin pemulih tenaga.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 34. PAPUA BARAT -->
            <div class="col-md-4 recipe-item" data-province="Papua Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Barat &bull; Vitamin Laut</span>
                        <h5 class="fw-bold text-dark mb-2'>Ikan Bungkus Daun Pepaya</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan laut rempah dibungkus daun pepaya kaya zat besi tinggi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Barat &bull; Protein Ikan Karang</span>
                        <h5 class="fw-bold text-dark mb-2'>Kuah Asam Manokwari</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan karang segar kuah bening cabai rawit pemulih stamina.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua Barat">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Barat &bull; Serat Sayur Pakis</span>
                        <h5 class="fw-bold text-dark mb-2'>Tumis Sayur Pcar Fakfak</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sayur paku segar kaya vitamin penambah darah penangkal anemia.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 35. PAPUA SELATAN -->
            <div class="col-md-4 recipe-item" data-province="Papua Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Selatan &bull; Albumin Rawa</span>
                        <h5 class="fw-bold text-dark mb-2'>Sup Ikan Gabus Merauke</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Kaldu ikan gabus murni kaya albumin untuk mempercepat pemulihan sel.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Selatan &bull; Karbohidrat Sagu Rawa</span>
                        <h5 class="fw-bold text-dark mb-2'>Sagu Lempeng Merauke</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Kue sagu kering panggang sumber energi praktis dan tahan lama.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua Selatan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Selatan &bull; Protein Ikan Kakap Rawa</span>
                        <h5 class="fw-bold text-dark mb-2'>Ikan Kuah Kuning Rawa Merauke</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan air tawar kuah kunyit rempah hangat pengusir masuk angin.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 36. PAPUA TENGAH -->
            <div class="col-md-4 recipe-item" data-province="Papua Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Tengah &bull; Energi Pegunungan</span>
                        <h5 class="fw-bold text-dark mb-2'>Ubi Bakar Madu Paniai</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ubi jalar lokal bakar berlumur madu hutan asli sumber karbohidrat.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Tengah &bull; Protein Air Tawar</span>
                        <h5 class="fw-bold text-dark mb-2'>Sup Ikan Danau Paniai</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan air tawar khas pegunungan hangat bergizi tinggi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua Tengah">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Tengah &bull; Nutrisi Sayur Dataran Tinggi</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Lilin Paniai Rebus</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Tebu telur hutan rebus kaya serat alami penyejuk pencernaan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 37. PAPUA PEGUNUNGAN -->
            <div class="col-md-4 recipe-item" data-province="Papua Pegunungan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fire"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Pegunungan &bull; Nutrisi Alami</span>
                        <h5 class="fw-bold text-dark mb-2'>Bakar Batu Sayur & Umbi</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Teknik masak batu panas menghasilkan sayuran dan umbi utuh bernutrisi.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua Pegunungan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-seedling"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Pegunungan &bull; Serat Organik</span>
                        <h5 class="fw-bold text-dark mb-2'>Sayur Pakis Hutan Organik</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Rebusan pakis hutan pegunungan murni tanpa pengawet.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua Pegunungan">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Pegunungan &bull; Karbohidrat Petatas</span>
                        <h5 class="fw-bold text-dark mb-2'>Petatas Wamena Tumbuk</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ubi jalar lembek hangat sumber energi utama masyarakat pegunungan.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

            <!-- 38. PAPUA BARAT DAYA -->
            <div class="col-md-4 recipe-item" data-province="Papua Barat Daya">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-fish"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Barat Daya &bull; Asam Segar</span>
                        <h5 class="fw-bold text-dark mb-2'>Kuah Asam Raja Ampat</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Sup ikan karang segar khas Sorong belimbing wuluh penambah stamina.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua Barat Daya">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-rice"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Barat Daya &bull; Karbohidrat Sehat</span>
                        <h5 class="fw-bold text-dark mb-2'>Kasbi Kuah Santan Sorong</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ubi kayu lembut kuah santan encer gurih sumber kalori sehat.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 recipe-item" data-province="Papua Barat Daya">
                <div class="card recipe-card">
                    <div class="recipe-img-wrapper"><i class="fa-solid fa-bowl-food"></i></div>
                    <div class="card-body d-flex flex-column p-4">
                        <span class="badge-nutrition mb-2 align-self-start">Papua Barat Daya &bull; Protein Ikan Laut</span>
                        <h5 class="fw-bold text-dark mb-2'>Ikan Bakar Colo Sorong</h5>
                        <p class="text-muted small mb-4 flex-grow-1">Ikan laut segar bakar bumbu kecap limau penyegar pemulih tenaga.</p>
                        <button class="btn btn-success-custom w-100 text-white"><i class="fa-solid fa-book-open me-1"></i>Lihat Detail Resep</button>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="modal fade recipe-modal" id="recipeModal" tabindex="-1" aria-labelledby="recipeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="modal-title fw-bold" id="recipeModalLabel">Resep</h4>
                        <div class="recipe-meta" id="recipeModalMeta"></div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4" id="recipeModalDescription"></p>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <div class="recipe-section">
                                <h6><i class="fa-solid fa-basket-shopping me-2"></i>Bahan-bahan</h6>
                                <ul id="recipeIngredients"></ul>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="recipe-section">
                                <h6><i class="fa-solid fa-list-ol me-2"></i>Cara Membuat</h6>
                                <ol id="recipeSteps"></ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Filter Provinsi -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterRecipes() {
            const selectedProvince = document.getElementById('provinceSelect').value;
            const items = document.querySelectorAll('.recipe-item');

            items.forEach(item => {
                const province = item.getAttribute('data-province');
                if (selectedProvince === 'all' || province === selectedProvince) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        const recipeTemplates = {
            soup: { ingredients: ['500 g bahan utama sesuai nama menu', '1,2 liter air atau kaldu', '3 siung bawang putih, haluskan', '5 butir bawang merah, haluskan', '2 cm jahe, memarkan', '1 batang serai, memarkan', 'Garam dan gula secukupnya', 'Pelengkap sesuai resep daerah'], steps: ['Bersihkan bahan utama dan potong sesuai kebutuhan.', 'Tumis bumbu sampai harum, lalu masukkan serai dan jahe.', 'Tuang air atau kaldu dan didihkan.', 'Masukkan bahan utama. Masak sampai matang dan bumbu meresap.', 'Koreksi rasa, lalu sajikan hangat dengan pelengkap.'] },
            fish: { ingredients: ['500 g ikan segar sesuai nama menu', '5 butir bawang merah', '3 siung bawang putih', '3 buah cabai merah', '2 cm kunyit', '1 batang serai', '2 lembar daun jeruk', '1 buah jeruk nipis', 'Garam secukupnya', 'Air secukupnya'], steps: ['Bersihkan ikan, lumuri jeruk nipis dan sedikit garam, diamkan 10 menit lalu bilas.', 'Haluskan bawang, cabai, dan kunyit. Tumis sampai harum.', 'Masukkan serai dan daun jeruk, lalu tambahkan air.', 'Masukkan ikan dan masak dengan api sedang sampai matang serta bumbu meresap.', 'Koreksi rasa dan sajikan hangat.'] },
            vegetable: { ingredients: ['250 g sayuran utama sesuai nama menu', '100 g bahan pendamping sesuai resep daerah', '3 butir bawang merah', '2 siung bawang putih', '2 buah cabai merah', '1 cm kencur atau jahe', 'Garam secukupnya', 'Air secukupnya', 'Minyak secukupnya'], steps: ['Cuci bersih sayuran dan siapkan bahan pendamping.', 'Haluskan bumbu lalu tumis sampai harum.', 'Masukkan bahan pendamping dan sedikit air.', 'Masukkan sayuran dan masak sampai matang tetapi tidak terlalu lembek.', 'Koreksi rasa lalu sajikan.'] },
            rice: { ingredients: ['300 g beras atau bahan karbohidrat utama', '500 ml air atau kaldu', '200 g lauk utama sesuai nama menu', '3 butir bawang merah', '2 siung bawang putih', '1 batang serai', '1 lembar daun salam', 'Garam secukupnya', 'Pelengkap sesuai resep daerah'], steps: ['Cuci bahan karbohidrat sampai bersih.', 'Siapkan bumbu dan tumis sampai harum jika resep membutuhkan bumbu tumis.', 'Masak bahan karbohidrat dengan air atau kaldu sampai matang.', 'Siapkan lauk utama dengan bumbu khas menu.', 'Sajikan bersama pelengkap tradisional.'] },
            grilled: { ingredients: ['500 g ikan atau ayam sesuai nama menu', '4 siung bawang putih', '5 butir bawang merah', '2 buah cabai', '2 cm kunyit', '1 sdm air jeruk nipis', 'Garam secukupnya', 'Bumbu pelengkap khas daerah', 'Sedikit minyak'], steps: ['Bersihkan bahan utama lalu lumuri jeruk nipis dan garam.', 'Haluskan bumbu dan balurkan merata pada bahan utama.', 'Diamkan 20–30 menit agar bumbu meresap.', 'Bakar atau panggang sampai matang, sambil sesekali dioles bumbu.', 'Sajikan dengan sambal atau pelengkap khas daerah.'] },
            generic: { ingredients: ['500 g bahan utama sesuai nama menu', '5 butir bawang merah', '3 siung bawang putih', '2 buah cabai merah', '2 cm kunyit atau jahe', '1 batang serai', '2 lembar daun jeruk atau daun salam', 'Garam dan gula secukupnya', 'Air secukupnya', 'Minyak secukupnya'], steps: ['Siapkan dan bersihkan seluruh bahan.', 'Haluskan bumbu dasar lalu tumis sampai harum.', 'Masukkan bahan utama dan bumbu aromatik.', 'Tambahkan air secukupnya lalu masak sampai bahan matang dan bumbu meresap.', 'Koreksi rasa dan sajikan hangat.'] }
        };

        function getRecipeTemplate(title) {
            const t = title.toLowerCase();
            if (t.includes('soto') || t.includes('sup') || t.includes('kuah') || t.includes('sop') || t.includes('gangan') || t.includes('papeda')) return recipeTemplates.soup;
            if (t.includes('ikan') || t.includes('fish') || t.includes('cakalang') || t.includes('kakap') || t.includes('patin') || t.includes('gabus') || t.includes('bandeng') || t.includes('lele') || t.includes('udang') || t.includes('kepiting') || t.includes('gonggong') || t.includes('tuna') || t.includes('parende') || t.includes('konro') || t.includes('sapi')) return recipeTemplates.fish;
            if (t.includes('sayur') || t.includes('pecel') || t.includes('karedok') || t.includes('lodeh') || t.includes('jukut') || t.includes('kelakai') || t.includes('pakis') || t.includes('rebung') || t.includes('terong') || t.includes('tumpang')) return recipeTemplates.vegetable;
            if (t.includes('nasi') || t.includes('bubur') || t.includes('ubi') || t.includes('sagu') || t.includes('lontong') || t.includes('mie') || t.includes('mi ')) return recipeTemplates.rice;
            if (t.includes('bakar') || t.includes('betutu') || t.includes('taliwang') || t.includes('seruit') || t.includes('asar')) return recipeTemplates.grilled;
            return recipeTemplates.generic;
        }

        function openRecipe(button) {
            const card = button.closest('.recipe-card');
            const item = button.closest('.recipe-item');
            const title = card.querySelector('h5').textContent.trim();
            const description = card.querySelector('p').textContent.trim();
            const province = item.getAttribute('data-province');
            const badge = card.querySelector('.badge-nutrition').textContent.trim();
            const template = getRecipeTemplate(title);

            document.getElementById('recipeModalLabel').textContent = title;
            document.getElementById('recipeModalDescription').textContent = description;
            document.getElementById('recipeModalMeta').innerHTML = '<span>' + province + '</span><span>' + badge.split('•').pop().trim() + '</span><span>± 4 porsi</span>';
            document.getElementById('recipeIngredients').innerHTML = template.ingredients.map(x => '<li>' + x + '</li>').join('');
            document.getElementById('recipeSteps').innerHTML = template.steps.map(x => '<li>' + x + '</li>').join('');

            bootstrap.Modal.getOrCreateInstance(document.getElementById('recipeModal')).show();
        }

        document.querySelectorAll('.recipe-card button').forEach(button => {
            button.addEventListener('click', function() {
                openRecipe(this);
            });
        });
    </script>
</body>
</html>