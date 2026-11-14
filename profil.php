<?php
session_start();

$nama_panjang = isset($_SESSION['nama_panjang']) ? $_SESSION['nama_panjang'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '');
$email        = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$provinsi     = isset($_SESSION['provinsi']) ? $_SESSION['provinsi'] : '';
$umur         = isset($_SESSION['umur']) ? $_SESSION['umur'] : '';
$berat        = isset($_SESSION['berat']) ? $_SESSION['berat'] : '';
$tujuan       = isset($_SESSION['tujuan']) ? $_SESSION['tujuan'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary-green: #059669; }
        html, body { height: 100%; margin: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #334155; display: flex; align-items: center; justify-content: center; }
        .profile-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 8px 20px rgba(0,0,0,0.03); width: 100%; max-width: 440px; overflow: hidden; margin: 15px auto; }
        .profile-header { background: linear-gradient(135deg, #ecfdf5 0%, #f1f5f9 100%); padding: 20px 15px; text-align: center; border-bottom: 1px solid #e2e8f0; }
        .avatar-circle { width: 60px; height: 60px; background-color: var(--primary-green); color: white; font-size: 26px; font-weight: 800; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; box-shadow: 0 4px 10px rgba(5,150,105,0.2); }
        .form-control, .form-select { border-radius: 10px; padding: 9px 12px; border: 1px solid #cbd5e1; font-size: 0.85rem; background-color: #f8fafc; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-green); box-shadow: 0 0 0 3px rgba(5,150,105,0.1); background-color: #fff; }
        .form-label { font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 4px; }
        .btn-success-custom { background-color: var(--primary-green); border: none; border-radius: 10px; padding: 10px; font-size: 0.9rem; font-weight: 700; color: white; width: 100%; transition: all 0.2s; }
        .btn-success-custom:hover { background-color: #047857; color: white; }
        .btn-back { border-radius: 10px; padding: 10px; font-size: 0.9rem; font-weight: 700; color: #64748b; background: #f1f5f9; border: none; width: 100%; transition: all 0.2s; text-decoration: none; display: block; text-align: center; }
        .btn-back:hover { background: #e2e8f0; color: #334155; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center align-items-center">
        <div class="col-12 d-flex justify-content-center">
            <div class="profile-card">
                
                <!-- Notifikasi / Alert Sukses Update Profil -->
                <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
                <div class="alert alert-success alert-dismissible fade show m-3 mb-0 py-2 small fw-bold" role="alert">
                    <i class="fa-solid fa-circle-check me-1"></i> Profil berhasil diupdate!
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <div class="profile-header">
                    <div class="avatar-circle">
                        <?= !empty($nama_panjang) ? strtoupper(substr($nama_panjang, 0, 1)) : 'U' ?>
                    </div>
                    <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($nama_panjang ?: 'Pengguna Baru') ?></h5>
                    <p class="text-muted small mb-0" style="font-size: 0.8rem;"><i class="fa-solid fa-envelope me-1"></i> <?= htmlspecialchars($email ?: 'Belum ada email') ?></p>
                </div>

                <div class="p-3 px-4">
                    <form action="update_profil.php" method="POST">
                        <div class="mb-2">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_panjang" class="form-control" value="<?= htmlspecialchars($nama_panjang) ?>" placeholder="Masukkan nama panjang..." required>
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control bg-light" value="<?= htmlspecialchars($email) ?>" readonly>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Provinsi</label>
                            <select name="provinsi" class="form-select" required>
                                <option value="">Pilih Provinsi</option>
                                <option value="Aceh" <?= ($provinsi == 'Aceh') ? 'selected' : '' ?>>Aceh</option>
                                <option value="Sumatera Utara" <?= ($provinsi == 'Sumatera Utara') ? 'selected' : '' ?>>Sumatera Utara</option>
                                <option value="Sumatera Barat" <?= ($provinsi == 'Sumatera Barat') ? 'selected' : '' ?>>Sumatera Barat</option>
                                <option value="Riau" <?= ($provinsi == 'Riau') ? 'selected' : '' ?>>Riau</option>
                                <option value="Kepulauan Riau" <?= ($provinsi == 'Kepulauan Riau') ? 'selected' : '' ?>>Kepulauan Riau</option>
                                <option value="Jambi" <?= ($provinsi == 'Jambi') ? 'selected' : '' ?>>Jambi</option>
                                <option value="Sumatera Selatan" <?= ($provinsi == 'Sumatera Selatan') ? 'selected' : '' ?>>Sumatera Selatan</option>
                                <option value="Bangka Belitung" <?= ($provinsi == 'Bangka Belitung') ? 'selected' : '' ?>>Kepulauan Bangka Belitung</option>
                                <option value="Bengkulu" <?= ($provinsi == 'Bengkulu') ? 'selected' : '' ?>>Bengkulu</option>
                                <option value="Lampung" <?= ($provinsi == 'Lampung') ? 'selected' : '' ?>>Lampung</option>
                                <option value="DKI Jakarta" <?= ($provinsi == 'DKI Jakarta') ? 'selected' : '' ?>>DKI Jakarta</option>
                                <option value="Jawa Barat" <?= ($provinsi == 'Jawa Barat') ? 'selected' : '' ?>>Jawa Barat</option>
                                <option value="Banten" <?= ($provinsi == 'Banten') ? 'selected' : '' ?>>Banten</option>
                                <option value="Jawa Tengah" <?= ($provinsi == 'Jawa Tengah') ? 'selected' : '' ?>>Jawa Tengah</option>
                                <option value="DI Yogyakarta" <?= ($provinsi == 'DI Yogyakarta') ? 'selected' : '' ?>>DI Yogyakarta</option>
                                <option value="Jawa Timur" <?= ($provinsi == 'Jawa Timur') ? 'selected' : '' ?>>Jawa Timur</option>
                                <option value="Bali" <?= ($provinsi == 'Bali') ? 'selected' : '' ?>>Bali</option>
                                <option value="Nusa Tenggara Barat" <?= ($provinsi == 'Nusa Tenggara Barat') ? 'selected' : '' ?>>Nusa Tenggara Barat</option>
                                <option value="Nusa Tenggara Timur" <?= ($provinsi == 'Nusa Tenggara Timur') ? 'selected' : '' ?>>Nusa Tenggara Timur</option>
                                <option value="Kalimantan Barat" <?= ($provinsi == 'Kalimantan Barat') ? 'selected' : '' ?>>Kalimantan Barat</option>
                                <option value="Kalimantan Tengah" <?= ($provinsi == 'Kalimantan Tengah') ? 'selected' : '' ?>>Kalimantan Tengah</option>
                                <option value="Kalimantan Selatan" <?= ($provinsi == 'Kalimantan Selatan') ? 'selected' : '' ?>>Kalimantan Selatan</option>
                                <option value="Kalimantan Timur" <?= ($provinsi == 'Kalimantan Timur') ? 'selected' : '' ?>>Kalimantan Timur</option>
                                <option value="Kalimantan Utara" <?= ($provinsi == 'Kalimantan Utara') ? 'selected' : '' ?>>Kalimantan Utara</option>
                                <option value="Sulawesi Utara" <?= ($provinsi == 'Sulawesi Utara') ? 'selected' : '' ?>>Sulawesi Utara</option>
                                <option value="Gorontalo" <?= ($provinsi == 'Gorontalo') ? 'selected' : '' ?>>Gorontalo</option>
                                <option value="Sulawesi Tengah" <?= ($provinsi == 'Sulawesi Tengah') ? 'selected' : '' ?>>Sulawesi Tengah</option>
                                <option value="Sulawesi Barat" <?= ($provinsi == 'Sulawesi Barat') ? 'selected' : '' ?>>Sulawesi Barat</option>
                                <option value="Sulawesi Selatan" <?= ($provinsi == 'Sulawesi Selatan') ? 'selected' : '' ?>>Sulawesi Selatan</option>
                                <option value="Sulawesi Tenggara" <?= ($provinsi == 'Sulawesi Tenggara') ? 'selected' : '' ?>>Sulawesi Tenggara</option>
                                <option value="Maluku" <?= ($provinsi == 'Maluku') ? 'selected' : '' ?>>Maluku</option>
                                <option value="Maluku Utara" <?= ($provinsi == 'Maluku Utara') ? 'selected' : '' ?>>Maluku Utara</option>
                                <option value="Papua" <?= ($provinsi == 'Papua') ? 'selected' : '' ?>>Papua</option>
                                <option value="Papua Barat" <?= ($provinsi == 'Papua Barat') ? 'selected' : '' ?>>Papua Barat</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="form-label">Umur (Tahun)</label>
                                <input type="number" name="umur" class="form-control" value="<?= htmlspecialchars($umur) ?>" placeholder="Contoh: 16" min="1" max="120" required>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Berat Badan (kg)</label>
                                <input type="number" name="berat" class="form-control" value="<?= htmlspecialchars($berat) ?>" placeholder="Contoh: 50" min="1" step="0.1" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tujuan Kesehatan</label>
                            <select name="tujuan" class="form-select" required>
                                <option value="">-- Pilih Tujuan --</option>
                                <option value="Menjaga Kesehatan" <?= ($tujuan == 'Menjaga Kesehatan') ? 'selected' : '' ?>>Menjaga Kesehatan Umum</option>
                                <option value="Turun BB" <?= ($tujuan == 'Turun BB') ? 'selected' : '' ?>>Menurunkan Berat Badan</option>
                                <option value="Naik BB" <?= ($tujuan == 'Naik BB') ? 'selected' : '' ?>>Menaikkan Berat Badan</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-success-custom shadow-sm">Simpan Perubahan Data</button>
                            <a href="index.php" class="btn btn-back">Kembali ke Beranda</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>