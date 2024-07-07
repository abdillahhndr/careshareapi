<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json"); // Ensure JSON response

include 'koneksi.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $cek = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($koneksi, $cek);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $response['value'] = 1;
        $response['message'] = "berhasil login";
        $response['username'] = $row['username'];
        $response['email'] = $row['email'];
        $response['address'] = $row['address'];
        $response['id'] = $row['id'];
    } else {
        $response['value'] = 0;
        $response['message'] = "Username atau password salah";
    }
    echo json_encode($response);
} else {
    $response['value'] = 0;
    $response['message'] = "Invalid Request Method";
    echo json_encode($response);
}

?>
