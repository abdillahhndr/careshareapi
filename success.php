<?php

header("Access-Control-Allow-Origin: *");

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan bahwa semua parameter yang diperlukan tersedia
    if (isset($_POST['id_user'])) {
        $order_id = $_POST['order_id'];
        $id_user = $_POST['id_user'];

        // Cek apakah id_user sudah ada dalam tabel cart
        $check_query = "SELECT * FROM subscriptions WHERE id = '$order_id'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            // id_user sudah ada dalam tabel cart
            // $sql_delete_cart = "DELETE FROM cart WHERE id_user='$id_user'";
            // Hapus data cart berhasil, sekarang perbarui status pembayaran
            $sql_update_payment = "UPDATE subscriptions SET status='success' WHERE id='$order_id'";
            if ($conn->query($sql_update_payment) === TRUE) {
                $response['isSuccess'] = true;
                $response['message'] = "Berhasil membayar ";
            } else {
                $response['isSuccess'] = false;
                $response['message'] = "Gagal mengupdate status pembayaran: " . $conn->error;
            }
        } else {
            // id_user belum ada dalam tabel cart, tangani sesuai kebutuhan
            $response['isSuccess'] = false;
            $response['message'] = "";
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
