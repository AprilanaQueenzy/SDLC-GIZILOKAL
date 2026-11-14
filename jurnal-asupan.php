<?php
session_start();

// Inisialisasi array data asupan di session jika belum ada
if (!isset($_SESSION['jurnal_asupan'])) {
    $_SESSION['jurnal_asupan'] = [];
}

$pesan_sukses = "";

// Proses Tambah Data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_asupan'])) {
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $waktu = $_POST['waktu'] ?? 'Makan Siang';
    $menu = trim($_POST['menu'] ?? '');
    $kategori = $_POST['kategori'] ?? 'Pangan Lokal Nusantara';
    $kalori = intval($_POST['kalori'] ?? 0);
    $protein = intval($_POST['protein'] ?? 0);

    if (!empty($menu)) {
        $_SESSION['jurnal_asupan'][] = [
            'id' => uniqid(),
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'menu' => $menu,
            'kategori' => $kategori,
            'kalori' => $kalori,
            'protein' => $protein
        ];
        $_SESSION['pesan'] = "Catatan asupan berhasil ditambahkan!";
    }
    header("Location: jurnal-asupan.php");
    exit;
}

// Ambil pesan dari session jika ada
if (isset($_SESSION['pesan'])) {
    $pesan_sukses = $_SESSION['pesan'];
    unset($_SESSION['pesan']);
}

// Perhitungan tanggal
$tanggal_hari_ini = date('Y-m-d');
$tanggal_kemarin = date('Y-m-d', strtotime('-1 day'));

$kalori_hari_ini = 0; $protein_hari_ini = 0;
$kalori_kemarin = 0; $protein_kemarin = 0;

foreach ($_SESSION['jurnal_asupan'] as $item) {
    if ($item['tanggal'] === $tanggal_hari_ini) {
        $kalori_hari_ini += $item['kalori'];
        $protein_hari_ini += $item['protein'];
    } elseif ($item['tanggal'] === $tanggal_kemarin) {
        $kalori_kemarin += $item['kalori'];
        $protein_kemarin += $item['protein'];
    }
}

$selisih_kalori = $kalori_hari_ini - $kalori_kemarin;
$selisih_protein = $protein_hari_ini - $protein_kemarin;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Asupan Harian - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary-green: #059669; --emerald-light: #34d399; --dark-green: #064e3b; --light-green: #ecfdf5; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #334155; overflow-x: hidden; line-height: 1.7; }
        .navbar { backdrop-filter: blur(16px); background: rgba(255, 255, 255, 0.9); box-shadow: 0 10px 30px rgba(0,0,0,0.04); }
        .jurnal-header { padding: 150px 0 50px; background: radial-gradient(circle at 10% 20%, rgba(52, 211, 153, 0.15) 0%, transparent 40%), #f8fafc; }
        .stat-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.02); transition: transform 0.3s ease; height: 100%; }
        .stat-card:hover { transform: translateY(-5px); border-color: var(--emerald-light); }
        .content-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-extrabold fs-3 text-success d-flex align-items-center" href="landing.php">
                <i class="fa-solid fa-seedling me-2 text-success"></i>Gizi<span class="text-dark">Lokal</span>
            </a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item ms-3">
                        <a href="landing.php" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm" style="background-color: var(--primary-green); border: none;">Kembali ke Beranda</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="jurnal-header">
        <div class="container">
            <?php if (!empty($pesan_sukses)): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> <?php echo $pesan_sukses; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="row align-items-center mb-4">
                <div class="col-md-8">
                    <span class="badge px-3 py-2 rounded-pill mb-2 fw-bold" style="background-color: var(--light-green); color: var(--dark-green);">
                        <i class="fa-solid fa-scale-balanced me-1"></i> Analisis Perbandingan Konsumsi
                    </span>
                    <h1 class="fw-extrabold text-dark mb-1">Jurnal Asupan Harian</h1>
                </div>
                <div class="col-md-4 text-md-end">
                    <button class="btn btn-success rounded-pill px-4 py-3 fw-bold shadow-sm" style="background-color: var(--primary-green); border: none;" data-bs-toggle="modal" data-bs-target="#modalTambahAsupan">
                        <i class="fa-solid fa-plus me-2"></i>Catat Makanan Baru
                    </button>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-md-4"><div class="stat-card border-success"><h6>HARI INI</h6><h3 class="fw-extrabold"><?php echo $kalori_hari_ini; ?> <span class="fs-6 fw-normal text-muted">kkal</span></h3></div></div>
                <div class="col-md-4"><div class="stat-card"><h6>KEMARIN</h6><h3 class="fw-extrabold"><?php echo $kalori_kemarin; ?> <span class="fs-6 fw-normal text-muted">kkal</span></h3></div></div>
                <div class="col-md-4"><div class="stat-card bg-light"><h6>SELISIH</h6><h3 class="fw-extrabold"><?php echo $selisih_kalori > 0 ? "+$selisih_kalori" : $selisih_kalori; ?> <span class="fs-6 fw-normal text-muted">kkal</span></h3></div></div>
            </div>
        </div>
    </header>

    <section class="py-4 mb-5">
        <div class="container">
            <div class="content-card">
                <table class="table align-middle table-hover">
                    <thead class="table-light text-uppercase fs-7 text-muted">
                        <tr><th>Tanggal & Waktu</th><th>Menu</th><th>Kategori</th><th>Kalori</th><th>Protein</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php if (empty($_SESSION['jurnal_asupan'])): ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada catatan asupan. Silakan klik "Catat Makanan Baru".</td></tr>
                        <?php else: ?>
                            <?php foreach ($_SESSION['jurnal_asupan'] as $item): ?>
                            <tr>
                                <td><?php echo $item['tanggal']; ?><br><small class="text-muted"><?php echo $item['waktu']; ?></small></td>
                                <td><?php echo $item['menu']; ?></td>
                                <td><?php echo $item['kategori']; ?></td>
                                <td><?php echo $item['kalori']; ?> kkal</td>
                                <td><?php echo $item['protein']; ?> g</td>
                                <td>
                                <form action="hapus-asupan.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                    <input type="hidden" name="id_hapus" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle p-2">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Modal Tambah Asupan -->
    <div class="modal fade" id="modalTambahAsupan" tabindex="-1" aria-labelledby="modalTambahAsupanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <!-- ACTION DISELESAIKAN MENGGUNAKAN TANDA MINUS (-) -->
                <form action="jurnal-asupan.php" method="POST">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="modalTambahAsupanLabel">Catat Asupan Makanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tanggal</label>
                            <input type="date" class="form-control rounded-pill px-3" name="tanggal" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Waktu Makan</label>
                            <select class="form-select rounded-pill px-3" name="waktu">
                                <option value="Sarapan">Sarapan</option>
                                <option value="Makan Siang" selected>Makan Siang</option>
                                <option value="Makan Malam">Makan Malam</option>
                                <option value="Camilan">Camilan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Menu Makanan</label>
                            <input type="text" class="form-control rounded-pill px-3" name="menu" placeholder="Contoh: Pecel Lele Pangan Lokal" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select class="form-select rounded-pill px-3" name="kategori">
                                <option value="Pangan Lokal Nusantara" selected>Pangan Lokal Nusantara</option>
                                <option value="Protein Nabati">Protein Nabati</option>
                                <option value="Protein Hewani">Protein Hewani</option>
                                <option value="Karbohidrat">Karbohidrat</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Kalori (kkal)</label>
                                <input type="number" class="form-control rounded-pill px-3" name="kalori" placeholder="350" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Protein (gram)</label>
                                <input type="number" class="form-control rounded-pill px-3" name="protein" placeholder="20" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah_asupan" class="btn btn-success rounded-pill px-4" style="background-color: var(--primary-green); border: none;">Simpan Catatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>