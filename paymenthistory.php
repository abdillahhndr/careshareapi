<?php

header("Access-Control-Allow-Origin: header");
header("Access-Control-Allow-Origin: *");

include 'config.php';

    $id = $_POST["id_user"];

	$sql = "SELECT * FROM subscriptions WHERE user_id = '$id'" ;
	$result = $conn->query($sql);

	if($result->num_rows > 0) {
		$response['isSuccess'] = true;
		$response['message'] = "Berhasil Menampilkan Data Product";
		$response['data'] = array();
		while ($row = $result->fetch_assoc()) {
			$response['data'][] = $row;
		}
	} else {
		$response['isSuccess'] = false;
		$response['message'] = "Gagal menampilkan Data Product";
		$response['data'] = null;
	}
	echo json_encode($response);


?>