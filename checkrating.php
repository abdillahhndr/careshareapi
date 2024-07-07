<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json"); // E

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mentor_id']) && isset($_POST['user_id'])) {
        $mentor_id = intval($_POST['mentor_id']);
        $user_id = intval($_POST['user_id']);

        // Check if the user has already rated the mentor
        $sql = "SELECT COUNT(*) as rating_count FROM ratings WHERE mentor_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $mentor_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['rating_count'] > 0) {
            $response['isRated'] = true;
        } else {
            $response['isRated'] = false;
        }

        echo json_encode($response);

        $stmt->close();
    } else {
        echo json_encode(["message" => "Invalid data provided"]);
    }
} else {
    echo json_encode(["message" => "Only POST method is allowed"]);
}

$conn->close();
?>
