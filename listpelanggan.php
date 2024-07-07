<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'config.php';

$id_user = $conn->real_escape_string($_POST['id_user']);
$sql = "SELECT c.id, p.username, m.nama
FROM subscriptions c 
JOIN users p ON c.user_id = p.id 
JOIN mentors m ON c.mentor_id = m.id 
WHERE m.id_user = '$id_user' AND c.status = 'success'";
$result = $conn->query($sql);

$response = array();

if ($result->num_rows > 0) {
    $response['isSuccess'] = true;
    $response['message'] = "Berhasil Menampilkan list pelanggan";
    $response['data'] = array();
    while ($row = $result->fetch_assoc()) {
        $response['data'][] = $row;
    }
} else {
    $response['isSuccess'] = false;
    $response['message'] = "Gagal menampilkan list pelanggan";
    $response['data'] = null;
}

echo json_encode($response);

?>
