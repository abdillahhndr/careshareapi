<?php

$koneksi = mysqli_connect("localhost", "root", "", "db_careshare");

if($koneksi){

} else {
	echo "gagal Connect";
}

?>