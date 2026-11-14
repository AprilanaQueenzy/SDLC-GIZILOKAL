<?php
error_reporting(E_ALL);
log_errors: ini_set('display_errors', 1);

echo "<h3>Mulai pengecekan sistem...</h3>";
if (file_exists('koneksi.php')) {
    echo "<p style='color:green;'>✓ File koneksi.php ditemukan.</p>";
    require_once 'koneksi.php';
} else {
    die("<p style='color:red;'>✗ File koneksi.php TIDAK ADA!</p>");
}
if (isset($pdo)) {
    echo "<p style='color:green;'>✓ Variabel koneksi \$pdo berhasil dibuat.</p>";
} else {
    die("<p style='color:red;'>✗ Variabel \$pdo gagal diinisialisasi.</p>");
}

try {
    $stmt = $pdo->query("SELECT DATABASE() as db_name");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p style='color:blue;'>ℹ PHP saat ini terhubung ke database: <b>" . $row['db_name'] . "</b></p>";
    $stmtUsers = $pdo->query("SELECT COUNT(*) as total FROM users");
    $dataUsers = $stmtUsers->fetch(PDO::FETCH_ASSOC);
    echo "<p style='color:green;'>✓ Tabel 'users' BERHASIL dibaca! Jumlah data user saat ini: " . $dataUsers['total'] . "</p>";

} catch (PDOException $e) {
    echo "<div style='background: #ffe6e6; padding: 15px; border: 1px solid red; border-radius: 5px;'>";
    echo "<h4 style='color: red;'>TERJADI ERROR MYSQL/PDO:</h4>";
    echo "<p><b>Pesan Error:</b> " . $e->getMessage() . "</p>";
    echo "<p><b>Kode Error:</b> " . $e->getCode() . "</p>";
    echo "</div>";
}
?>