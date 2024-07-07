<?php

header("Access-Control-Allow-Origin: *");

include 'koneksi.php';

$id = $_POST['id'];
$password = md5($_POST['password']); // Tambahkan alamat

$sql = "UPDATE users SET password = '$password' WHERE id=$id"; // Perbarui query untuk menyertakan alamat
$isSuccess = $koneksi->query($sql);

if ($isSuccess) {
    $cek = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_fetch_array(mysqli_query($koneksi, $cek));
    $res['is_success'] = true;
    $res['value'] = 1;
    $res['message'] = "Berhasil ubah password"; // Sertakan alamat dalam respons
    $res['id'] = $result['id'];
} else {
    $res['is_success'] = false;
    $res['value'] = 0;
    $res['message'] = "Gagal ubah password";
}

echo json_encode($res);

?>
