<?php

header("Access-Control-Allow-Origin: *");

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan bahwa semua parameter yang diperlukan tersedia
    if (isset($_POST['forum_id']) && isset($_POST['user_id'])) {
        $forum_id = $_POST['forum_id'];
        $user_id = $_POST['user_id'];

        // Cek apakah user_id sudah ada dalam tabel cart
        $check_query = "SELECT * FROM forum_users WHERE forum_id = '$forum_id' AND user_id = '$user_id'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            // user_id sudah ada dalam tabel cart
            $response['isSuccess'] = false;
            $response['message'] = "Sudah Terdaftar";
        } else {
            // user_id belum ada dalam tabel cart, tambahkan data
            $sql = "INSERT INTO forum_users (forum_id, user_id) VALUES ('$forum_id', '$user_id')";
            if ($conn->query($sql) === TRUE) {
                $response['isSuccess'] = true;
                $response['message'] = "Berhasil Masuk Forum";
            } else {
                $response['isSuccess'] = false;
                $response['message'] = "Gagal Masuk Forum: " . $conn->error;
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
