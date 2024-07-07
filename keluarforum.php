<?php

header("Access-Control-Allow-Origin: header");
header("Access-Control-Allow-Origin: *");

include_once 'config.php';

if (isset($_POST["forum_id"])&& isset($_POST['user_id'])) {
    $forum_id = $_POST["forum_id"];
    $user_id = $_POST["user_id"];
} else {
}
$query = "DELETE FROM forum_users WHERE forum_id= '$forum_id' AND user_id = '$user_id'";
$execute = mysqli_query($conn, $query);
$arruser = [];
if ($execute) {
    $arruser["success"] = "true";
    echo "berhasil dihapus";
} else {
    $arruser["success"] = "false";
    echo "gagal dihapus";
}
print(json_encode($arruser));
?>