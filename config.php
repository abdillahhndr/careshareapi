<?php
header("Access-Control-Allow-Origin: header");
header("Access-Control-Allow-Origin: *");
$conn = mysqli_connect("localhost", "root", "", "db_careshare");

if($conn){
    // echo "berhasil";
} else {
	echo "gagal Connect";
}

?>