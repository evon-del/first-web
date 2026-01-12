<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
include 'config.php';

// Ambil data kendaraan dengan kategori 'mobil'
$sql = "SELECT * FROM kendaraan WHERE kategori = 'mobil' ORDER BY id_kendaraan DESC";
$result = mysqli_query($conn, $sql);

$data_mobil = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Format harga agar ada titik ribuan
    $row['harga_format'] = number_format($row['harga'], 0, ',', '.');
    $data_mobil[] = $row;
}

echo json_encode($data_mobil);
?>