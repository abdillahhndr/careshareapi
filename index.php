<?php
require 'config.php';

// Helper function to send JSON response
function send_response($status, $data) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
}

header("Access-Control-Allow-Origin: header");
header("Access-Control-Allow-Origin: *");
// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get path
$path = isset($_GET['path']) ? $_GET['path'] : '';
$path_params = explode('/', $path);

switch ($method) {
    case 'GET':
        if ($path_params[0] == 'forums') {
            get_forums();
        } else if ($path_params[0] == 'forum' && isset($path_params[1])) {
            get_forum_detail($path_params[1]);
        } else if ($path_params[0] == 'chats' && isset($path_params[1])) {
            get_forum_chats($path_params[1]);
        }
        break;
    case 'POST':
        if ($path_params[0] == 'forum') {
            create_forum();
        } else if ($path_params[0] == 'user') {
            create_user();
        } else if ($path_params[0] == 'chat') {
            create_chat();
        }
        break;
    default:
        send_response(405, ["message" => "Method Not Allowed"]);
        break;
}

function get_forums() {
    global $conn;
    $sql = "SELECT * FROM forums";
    $result = $conn->query($sql);

    // Debug: cek apakah query berhasil dieksekusi
    if (!$result) {
        send_response(500, ["message" => "Error executing query: " . $conn->error]);
        return;
    }

    $forums = [];
    while ($row = $result->fetch_assoc()) {
        // Convert 'id' to integer
        $row['id'] = (int) $row['id'];
        $forums[] = $row;
    }

    // Debug: cek apakah data berhasil diambil dari database
    if (empty($forums)) {
        send_response(200, ["message" => "No forums found"]);
    } else {
        send_response(200, $forums);
    }
}


function get_forum_detail($forum_id) {
    global $conn;
    $sql = "SELECT * FROM forums WHERE id = $forum_id";
    $result = $conn->query($sql);
    $forum = $result->fetch_assoc();

    $sql = "SELECT users.id, users.username FROM forum_users JOIN users ON forum_users.user_id = users.id WHERE forum_users.forum_id = $forum_id";
    $result = $conn->query($sql);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    $forum['users'] = $users;
    send_response(200, $forum);
}

function get_forum_chats($forum_id) {
    global $conn;
    $sql = "SELECT forum_chats.*, users.username FROM forum_chats JOIN users ON forum_chats.user_id = users.id WHERE forum_chats.forum_id = $forum_id ORDER BY forum_chats.created_at ASC";
    $result = $conn->query($sql);
    $chats = [];
    while ($row = $result->fetch_assoc()) {
        $chats[] = $row;
    }
    send_response(200, $chats);
}

function create_forum() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $description = $data['description'];

    $sql = "INSERT INTO forums (name, description) VALUES ('$name', '$description')";
    if ($conn->query($sql) === TRUE) {
        send_response(201, ["message" => "Forum created successfully"]);
    } else {
        send_response(500, ["message" => "Error creating forum: " . $conn->error]);
    }
}

function create_user() {
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if ($conn->query($sql) === TRUE) {
        send_response(201, ["message" => "User created successfully"]);
    } else {
        send_response(500, ["message" => "Error creating user: " . $conn->error]);
    }
}

function create_chat() {
    
    global $conn;
    $data = json_decode(file_get_contents('php://input'), true);
    $forum_id = $data['forum_id'];
    $user_id = $data['user_id'];
    $message = $data['message'];

    $sql = "INSERT INTO forum_chats (forum_id, user_id, message) VALUES ('$forum_id', '$user_id', '$message')";
    if ($conn->query($sql) === TRUE) {
        send_response(201, ["message" => "Chat message sent successfully"]);
    } else {
        send_response(500, ["message" => "Error sending message: " . $conn->error]);
    }
}
?>
