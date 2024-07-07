<?php
header("Access-Control-Allow-Origin: *");

include 'koneksi.php'; // Memasukkan file koneksi.php yang berisi detail koneksi database

// Memeriksa apakah parameter id_user disediakan
if (isset($_GET['id_user'])) {
    // Melakukan sanitasi input id_user untuk menghindari SQL injection
    $user_id = mysqli_real_escape_string($koneksi, $_GET['id_user']);

    // Query untuk mengambil konten favorit untuk user yang ditentukan
    $sql = "SELECT k.id AS id, k.judul AS judul, k.isi_konten AS isi_konten, k.gambar_konten AS gambar_konten, k.tgl_konten AS tgl_konten
            FROM tb_konten k
            INNER JOIN tb_favorite f ON k.id = f.id_konten
            WHERE f.id_user = '$user_id'";

    // Menjalankan query
    $result = $koneksi->query($sql);

    // Memeriksa apakah query berhasil dieksekusi
    if ($result) {
        // Memeriksa apakah ada konten favorit untuk user tersebut
        if ($result->num_rows > 0) {
            $response['isSuccess'] = true;
            $response['message'] = "Berhasil Menampilkan Data Konten Favorit";
            $response['data'] = array();
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = $row;
            }
        } else {
            $response['isSuccess'] = false;
            $response['message'] = "Tidak ada konten favorit untuk pengguna ini";
            $response['data'] = null;
        }
    } else {
        $response['isSuccess'] = false;
        $response['message'] = "Terjadi kesalahan saat mengambil data konten favorit";
        $response['data'] = null;
    }
} else {
    $response['isSuccess'] = false;
    $response['message'] = "Parameter 'id_user' tidak diberikan";
    $response['data'] = null;
}

// Mengirimkan response dalam format JSON
echo json_encode($response);
?>
