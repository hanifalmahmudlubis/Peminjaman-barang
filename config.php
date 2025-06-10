<?php
    // Buat koneksi database
    $conn = new mysqli("localhost", "root", "", "db_pinjam_barang");

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi Gagal: " . $conn->connect_error);
    } else {
        // echo "Koneksi Berhasil";
    }

    // Fungsi untuk menutup koneksi database
    function closeDBConnection() {
        global $conn;
        $conn->close();
    }
?>
