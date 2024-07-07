<?php
// Include library for database connection
// require_once 'db_connection.php';
include 'config.php';

// Set header to allow CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Check if mentor_id is provided in the GET request
if (isset($_GET['mentor_id'])) {
    $mentor_id = $_GET['mentor_id'];

    // Query to calculate the average rating for the mentor
    $sql = "SELECT AVG(rating) as average_rating FROM ratings WHERE mentor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mentor_id);
    $stmt->execute();
    $stmt->bind_result($average_rating);
    $stmt->fetch();

    // Prepare the response
    $response = [
        'mentor_id' => $mentor_id,
        'average_rating' => $average_rating
    ];

    echo json_encode($response);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["message" => "Invalid data provided"]);
}
?>
