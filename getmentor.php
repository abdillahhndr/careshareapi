<?php

header("Access-Control-Allow-Origin: header");
header("Access-Control-Allow-Origin: *");
include 'config.php';

$sql = "SELECT * FROM mentors" ;
	$result = $conn->query($sql);

	if($result->num_rows > 0) {
		$response['isSuccess'] = true;
		$response['message'] = "Berhasil Menampilkan Data Mentor";
		$response['data'] = array();
		while ($row = $result->fetch_assoc()) {
			$response['data'][] = $row;
		}
	} else {
		$response['isSuccess'] = false;
		$response['message'] = "Gagal menampilkan Data Mentor";
		$response['data'] = null;
	}
	echo json_encode($response);

?>