<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_hapus'])) {
    $id_hapus = $_POST['id_hapus'];
    if (isset($_SESSION['jurnal_asupan'])) {
        $_SESSION['jurnal_asupan'] = array_filter($_SESSION['jurnal_asupan'], function($item) use ($id_hapus) {
            return $item['id'] !== $id_hapus;
        });
        $_SESSION['jurnal_asupan'] = array_values($_SESSION['jurnal_asupan']);
        $_SESSION['pesan'] = "Catatan asupan berhasil dihapus!";
    }
}

header("Location: jurnal-asupan.php");
exit;
?>