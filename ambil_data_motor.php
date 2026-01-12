<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
include 'config.php';

// Ambil data kendaraan dengan kategori motor
$sql = "SELECT * FROM kendaraan WHERE kategori = 'motor' ORDER BY id_kendaraan DESC";
$result = mysqli_query($conn, $sql);

$data_motor = [];
while ($row = mysqli_fetch_assoc($result)) {
    // Format harga agar ada titik ribuan untuk tampilan
    $row['harga_format'] = number_format($row['harga'], 0, ',', '.');
    $data_motor[] = $row;
}

echo json_encode($data_motor);
?>