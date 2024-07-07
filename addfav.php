<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $response = array();

    // Check if the required POST parameters are set
    if (isset($_POST['id_user']) && isset($_POST['id_konten'])) {
        $id_user = $_POST['id_user'];
        $id_konten = $_POST['id_konten'];

        // Sanitize inputs to prevent SQL injection
        $id_user = mysqli_real_escape_string($koneksi, $id_user);
        $id_konten = mysqli_real_escape_string($koneksi, $id_konten);

        // Insert the new favorite into tb_favorite
        $insert = "INSERT INTO tb_favorite (id_user, id_konten) VALUES ('$id_user', '$id_konten')";
        if (mysqli_query($koneksi, $insert)) {
            $response['value'] = 1;
            $response['message'] = "Konten berhasil ditambahkan ke favorit";
        } else {
            $response['value'] = 0;
            $response['message'] = "Gagal menambahkan konten ke favorit";
        }
    } else {
        // Missing required POST parameters
        $response['value'] = 0;
        $response['message'] = "Parameter yang diperlukan tidak ada";
    }

    echo json_encode($response);
} else {
    // Invalid request method
    $response['value'] = 0;
    $response['message'] = "Metode permintaan tidak valid";
    echo json_encode($response);
}

?>
