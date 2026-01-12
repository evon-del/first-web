<?php
include 'config.php';
header('Content-Type: application/json');

// Untuk sementara kita gunakan user_id = 1 (sama dengan di simpan_iklan.php)
$user_id = 1; 

$sql = "SELECT * FROM kendaraan WHERE user_id = '$user_id' ORDER BY id_kendaraan DESC";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['harga_format'] = number_format($row['harga'], 0, ',', '.');
    $data[] = $row;
}
echo json_encode($data);
?>