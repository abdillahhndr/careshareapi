<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');


include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = file_get_contents("php://input");
    $data = json_decode($postData, true);

    if (isset($data['email'])) {
        $email = $data['email'];

        $conn = new mysqli("localhost", "root", "", "db_careshare"); // Assuming you have variables set up for database connection

        if ($conn->connect_error) {
            die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
        }

        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die(json_encode(["error" => "Failed to prepare statement: " . $conn->error]));
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $userId = $user['id'];

            echo json_encode(["message" => "Email ditemukan.", "user_id" => $userId]);
        } else {
            echo json_encode(["error" => "Email tidak ditemukan."]);
        }

        $stmt->close();
        $conn->close();
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Email is required."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed."]);
}
?>
