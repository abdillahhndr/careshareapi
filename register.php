<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json"); // Ensure JSON response

include 'koneksi.php';

$response = array();

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $email = $_POST['email'];
        $address = $_POST['address']; // Add this line to get the address from the request

        $cek = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = mysqli_fetch_array(mysqli_query($koneksi, $cek));

        if (isset($result)) {
            $response['value'] = 2;
            $response['message'] = "Username atau email telah digunakan";
        } else {
            $insert = "INSERT INTO users (username, email, password, address) VALUES ('$username', '$email', '$password', '$address')"; // Modify the query to include the address
            if (mysqli_query($koneksi, $insert)) {
                $response['value'] = 1;
                $response['message'] = "Berhasil didaftarkan";
            } else {
                $response['value'] = 0;
                $response['message'] = "Gagal didaftarkan";
            }
        }
    } else {
        $response['value'] = 0;
        $response['message'] = "Invalid Request Method";
    }
} catch (Exception $e) {
    $response['value'] = 0;
    $response['message'] = "Terjadi kesalahan: " . $e->getMessage();
}

echo json_encode($response);

?>
