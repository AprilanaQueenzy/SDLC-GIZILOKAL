<?php
session_start();
// Jika user sudah login, jangan tampilkan landing page, langsung lempar ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GiziLokal - Solusi Cerdas Pemenuhan Gizi & Pangan Nusantara</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation Library CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { 
            --primary-green: #059669; 
            --emerald-light: #34d399;
            --dark-green: #064e3b; 
            --light-green: #ecfdf5;
        }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #ffffff; 
            color: #334155; 
            overflow-x: hidden; 
            line-height: 1.7; 
        }
        
        /* Navbar Modern Blur */
        .navbar { 
            backdrop-filter: blur(16px); 
            background: rgba(255, 255, 255, 0.9); 
            box-shadow: 0 10px 30px rgba(0,0,0,0.04); 
            transition: all 0.3s ease;
        }
        
        /* Hero Section dengan Mesh Gradient Halus */
        .hero { 
            padding: 180px 0 130px; 
            background: radial-gradient(circle at 90% 10%, rgba(52, 211, 153, 0.15) 0%, transparent 40%),
                        radial-gradient(circle at 10% 90%, rgba(5, 150, 105, 0.1) 0%, transparent 50%),
                        #ffffff;
            position: relative; 
        }
        
        /* Section Title Elegan */
        .section-title { 
            font-weight: 800; 
            color: #0f172a; 
            position: relative; 
            padding-bottom: 15px; 
            letter-spacing: -0.5px;
        }
        .section-title::after { 
            content: ''; 
            position: absolute; 
            bottom: 0; 
            left: 50%; 
            transform: translateX(-50%); 
            width: 60px; 
            height: 4px; 
            background: linear-gradient(90deg, var(--primary-green), var(--emerald-light)); 
            border-radius: 4px; 
        }

        /* --- STYLING FOTO ORGANIK & GRADASI MENYELURUH --- */
        .story-img-wrapper {
            position: relative;
            border-radius: 50px 20px 50px 20px; /* Bentuk organik asimetris dinamis */
            overflow: visible; /* Biarkan elemen dekoratif keluar sedikit jika perlu */
            background: transparent;
        }
        
        /* Inner container khusus untuk masking gambar agar bersih */
        .img-mask-box {
            border-radius: 50px 20px 50px 20px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 30px 60px rgba(6, 78, 59, 0.15);
            background: #0f172a;
        }

        .story-img {
            width: 100%;
            height: 440px;
            object-fit: cover;
            /* Efek gradasi transparan (*fade out*) di bagian bawah agar menyatu mulus dengan latar */
            mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 75%, rgba(0,0,0,0) 100%);
            -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 75%, rgba(0,0,0,0) 100%);
            transition: transform 0.7s cubic-bezier(0.165, 0.84, 0.44, 1), filter 0.5s ease;
            filter: brightness(0.96);
        }

        .story-img-wrapper:hover .story-img {
            transform: scale(1.06);
            filter: brightness(1);
        }

        /* Aksen Background Glow di Belakang Foto */
        .story-img-wrapper::before {
            content: '';
            position: absolute;
            top: -15px;
            left: -15px;
            right: -15px;
            bottom: -15px;
            background: linear-gradient(135deg, var(--emerald-light), var(--primary-green));
            border-radius: 60px 25px 60px 25px;
            z-index: -1;
            opacity: 0.25;
            filter: blur(15px);
            transition: opacity 0.4s ease;
        }
        .story-img-wrapper:hover::before {
            opacity: 0.45;
        }

        /* Floating Badge Estetik */
        .floating-badge {
            position: absolute;
            bottom: 15px;
            left: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            padding: 14px 18px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            animation: floatBadge 4s ease-in-out infinite;
            z-index: 10;
        }
        @keyframes floatBadge {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        /* Carousel Styling Berkelas dengan Gradasi & Sudut Organik */
        .carousel-custom-wrapper {
            position: relative;
        }
        .carousel-custom-wrapper::before {
            content: '';
            position: absolute;
            top: -15px;
            left: -15px;
            right: -15px;
            bottom: -15px;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            border-radius: 60px 25px 60px 25px;
            z-index: -1;
            opacity: 0.2;
            filter: blur(15px);
        }
        .carousel-item img { 
            border-radius: 50px 20px 50px 20px; 
            box-shadow: 0 30px 60px rgba(0,0,0,0.18); 
            height: 480px; 
            object-fit: cover; 
            mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 80%, rgba(0,0,0,0) 100%);
            -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 80%, rgba(0,0,0,0) 100%);
        }

        /* Step Card Mewah */
        .step-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 28px;
            padding: 40px 30px;
            height: 100%;
            position: relative;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        }
        .step-card:hover {
            transform: translateY(-8px);
            border-color: var(--emerald-light);
            box-shadow: 0 20px 40px rgba(5,150,105,0.08);
        }
        .step-number {
            position: absolute;
            top: -22px;
            left: 35px;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            font-weight: 800;
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(5,150,105,0.3);
        }
        
        /* FAQ Modern */
        .accordion-item {
            border: 1px solid #e2e8f0 !important;
            border-radius: 20px !important;
            overflow: hidden;
            margin-bottom: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.01);
        }
        .accordion-button {
            background-color: #f8fafc !important;
            font-weight: 700;
            color: #0f172a !important;
            box-shadow: none !important;
            padding: 22px 25px;
        }
        .accordion-button:not(.collapsed) {
            background-color: var(--light-green) !important;
            color: var(--dark-green) !important;
        }

        /* Feature List Modern */
        .feature-row {
            transition: all 0.3s ease;
            padding: 16px 20px;
            border-radius: 20px;
            border: 1px solid transparent;
        }
        .feature-row:hover {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            transform: translateX(6px);
        }
    </style>
</head>
<body>

    <!-- Navbar Utama -->
    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-extrabold fs-3 text-success d-flex align-items-center" href="#">
                <i class="fa-solid fa-seedling me-2 text-success"></i>Gizi<span class="text-dark">Lokal</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center mb-2 mb-lg-0">
                    <li class="nav-item mx-2"><a class="nav-link fw-semibold text-dark" href="#tentang">Tentang</a></li>
                    <li class="nav-item mx-2"><a class="nav-link fw-semibold text-dark" href="#mengapa">Pangan Lokal</a></li>
                    <li class="nav-item mx-2"><a class="nav-link fw-semibold text-dark" href="#fitur">Fitur AI</a></li>
                    <li class="nav-item mx-2"><a class="nav-link fw-semibold text-dark" href="#carakerja">Cara Kerja</a></li>
                    <li class="nav-item ms-3">
                        <a href="login.php" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm" style="background-color: var(--primary-green); border: none;">Masuk / Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                    <span class="badge px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm" style="background-color: var(--light-green) !important; color: var(--dark-green) !important;">
                        <i class="fa-solid fa-shield-heart me-1"></i> Standar Gizi Resmi Kemenkes & WHO
                    </span>
                    <h1 class="display-4 fw-extrabold mb-4 text-dark" style="line-height: 1.2; letter-spacing: -1px;">
                        Membangun Generasi Sehat Lewat Kekayaan <span class="text-success">Pangan Nusantara</span>
                    </h1>
                    <p class="lead text-muted mb-4" style="font-size: 1.15rem;">
                        <strong>GiziLokal</strong> adalah platform pemantauan gizi keluarga berbasis kecerdasan buatan (AI) terdepan yang mengangkat kembali nilai nutrisi luar biasa dari bahan pangan lokal Indonesia.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="login.php" class="btn btn-success btn-lg rounded-pill px-5 shadow-lg fw-bold" style="background-color: var(--primary-green); border: none;">Mulai Akses Fitur</a>
                        <a href="#tentang" class="btn btn-outline-dark btn-lg rounded-pill px-4 fw-semibold">Pelajari Dulu</a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000">
                    <div class="carousel-custom-wrapper">
                        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3500">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80" class="d-block w-100" alt="Sayuran Segar Lokal">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80" class="d-block w-100" alt="Kuliner Sehat Nusantara">
                                </div>
                                <div class="carousel-item">
                                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80" class="d-block w-100" alt="Makanan Bergizi">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Bagian Visual Storytelling: Tentang GiziLokal -->
    <section id="tentang" class="py-5 bg-white">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="story-img-wrapper">
                        <div class="img-mask-box">
                            <img src="https://images.unsplash.com/photo-1610348725531-843dff563e2c?auto=format&fit=crop&w=800&q=80" alt="Bahan Pangan Lokal Sehat dan Segar" class="story-img">
                        </div>
                        <div class="floating-badge">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success text-white p-2 rounded-3"><i class="fa-solid fa-seedling"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark small">100% Bahan Alami</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.75rem;">Dipanen langsung dari petani lokal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <span class="text-success fw-bold text-uppercase small tracking-wider">Filosofi Kami</span>
                    <h2 class="fw-extrabold text-dark mt-2 mb-3">Kekayaan Alam Nusantara untuk Kesehatan Keluarga</h2>
                    <p class="text-muted mb-4">
                        Makanan sehat tidak harus mahal atau berasal dari produk impor luar negeri. Indonesia dianugerahi sumber daya alam yang melimpah—mulai dari ikan kembung lokal yang kaya omega-3, daun kelor penuh kalsium, hingga ragam umbi bernutrisi tinggi. 
                    </p>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="p-3 rounded-circle fs-4" style="background-color: var(--light-green); color: var(--primary-green);"><i class="fa-solid fa-check"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Teruji Secara Klinis & Tradisional</h6>
                            <p class="text-muted small mb-0">Memadukan kearifan lokal dengan perhitungan sains modern.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-circle fs-4" style="background-color: var(--light-green); color: var(--primary-green);"><i class="fa-solid fa-seedling"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">100% Berkelanjutan</h6>
                            <p class="text-muted small mb-0">Mendukung penuh para petani dan nelayan lokal di seluruh penjuru negeri.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="mengapa" class="py-5 bg-light">
        <div class="container py-4">
            <div class="row align-items-center g-5 flex-row-reverse">
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="story-img-wrapper">
                        <div class="img-mask-box">
                            <img src="https://images.unsplash.com/photo-1588854337236-6889d631faa8?auto=format&fit=crop&w=800&q=80" alt="Hasil Pertanian Lokal Indonesia" class="story-img">
                        </div>
                        <div class="floating-badge">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success text-white p-2 rounded-3"><i class="fa-solid fa-heart-pulse"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark small">Segar & Bernutrisi</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.75rem;">Nutrisi terjaga tanpa bahan pengawet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="text-success fw-bold text-uppercase small tracking-wider">Keunggulan Pangan Lokal</span>
                    <h2 class="fw-extrabold text-dark mt-2 mb-3">Kenapa Harus Beralih ke Bahan Pangan Sekitar?</h2>
                    <p class="text-muted mb-4">
                        Pangan lokal menawarkan kesegaran optimal karena tiba di dapur Anda tanpa melalui rantai distribusi global yang panjang dan melelahkan.
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-4 border shadow-sm">
                                <i class="fa-solid fa-wallet text-success fs-4 mb-2"></i>
                                <h6 class="fw-bold">Lebih Ekonomis</h6>
                                <p class="small text-muted mb-0">Ramah di kantong tanpa kompromi kualitas gizi.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 bg-white rounded-4 border shadow-sm">
                                <i class="fa-solid fa-earth-asia text-success fs-4 mb-2"></i>
                                <h6 class="fw-bold">Jejak Karbon Rendah</h6>
                                <p class="small text-muted mb-0">Ramah lingkungan dan menjaga kelestarian alam.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="fitur" class="py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="text-success fw-bold text-uppercase small tracking-wider">Inovasi Teknologi</span>
                <h2 class="section-title mt-2">Fitur Pintar Berbasis Teknologi AI</h2>
                <p class="text-muted mt-3">Nikmati kemudahan mengelola nutrisi harian keluarga lewat inovasi digital terkini yang interaktif.</p>
            </div>
            
            <div class="row align-items-center g-5">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="d-flex align-items-start feature-row mb-3">
                        <div class="fs-2 me-4 mt-1" style="color: var(--primary-green) !important;"><i class="fa-solid fa-calculator"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Hitung Gizi & Kalori Otomatis</h5>
                            <p class="text-muted small mb-0">Kalkulator cerdas untuk mengukur kebutuhan kalori, karbohidrat, protein, dan lemak harian keluarga berdasarkan standar resmi Kemenkes.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start feature-row mb-3">
                        <div class="text-success fs-2 me-4 mt-1"><i class="fa-solid fa-camera"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Scan Kalori Foto AI</h5>
                            <p class="text-muted small mb-0">Cukup arahkan kamera ke hidangan makanan Anda, teknologi AI kami langsung mengenali jenis masakan dan menghitung total kalorinya.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start feature-row mb-3">
                        <div class="text-warning fs-2 me-4 mt-1"><i class="fa-solid fa-recycle"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">AI Cegah Food Waste</h5>
                            <p class="text-muted small mb-0">Punya sisa bahan makanan di kulkas? Foto bahan tersebut, dan AI akan meracik resep masakan nusantara lezat agar tidak terbuang sia-sia.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start feature-row">
                        <div class="text-danger fs-2 me-4 mt-1"><i class="fa-solid fa-robot"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Konsultasi AI Kesehatan 24/7</h5>
                            <p class="text-muted small mb-0">Tanya jawab seputar masalah kesehatan ringan, rekomendasi makanan pemulihan, hingga takaran gizi ideal kapan saja secara instan.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-left">
                    <div class="story-img-wrapper">
                        <div class="img-mask-box">
                            <img src="https://images.unsplash.com/photo-1543353071-10c8ba85a904?auto=format&fit=crop&w=800&q=80" alt="Scan Kalori AI Makanan Sehat" class="story-img" style="height: 460px;">
                        </div>
                        <div class="floating-badge">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success text-white p-2 rounded-3"><i class="fa-solid fa-bolt"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark small">AI Recognition</h6>
                                    <p class="text-muted mb-0" style="font-size: 0.75rem;">Analisis gizi instan berkecepatan tinggi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="carakerja" class="py-5 bg-light">
        <div class="container py-4">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Bagaimana Cara Kerja GiziLokal?</h2>
                <p class="text-muted mt-3">Langkah mudah memulai perjalanan gizi sehat keluarga dalam hitungan detik.</p>
            </div>
            <div class="row g-4 pt-3">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card mt-3">
                        <div class="step-number">1</div>
                        <h4 class="fw-bold h5 mt-3 mb-3">Buat Akun & Profil</h4>
                        <p class="text-muted small mb-0">Daftar secara gratis, lalu masukkan data anggota keluarga agar sistem menyesuaikan target kalori dan gizi spesifik sesuai standar kesehatan.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card mt-3">
                        <div class="step-number">2</div>
                        <h4 class="fw-bold h5 mt-3 mb-3">Gunakan Fitur Pintar AI</h4>
                        <p class="text-muted small mb-0">Manfaatkan pemindai foto makanan otomatis atau masukkan bahan pangan lokal yang tersedia di dapur Anda untuk dianalisis gizinya.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card mt-3">
                        <div class="step-number">3</div>
                        <h4 class="fw-bold h5 mt-3 mb-3">Pantau & Tumbuh Sehat</h4>
                        <p class="text-muted small mb-0">Dapatkan laporan harian perkembangan gizi, rekomendasi resep nusantara kreatif, dan konsultasi instan kapan pun dibutuhkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5 bg-white">
        <div class="container py-4 col-lg-8">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
                <p class="text-muted mt-3">Temukan jawaban cepat seputar penggunaan platform GiziLokal.</p>
            </div>
            <div class="accordion" id="faqAccordion" data-aos="fade-up">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            Apakah platform GiziLokal ini gratis digunakan?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            Ya! Seluruh fitur dasar pemantauan gizi, kalkulator harian, katalog cemilan, hingga AI scanner dapat diakses secara gratis oleh seluruh masyarakat Indonesia untuk mendukung kesehatan keluarga.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            Bagaimana cara kerja fitur Scan Kalori Foto AI?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            Anda cukup mengambil atau mengunggah foto hidangan makanan Anda melalui fitur scan. Kecerdasan buatan (AI) kami akan menganalisis bentuk visual makanan tersebut dan mengestimasikan jumlah kalori serta kandungan zat gizinya secara instan.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            Apakah data gizi yang disajikan sesuai standar medis?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted small">
                            Tentu saja. Seluruh rujukan takaran gizi dan rekomendasi dalam GiziLokal disusun berdasarkan pedoman angka kecukupan gizi resmi dari Kementerian Kesehatan Republik Indonesia dan standar Organisasi Kesehatan Dunia (WHO).
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5 text-white text-center" style="background: linear-gradient(135deg, var(--dark-green), var(--primary-green));">
        <div class="container py-4" data-aos="zoom-in">
            <h2 class="fw-bold mb-3">Siap Memulai Pola Hidup Sehat Bersama GiziLokal?</h2>
            <p class="lead mb-4 opacity-75 col-lg-8 mx-auto">Bergabunglah sekarang dan nikmati seluruh kemudahan memantau nutrisi keluarga dengan kearifan pangan lokal.</p>
            <a href="login.php" class="btn btn-light btn-lg rounded-pill px-5 fw-bold text-success shadow-lg" style="color: var(--dark-green) !important;">Masuk ke Sistem Sekarang</a>
        </div>
    </section>
    <footer class="bg-dark text-white py-5">
        <div class="container text-center">
            <h3 class="fw-bold mb-3"><i class="fa-solid fa-seedling text-success me-2"></i>GiziLokal</h3>
            <p class="text-white-50 small mb-4">Platform Edukasi dan Pemantauan Gizi Pangan Nusantara Berbasis Teknologi.</p>
            <p class="text-muted small mb-0">&copy; 2026 GiziLokal Technologies. Seluruh hak cipta dilindungi undang-undang.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 900,
            once: true
        });
    </script>
</body>
</html>