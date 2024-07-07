<?php

header("Access-Control-Allow-Origin: *");

include 'koneksi.php';

$id = $_POST['id'];
$username = $_POST['username'];
$email = $_POST['email'];
$address = $_POST['address']; // Tambahkan alamat

$sql = "UPDATE users SET username = '$username', email = '$email', address = '$address' WHERE id=$id"; // Perbarui query untuk menyertakan alamat
$isSuccess = $koneksi->query($sql);

if ($isSuccess) {
    $cek = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_fetch_array(mysqli_query($koneksi, $cek));
    $res['is_success'] = true;
    $res['value'] = 1;
    $res['message'] = "Berhasil edit user";
    $res['username'] = $result['username'];
    $res['email'] = $result['email'];
    $res['address'] = $result['address']; // Sertakan alamat dalam respons
    $res['id'] = $result['id'];
} else {
    $res['is_success'] = false;
    $res['value'] = 0;
    $res['message'] = "Gagal edit user";
}

echo json_encode($res);

?>
