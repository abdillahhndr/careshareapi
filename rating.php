<?php

header("Access-Control-Allow-Origin: *");

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan bahwa semua parameter yang diperlukan tersedia
    if (isset($_POST['mentor_id']) && isset($_POST['user_id'])) {
        $mentor_id = $_POST['mentor_id'];
        $user_id = $_POST['user_id'];
        $rating = $_POST['rating'];

        // Cek apakah user_id sudah ada dalam tabel cart
        $check_query = "SELECT * FROM ratings WHERE mentor_id = '$mentor_id' AND user_id = '$user_id'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            // user_id sudah ada dalam tabel cart
            $response['isSuccess'] = false;
            $response['message'] = "Sudah Terdaftar";
        } else {
            // user_id belum ada dalam tabel cart, tambahkan data
            $sql = "INSERT INTO ratings (mentor_id, user_id,rating) VALUES ('$mentor_id', '$user_id','$rating')";
            if ($conn->query($sql) === TRUE) {
                $response['isSuccess'] = true;
                $response['message'] = "Berhasil rating";
            } else {
                $response['isSuccess'] = false;
                $response['message'] = "Gagal rating: " . $conn->error;
            }
        }
    } else {
        $response['isSuccess'] = false;
        $response['message'] = "Parameter tidak lengkap";
    }
} else {
    $response['isSuccess'] = false;
    $response['message'] = "Metode yang diperbolehkan hanya POST";
}

echo json_encode($response);
?>