<?php
// Include library for database connection
require_once 'config.php';

// Set header untuk mengizinkan CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json"); // E

// Menerima data dari permintaan POST
$data = json_decode(file_get_contents('php://input'), true);

// Memeriksa apakah data diterima sesuai dengan yang diharapkan
if (isset($data['user_id']) && isset($data['mentor_id'])) {
    $user_id = $data['user_id'];
    $mentor_id = $data['mentor_id'];

    // Buat koneksi ke database
    $conn = new mysqli("localhost", "root", "", "db_careshare");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk memeriksa apakah user memiliki langganan aktif
    $sql = "SELECT status FROM subscriptions WHERE user_id = ? AND mentor_id = ? AND status = 'success' LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $mentor_id);
    $stmt->execute();
    $stmt->store_result();

    $response = [
        'is_subscribed' => $stmt->num_rows > 0
    ];

    echo json_encode($response);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["message" => "Invalid data provided"]);
}
?>