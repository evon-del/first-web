<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

include 'config.php'; 

// Membuat folder uploads jika belum ada
if (!file_exists('uploads')) { mkdir('uploads', 0777, true); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Tangkap data dari FormData
    $user_id    = 1; 
    $kategori   = mysqli_real_escape_string($conn, $_POST['kategori'] ?? '');
    $judul      = mysqli_real_escape_string($conn, $_POST['judul'] ?? '');
    $merek      = mysqli_real_escape_string($conn, $_POST['merek'] ?? '');
    $tahun      = mysqli_real_escape_string($conn, $_POST['tahun'] ?? '');
    $harga      = mysqli_real_escape_string($conn, $_POST['harga'] ?? 0);
    $lokasi     = mysqli_real_escape_string($conn, $_POST['lokasi'] ?? '');
    $desc       = mysqli_real_escape_string($conn, $_POST['deskripsi'] ?? '');
    
    $kondisi    = mysqli_real_escape_string($conn, $_POST['kondisi'] ?? 'Bekas');
    $transmisi  = mysqli_real_escape_string($conn, $_POST['transmisi'] ?? '');
    $mesin      = mysqli_real_escape_string($conn, $_POST['mesin'] ?? '');
    $jarak      = mysqli_real_escape_string($conn, $_POST['jarak'] ?? '');

    $nomor_iklan = "DIRS-" . strtoupper(substr($kategori,0,1)) . rand(1000, 9999);

    // 2. Proses Foto (DIPERBAIKI)
   $foto_paths = [];
if (isset($_POST['foto'])) {
    $photos = json_decode($_POST['foto']);
    if (is_array($photos)) {
        foreach ($photos as $index => $base64_data) {
            if (!empty($base64_data)) {
                $parts = explode(',', $base64_data);
                if (count($parts) > 1) {
                    preg_match('/image\/(.*?);/', $parts[0], $matches);
                    $extension = $matches[1] ?? 'jpg';
                    
                    $image_content = base64_decode($parts[1]);
                    $file_name = "img_" . time() . "_" . $index . "." . $extension;
                    $file_path = "uploads/" . $file_name;
                    
                    if (file_put_contents($file_path, $image_content)) {
                        // PAKSA MENGGUNAKAN FORWARD SLASH (/) AGAR TERBACA BROWSER
                        $foto_paths[] = str_replace('\\', '/', $file_path);
                    }
                }
            }
        }
    }
}
$foto_db = mysqli_real_escape_string($conn, json_encode($foto_paths));

    // 3. Query INSERT
    $sql = "INSERT INTO kendaraan (
                user_id, kategori, judul, merek, tahun, harga, lokasi, 
                deskripsi, kondisi, jarak_tempuh, transmisi, kapasitas_mesin, 
                nomor_iklan, foto, status
            ) VALUES (
                '$user_id', '$kategori', '$judul', '$merek', '$tahun', '$harga', '$lokasi', 
                '$desc', '$kondisi', '$jarak', '$transmisi', '$mesin', 
                '$nomor_iklan', '$foto_db', 'pending'
            )";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "Iklan Berhasil Disimpan!"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
}
?>