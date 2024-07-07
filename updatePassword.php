<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'koneksi.php';

$id = $_POST['id'];
$password = md5($_POST['password']);

$sql = "UPDATE users SET password = '$password' WHERE id = $id";
$isSuccess = $koneksi->query($sql);

$res = [];

if ($isSuccess) {
    // Update session or return user data with new password
    // Example: $_SESSION['password'] = $password;
    $res['is_success'] = true;
    $res['value'] = 1;
    $res['message'] = "Password updated successfully";
} else {
    $res['is_success'] = false;
    $res['value'] = 0;
    $res['message'] = "Failed to update password";
}

echo json_encode($res);

?>
