<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "dirs_database";

// Matikan laporan error HTML agar tidak merusak JSON
mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal"]);
    exit;
}
?>