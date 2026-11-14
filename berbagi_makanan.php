<?php
session_start();
include 'koneksi.php'; // Menggunakan koneksi PDO kamu

// Mengambil data nama dan provinsi dari session aktif
$nama_pengguna = isset($_SESSION['nama_panjang']) ? $_SESSION['nama_panjang'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Aprilana Queenzy');
$provinsi_pengguna = isset($_SESSION['provinsi']) ? $_SESSION['provinsi'] : 'DKI Jakarta';

// Membuat inisial huruf pertama otomatis untuk lingkaran avatar
$inisial_nama = strtoupper(substr($nama_pengguna, 0, 1));

// Proses Aksi POST (Posting baru, Like, Komentar, Hapus Post, Hapus Komentar)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 1. Aksi Tambah Postingan Baru
    if (isset($_POST['action']) && $_POST['action'] == 'tambah_post') {
        $teks_post = trim($_POST['isi_postingan']);
        $kategori  = trim($_POST['kategori_gizi']);
        $foto_path = '';

        if (isset($_FILES['foto_makanan']) && $_FILES['foto_makanan']['error'] == 0) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $nama_file = time() . '_' . basename($_FILES['foto_makanan']['name']);
            $target_file = $target_dir . $nama_file;

            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($imageFileType, $allowed_types)) {
                if (move_uploaded_file($_FILES['foto_makanan']['tmp_name'], $target_file)) {
                    $foto_path = $target_file;
                }
            }
        }

        if (!empty($teks_post)) {
            $stmt = $pdo->prepare("INSERT INTO feed_berbagi (nama, provinsi, inisial, konten, kategori, foto, likes, liked_by, komentar) VALUES (?, ?, ?, ?, ?, ?, 0, ?, ?)");
            $stmt->execute([
                $nama_pengguna, 
                $provinsi_pengguna, 
                $inisial_nama, 
                $teks_post, 
                $kategori, 
                $foto_path, 
                json_encode([]), 
                json_encode([])
            ]);
            $_SESSION['flash_message'] = "Postingan berhasil dibagikan!";
            $_SESSION['flash_type'] = "success";
        }
    }

    // 2. Aksi Like Postingan
    elseif (isset($_POST['action']) && $_POST['action'] == 'like_post') {
        $post_id = $_POST['post_id'];
        
        $stmt = $pdo->prepare("SELECT likes, liked_by FROM feed_berbagi WHERE id = ?");
        $stmt->execute([$post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            $likes = intval($post['likes']);
            $liked_by = json_decode($post['liked_by'], true) ?: [];

            if (!in_array($nama_pengguna, $liked_by)) {
                $likes++;
                $liked_by[] = $nama_pengguna;
            } else {
                $likes--;
                $liked_by = array_diff($liked_by, [$nama_pengguna]);
            }

            $update = $pdo->prepare("UPDATE feed_berbagi SET likes = ?, liked_by = ? WHERE id = ?");
            $update->execute([$likes, json_encode(array_values($liked_by)), $post_id]);
        }
    }

    // 3. Aksi Tambah Komentar
    elseif (isset($_POST['action']) && $_POST['action'] == 'tambah_komentar') {
        $post_id = $_POST['post_id'];
        $isi_komentar = trim($_POST['isi_komentar']);

        if (!empty($isi_komentar)) {
            $stmt = $pdo->prepare("SELECT komentar FROM feed_berbagi WHERE id = ?");
            $stmt->execute([$post_id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($post) {
                $komentar_list = json_decode($post['komentar'], true) ?: [];
                $komentar_list[] = [
                    'nama'    => $nama_pengguna,
                    'inisial' => $inisial_nama,
                    'teks'    => $isi_komentar
                ];

                $update = $pdo->prepare("UPDATE feed_berbagi SET komentar = ? WHERE id = ?");
                $update->execute([json_encode($komentar_list), $post_id]);
            }
        }
    }

    // 4. Aksi Hapus Postingan
    elseif (isset($_POST['action']) && $_POST['action'] == 'hapus_post') {
        $post_id = $_POST['post_id'];
        $stmt = $pdo->prepare("DELETE FROM feed_berbagi WHERE id = ? AND nama = ?");
        $stmt->execute([$post_id, $nama_pengguna]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['flash_message'] = "Postingan berhasil dihapus.";
            $_SESSION['flash_type'] = "danger";
        }
    }

    // 5. Aksi Hapus Komentar
    elseif (isset($_POST['action']) && $_POST['action'] == 'hapus_komentar') {
        $post_id = $_POST['post_id'];
        $komentar_index = intval($_POST['komentar_index']);

        $stmt = $pdo->prepare("SELECT komentar FROM feed_berbagi WHERE id = ?");
        $stmt->execute([$post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            $komentar_list = json_decode($post['komentar'], true) ?: [];
            if (isset($komentar_list[$komentar_index]) && $komentar_list[$komentar_index]['nama'] == $nama_pengguna) {
                unset($komentar_list[$komentar_index]);
                $komentar_list = array_values($komentar_list);

                $update = $pdo->prepare("UPDATE feed_berbagi SET komentar = ? WHERE id = ?");
                $update->execute([json_encode($komentar_list), $post_id]);
                $_SESSION['flash_message'] = "Komentar berhasil dihapus.";
                $_SESSION['flash_type'] = "danger";
            }
        }
    }

    header("Location: berbagi_makanan.php");
    exit;
}
$tab_filter = isset($_GET['tab']) ? trim($_GET['tab']) : 'beranda';
if ($tab_filter == 'Karbohidrat Kompleks' || $tab_filter == 'Protein Nabati' || $tab_filter == 'Protein Hewani' || $tab_filter == 'Buah Segar') {
    $stmt_all = $pdo->prepare("SELECT * FROM feed_berbagi WHERE kategori = ? ORDER BY id DESC");
    $stmt_all->execute([$tab_filter]);
} else {
    $stmt_all = $pdo->query("SELECT * FROM feed_berbagi ORDER BY id DESC");
}
$feed_berbagi = $stmt_all->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berbagi Makanan Sehat - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary-green: #059669; --dark-green: #064e3b; --light-green: #ecfdf5; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #334155; line-height: 1.6; }
        .navbar { backdrop-filter: blur(16px); background: rgba(255, 255, 255, 0.95); box-shadow: 0 4px 20px rgba(0,0,0,0.03); padding: 1rem 0; border-bottom: 1px solid #e2e8f0; }
        .header-section { padding: 40px 0 20px; margin-top: 60px; background: linear-gradient(180deg, #ecfdf5 0%, #f8fafc 100%); border-bottom: 1px solid #e2e8f0; }
        
        /* Style untuk Tab Navigasi Horizontal Modern */
        .feed-tabs-container {
            display: flex;
            gap: 24px;
            overflow-x: auto;
            white-space: nowrap;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 24px;
            padding-bottom: 0;
        }
        .feed-tab-item {
            font-weight: 600;
            font-size: 0.95rem;
            color: #64748b;
            text-decoration: none;
            padding-bottom: 12px;
            position: relative;
            transition: color 0.2s ease;
        }
        .feed-tab-item:hover {
            color: var(--primary-green);
        }
        .feed-tab-item.active {
            color: var(--primary-green);
            font-weight: 700;
        }
        .feed-tab-item.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--primary-green);
            border-radius: 3px 3px 0 0;
        }

        .post-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); margin-bottom: 20px; }
        .post-img { width: 100%; max-height: 320px; object-fit: cover; border-radius: 8px; margin-bottom: 12px; }
        .avatar-circle { width: 42px; height: 42px; background-color: var(--primary-green); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 18px; }
        .comment-box { background: #f8fafc; border-radius: 8px; padding: 8px 12px; font-size: 0.8rem; margin-bottom: 6px; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-extrabold fs-3 text-success d-flex align-items-center text-decoration-none" href="index.php">
                <i class="fa-solid fa-seedling me-2 text-success"></i>Gizi<span class="text-dark">Lokal</span>
            </a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold me-2"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Menu</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="header-section text-center">
        <div class="container">
            <span class="badge px-3 py-2 rounded-pill mb-2 fw-bold shadow-sm" style="background-color: #d1fae5; color: var(--dark-green);">
                <i class="fa-solid fa-people-arrows me-1"></i> Komunitas Berbagi Makanan Sehat
            </span>
            <h1 class="fw-extrabold text-dark mb-2 fs-3">Berbagi Hidangan Sehat Antar Pengguna</h1>
            <p class="text-muted small">Bagikan makanan sehat berlebih Anda ke sesama pengguna, berikan apresiasi lewat suka, atau tulis komentar untuk mengambil makanan.</p>
        </div>
    </header>

    <main class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                <!-- Notifikasi / Alert Sukses -->
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="alert alert-<?= $_SESSION['flash_type'] ?? 'success' ?> alert-dismissible fade show py-2 small fw-bold" role="alert">
                        <i class="fa-solid fa-circle-info me-1"></i> <?= $_SESSION['flash_message']; ?>
                        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php 
                        unset($_SESSION['flash_message']);
                        unset($_SESSION['flash_type']);
                    ?>
                <?php endif; ?>

                <!-- Tab Navigasi Kategori & Beranda Style Modern (Tanpa Terpopuler) -->
                <div class="feed-tabs-container">
                    <a href="berbagi_makanan.php?tab=beranda" class="feed-tab-item <?= ($tab_filter == 'beranda') ? 'active' : '' ?>">Beranda</a>
                    <a href="berbagi_makanan.php?tab=Karbohidrat Kompleks" class="feed-tab-item <?= ($tab_filter == 'Karbohidrat Kompleks') ? 'active' : '' ?>">Karbohidrat Kompleks</a>
                    <a href="berbagi_makanan.php?tab=Protein Nabati" class="feed-tab-item <?= ($tab_filter == 'Protein Nabati') ? 'active' : '' ?>">Protein Nabati</a>
                    <a href="berbagi_makanan.php?tab=Protein Hewani" class="feed-tab-item <?= ($tab_filter == 'Protein Hewani') ? 'active' : '' ?>">Protein Hewani</a>
                    <a href="berbagi_makanan.php?tab=Buah Segar" class="feed-tab-item <?= ($tab_filter == 'Buah Segar') ? 'active' : '' ?>">Buah Segar</a>
                </div>

                <!-- Form Postingan Utama -->
                <div class="post-card p-3 mb-4 bg-white">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar-circle me-2">
                            <?= htmlspecialchars($inisial_nama ?? '') ?>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark small"><?= htmlspecialchars($nama_pengguna ?? '') ?></h6>
                            <span class="text-muted" style="font-size: 0.7rem;"><i class="fa-solid fa-location-dot me-1"></i> <?= htmlspecialchars($provinsi_pengguna ?? '') ?></span>
                        </div>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="tambah_post">
                        <textarea name="isi_postingan" class="form-control border-0 bg-light small mb-2" rows="2" placeholder="Mau bagikan makanan sehat apa hari ini? Ceritakan kondisi dan gizinya..." required></textarea>

                        <div id="file-name-display" class="text-success small mb-2 fw-bold" style="font-size: 0.75rem; display: none;"></div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <div class="d-flex gap-2 align-items-center">
                                <label class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 mb-0" style="font-size: 0.75rem; cursor: pointer;">
                                    <i class="fa-solid fa-image me-1"></i> Tambah Foto 
                                    <input type="file" name="foto_makanan" id="foto_makanan" accept="image/*" hidden onchange="showFileName(this)">
                                </label>
                                <select name="kategori_gizi" class="form-select form-select-sm rounded-pill text-muted" style="font-size: 0.75rem; width: auto;" required>
                                    <option value="">Pilih Kategori Gizi</option>
                                    <option value="Karbohidrat Kompleks">Karbohidrat Kompleks</option>
                                    <option value="Protein Nabati">Protein Nabati</option>
                                    <option value="Protein Hewani">Protein Hewani</option>
                                    <option value="Buah Segar">Buah Segar</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold" style="background-color: var(--primary-green); border: none;">Posting</button>
                        </div>
                    </form>
                </div>

                <!-- Feed Daftar Postingan -->
                <?php if (empty($feed_berbagi)): ?>
                <div class="text-center py-5 bg-white rounded-3 border">
                    <i class="fa-solid fa-utensils text-muted fa-2x mb-3"></i>
                    <h6 class="fw-bold text-dark">Belum ada makanan yang dibagikan</h6>
                    <p class="text-muted small mb-0">Jadilah pengguna pertama yang membagikan makanan sehat ke sesama!</p>
                </div>
                <?php else: ?>
                    <?php foreach ($feed_berbagi as $post): ?>
                    <?php 
                        $liked_by = !empty($post['liked_by']) ? json_decode($post['liked_by'], true) : [];
                        $sudah_like = in_array($nama_pengguna, $liked_by);
                        $icon_like_class = $sudah_like ? "fa-solid text-danger" : "fa-regular";
                        $komentar_list = !empty($post['komentar']) ? json_decode($post['komentar'], true) : [];
                    ?>
                    <div class="post-card p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2" style="width: 36px; height: 36px; font-size: 15px;">
                                    <?= htmlspecialchars($post['inisial'] ?? '') ?>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark small"><?= htmlspecialchars($post['nama'] ?? '') ?></h6>
                                    <span class="text-muted" style="font-size: 0.7rem;"><i class="fa-solid fa-location-dot me-1"></i> <?= htmlspecialchars($post['provinsi'] ?? '') ?> &bull; <span class="text-success"><?= htmlspecialchars($post['kategori'] ?? '') ?></span></span>
                                </div>
                            </div>
                            <?php if (isset($post['nama']) && $post['nama'] == $nama_pengguna): ?>
                                <form action="" method="POST" class="mb-0">
                                    <input type="hidden" name="action" value="hapus_post">
                                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id'] ?? '') ?>">
                                    <button type="submit" class="btn btn-link btn-sm text-danger p-0 text-decoration-none" style="font-size: 0.75rem;" onclick="return confirm('Hapus postingan ini?')">
                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <p class="small text-dark mb-2"><?= htmlspecialchars($post['konten'] ?? '') ?></p>

                        <?php if (!empty($post['foto'])): ?>
                            <img src="<?= htmlspecialchars($post['foto']) ?>" alt="Foto Makanan" class="post-img">
                        <?php endif; ?>

                        <!-- Tombol Like & Komentar -->
                        <div class="d-flex gap-4 border-top pt-2 align-items-center" style="font-size: 0.85rem;">
                            <!-- Form Like -->
                            <form action="" method="POST" class="mb-0">
                                <input type="hidden" name="action" value="like_post">
                                <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id'] ?? '') ?>">
                                <button type="submit" class="btn btn-link p-0 text-decoration-none text-muted fw-bold" style="font-size: 0.85rem;">
                                    <i class="<?= $icon_like_class ?> fa-heart me-1"></i> 
                                    <span><?= intval($post['likes'] ?? 0) ?> Suka</span>
                                </button>
                            </form>

                            <span class="text-muted">
                                <i class="fa-regular fa-comment me-1"></i> <?= count($komentar_list) ?> Komentar
                            </span>
                        </div>

                        <!-- Daftar Komentar -->
                        <div class="mt-3 pt-2 border-top">
                            <?php if (!empty($komentar_list)): ?>
                                <div class="mb-2" style="max-height: 150px; overflow-y: auto;">
                                    <?php foreach ($komentar_list as $index => $kom): ?>
                                    <div class="comment-box d-flex align-items-start justify-content-between gap-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 24px; height: 24px; font-size: 10px; flex-shrink: 0;">
                                                <?= htmlspecialchars($kom['inisial'] ?? '') ?>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark" style="font-size: 0.75rem;"><?= htmlspecialchars($kom['nama'] ?? '') ?></span>
                                                <p class="mb-0 text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($kom['teks'] ?? '') ?></p>
                                            </div>
                                        </div>
                                        <?php if (isset($kom['nama']) && $kom['nama'] == $nama_pengguna): ?>
                                            <form action="" method="POST" class="mb-0">
                                                <input type="hidden" name="action" value="hapus_komentar">
                                                <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id'] ?? '') ?>">
                                                <input type="hidden" name="komentar_index" value="<?= intval($index) ?>">
                                                <button type="submit" class="btn btn-link text-danger p-0" style="font-size: 0.7rem;"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Form Kirim Komentar -->
                            <form action="" method="POST" class="d-flex gap-2 mt-2">
                                <input type="hidden" name="action" value="tambah_komentar">
                                <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id'] ?? '') ?>">
                                <input type="text" name="isi_komentar" class="form-control form-control-sm rounded-pill bg-light border-0 px-3" placeholder="Tulis komentar..." required>
                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3" style="font-size: 0.75rem; background-color: var(--primary-green); border: none;">Kirim</button>
                            </form>
                        </div>

                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <footer class="text-center py-3 text-muted border-top bg-white mt-4" style="font-size: 0.8rem;">
        <p class="mb-0">&copy; 2026 GiziLokal Indonesia. Berbagi Makanan Sehat Antar Pengguna.</p>
    </footer>

    <script>
        function showFileName(input) {
            const display = document.getElementById('file-name-display');
            if (input.files && input.files[0]) {
                display.innerText = "Foto dipilih: " + input.files[0].name;
                display.style.display = "block";
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>